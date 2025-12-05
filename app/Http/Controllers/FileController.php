<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function dashboard()
    {
        try {
            Log::info('Dashboard accessed');
            return view('dashboard');
        } catch (\Exception $e) {
            Log::error('Error accessing dashboard: ' . $e->getMessage());
            abort(500);
        }
    }

    public function index()
    {
        try {
            $files = File::where('user_id', auth()->id())->latest()->get();
            Log::info('Files page accessed', ['file_count' => $files->count()]);
            return view('files.index', compact('files'));
        } catch (\Exception $e) {
            Log::error('Error accessing files page: ' . $e->getMessage());
            abort(500);
        }
    }

    public function store(StoreFileRequest $request)
    {
        try {
            Log::info('File upload attempt started', [
                'has_file' => $request->hasFile('file'),
                'has_file_input' => $request->has('file'),
                'all_keys' => array_keys($request->all()),
                'expires_at' => $request->expires_at,
            ]);

            // Check if file was uploaded
            if (!$request->hasFile('file')) {
                Log::warning('No file in request', [
                    'request_keys' => array_keys($request->all()),
                    'files' => $request->allFiles(),
                ]);
                return back()->withErrors(['file' => 'Please select a file to upload.']);
            }

            $file = $request->file('file');

            if (!$file) {
                Log::warning('File is null after hasFile check');
                return back()->withErrors(['file' => 'Please select a file to upload.']);
            }

            if (!$file->isValid()) {
                Log::warning('Invalid file uploaded', [
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage(),
                ]);
                return back()->withErrors(['file' => 'Invalid file uploaded: ' . $file->getErrorMessage()]);
            }

            // Get storage disk from config (defaults to 'public' for local storage)
            $storageDisk = config('files.storage_disk', 'public');

            $path = $file->store('uploads', $storageDisk);
            Log::info('File stored', ['path' => $path, 'disk' => $storageDisk]);

            $expiresAt = null;
            if ($request->filled('expires_at') && !empty(trim($request->expires_at))) {
                try {
                    $expiresAtValue = trim($request->expires_at);
                    Log::info('Processing expiration date', ['value' => $expiresAtValue]);

                    // Try datetime-local format first (Y-m-d\TH:i)
                    if (strpos($expiresAtValue, 'T') !== false) {
                        $expiresAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $expiresAtValue);
                    }
                    // Try standard datetime format (Y-m-d H:i:s)
                    elseif (strpos($expiresAtValue, ' ') !== false) {
                        $expiresAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $expiresAtValue);
                    }
                    // Try just date format
                    else {
                        $expiresAt = \Carbon\Carbon::createFromFormat('Y-m-d', $expiresAtValue)->endOfDay();
                    }

                    // Validate it's in the future
                    if ($expiresAt && $expiresAt->isPast()) {
                        Log::warning('Expiration date is in the past', ['expires_at' => $expiresAt]);
                        return back()->withErrors(['expires_at' => 'The expiration date must be in the future.']);
                    }

                    Log::info('Expiration date parsed successfully', ['expires_at' => $expiresAt]);
                } catch (\Exception $e) {
                    Log::warning('Invalid expiration date format', [
                        'expires_at' => $request->expires_at,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Don't fail the upload if expiration date is invalid, just log it and continue
                }
            }

            $fileModel = $request->user()->files()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'expires_at' => $expiresAt,
            ]);

            Log::info('File uploaded successfully', [
                'file_id' => $fileModel->id,
                'unique_id' => $fileModel->unique_id,
                'expires_at' => $fileModel->expires_at
            ]);

            return back()->with('success', 'File uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Error uploading file', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->withErrors(['file' => 'An error occurred while uploading the file. Please try again.']);
        }
    }

    public function show(Request $request, File $file)
    {
        try {
            if (! $request->hasValidSignature()) {
                Log::warning('Invalid signature for file access', ['file_id' => $file->id, 'ip' => $request->ip()]);
                abort(403);
            }

            if ($file->isExpired()) {
                Log::info('Attempted access to expired file', ['file_id' => $file->id]);
                abort(404, 'File has expired.');
            }

            Log::info('File download page accessed', ['file_id' => $file->id]);
            return view('files.download', compact('file'));
        } catch (\Exception $e) {
            Log::error('Error showing file page: ' . $e->getMessage());
            throw $e;
        }
    }

    public function download(File $file)
    {
        try {
            if ($file->isExpired()) {
                Log::warning('Attempt to download expired file', ['file_id' => $file->id]);
                abort(404, 'This file has expired.');
            }

            // Get storage disk from config (defaults to 'public' for local storage)
            $storageDisk = config('files.storage_disk', 'public');

            // Check if file exists in storage
            if (!Storage::disk($storageDisk)->exists($file->path)) {
                Log::error('File not found in storage', ['file_id' => $file->id, 'path' => $file->path, 'disk' => $storageDisk]);
                abort(404, 'File not found.');
            }

            $file->increment('download_count');
            Log::info('File downloaded', ['file_id' => $file->id, 'disk' => $storageDisk]);

            return Storage::disk($storageDisk)->download($file->path, $file->original_name);
        } catch (\Exception $e) {
            Log::error('Error downloading file: ' . $e->getMessage());
            abort(404);
        }
    }

    public function destroy(File $file)
    {
        try {
            // Ensure user can only delete their own files
            if ($file->user_id !== auth()->id()) {
                Log::warning('Unauthorized file deletion attempt', [
                    'file_id' => $file->id,
                    'user_id' => auth()->id(),
                    'file_owner_id' => $file->user_id,
                ]);
                abort(403, 'You do not have permission to delete this file.');
            }

            // Check if file exists (additional safety check)
            if (!$file->exists) {
                abort(404, 'File not found.');
            }

            // Get storage disk from config (defaults to 'public' for local storage)
            $storageDisk = config('files.storage_disk', 'public');

            // Delete file from storage
            if (Storage::disk($storageDisk)->exists($file->path)) {
                Storage::disk($storageDisk)->delete($file->path);
                Log::info('File deleted from storage', ['file_id' => $file->id, 'path' => $file->path, 'disk' => $storageDisk]);
            }

            // Delete database record
            $fileId = $file->id;
            $file->delete();

            Log::info('File deleted successfully', ['file_id' => $fileId]);

            return back()->with('success', 'File deleted successfully!');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            // Re-throw HTTP exceptions (like 403, 404) so they're handled properly
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error deleting file', [
                'message' => $e->getMessage(),
                'file_id' => $file->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'An error occurred while deleting the file. Please try again.']);
        }
    }
}
