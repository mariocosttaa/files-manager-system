<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the maximum file size allowed for uploads in kilobytes.
    | This should not exceed PHP's upload_max_filesize and post_max_size settings.
    |
    */

    'max_file_size' => env('MAX_FILE_SIZE', 2048), // Default to 2MB (2048 KB) to match common PHP limits

    /*
    |--------------------------------------------------------------------------
    | Allowed File Types
    |--------------------------------------------------------------------------
    |
    | Specify the allowed MIME types for file uploads. This helps prevent
    | dangerous file types from being uploaded.
    |
    */

    'allowed_mime_types' => [
        // Documents
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
        'text/plain',
        'text/csv',
        // Images
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        // Archives
        'application/zip',
        'application/x-rar-compressed',
        'application/x-tar',
        'application/gzip',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed File Extensions (as fallback)
    |--------------------------------------------------------------------------
    |
    | File extensions that are allowed. Used as a secondary validation.
    |
    */

    'allowed_extensions' => [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'txt', 'csv', 'rtf',
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
        'zip', 'rar', 'tar', 'gz',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Disk Configuration
    |--------------------------------------------------------------------------
    |
    | Specify which filesystem disk to use for file storage.
    | Options: 'public' (local), 's3' (AWS S3), 'gcs' (Google Cloud Storage), etc.
    | Defaults to 'public' (local storage) if not specified.
    |
    | Make sure the disk is configured in config/filesystems.php
    |
    */

    'storage_disk' => env('FILE_STORAGE_DISK', 'public'),
];

