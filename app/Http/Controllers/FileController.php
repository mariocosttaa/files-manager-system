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
    public function index()
    {
        try {
            $files = File::latest()->get();
            return view('files.index', compact('files'));
        } catch (\Exception $e) {
            Log::error('Error fetching files: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Unable to load files.']);
        }
    }

    public function store(StoreFileRequest $request)
    {
        try {
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('uploads', 'public');

            $file = File::create([
                'original_name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'expires_at' => $request->expires_at,
            ]);

            Log::info('File uploaded successfully', ['file_id' => $file->id, 'name' => $file->original_name]);

            return redirect()->route('files.index')->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage());
            return back()->withErrors(['file' => 'File upload failed. Please try again.']);
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
                Log::info('Attempted download of expired file', ['file_id' => $file->id]);
                abort(404, 'File has expired.');
            }

            $file->increment('download_count');
            Log::info('File downloaded', ['file_id' => $file->id]);

            return Storage::disk('public')->download($file->path, $file->original_name);
        } catch (\Exception $e) {
            Log::error('Error downloading file: ' . $e->getMessage());
            return back()->withErrors(['error' => 'File download failed.']);
        }
    }
}
