<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

Schedule::call(function () {
    $expiredFiles = File::where('expires_at', '<', now())->get();
    
    foreach ($expiredFiles as $file) {
        Storage::disk('public')->delete($file->path);
        $file->delete();
    }
})->hourly();
