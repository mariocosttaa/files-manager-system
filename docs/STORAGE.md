# Storage Configuration Guide

This application supports both **local storage** and **cloud storage** (S3, Google Cloud Storage, Azure) using Laravel's filesystem abstraction. The storage backend can be easily switched via configuration.

## Quick Start

### Local Storage (Default)

By default, files are stored locally in `storage/app/public/uploads`. **No additional configuration needed** - it works out of the box!

### Cloud Storage

To use cloud storage, simply set the `FILE_STORAGE_DISK` environment variable in your `.env` file:

```env
FILE_STORAGE_DISK=s3
```

The application will automatically use the configured disk for all file operations.

## Supported Storage Providers

### 1. AWS S3

**Configuration in `.env`:**
```env
FILE_STORAGE_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com
```

**Optional:**
```env
AWS_ENDPOINT=https://s3.amazonaws.com
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 2. Google Cloud Storage

**Install the package:**
```bash
composer require league/flysystem-google-cloud-storage
```

**Configuration in `.env`:**
```env
FILE_STORAGE_DISK=gcs
GOOGLE_CLOUD_PROJECT_ID=your-project-id
GOOGLE_CLOUD_STORAGE_BUCKET=your-bucket-name
GOOGLE_CLOUD_KEY_FILE=/path/to/service-account.json
```

**Optional:**
```env
GOOGLE_CLOUD_STORAGE_PATH_PREFIX=uploads
GOOGLE_CLOUD_STORAGE_API_URI=https://storage.googleapis.com
```

### 3. Azure Blob Storage

**Install the package:**
```bash
composer require league/flysystem-azure-blob-storage
```

**Configuration in `.env`:**
```env
FILE_STORAGE_DISK=azure
AZURE_STORAGE_NAME=your-storage-account-name
AZURE_STORAGE_KEY=your-storage-account-key
AZURE_STORAGE_CONTAINER=your-container-name
```

**Optional:**
```env
AZURE_STORAGE_URL=https://your-storage-account.blob.core.windows.net
AZURE_STORAGE_PREFIX=uploads
```

## How It Works

1. **Configuration**: The storage disk is configured in `config/files.php`:
   ```php
   'storage_disk' => env('FILE_STORAGE_DISK', 'public'),
   ```

2. **Automatic Fallback**: If `FILE_STORAGE_DISK` is not set, it defaults to `'public'` (local storage).

3. **Seamless Operation**: All file operations (upload, download, delete) work identically regardless of storage backend thanks to Laravel's filesystem abstraction.

4. **FileController**: The controller automatically uses the configured disk:
   ```php
   $storageDisk = config('files.storage_disk', 'public');
   Storage::disk($storageDisk)->store(...);
   ```

## Switching Between Storage Backends

You can switch storage backends at any time by changing the `FILE_STORAGE_DISK` environment variable:

```env
# Use local storage
FILE_STORAGE_DISK=public

# Use S3
FILE_STORAGE_DISK=s3

# Use Google Cloud Storage
FILE_STORAGE_DISK=gcs

# Use Azure
FILE_STORAGE_DISK=azure
```

**Note**: When switching storage backends, existing files in the previous storage will not be automatically migrated. You'll need to handle migration separately if needed.

## Testing

Tests automatically work with any storage backend. The test suite uses `Storage::fake()` which works with all configured disks.

## Custom Storage Disks

You can add custom storage disks in `config/filesystems.php` and use them by setting `FILE_STORAGE_DISK` to your custom disk name.

## Benefits

✅ **Flexibility**: Switch between local and cloud storage easily  
✅ **Scalability**: Use cloud storage for production, local for development  
✅ **Cost-Effective**: Start with local storage, migrate to cloud when needed  
✅ **Laravel Native**: Uses Laravel's built-in filesystem abstraction  
✅ **No Code Changes**: Switch storage backends via configuration only  

