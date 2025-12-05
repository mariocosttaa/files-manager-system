# API Documentation

This document describes the API endpoints available in the Laravel Files Manager System.

## Base URL

All endpoints are relative to your application URL:
```
http://localhost:8000
```

## Authentication

Most endpoints require authentication. Users must be logged in to access protected routes.

### Authentication Methods

- **Session-based authentication** (default)
- **CSRF token** required for state-changing operations

## Endpoints

### Authentication Routes

#### Register
```
POST /register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

#### Login
```
POST /login
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password"
}
```

#### Logout
```
POST /logout
```

**Authentication:** Required

---

### File Management Routes

#### Upload File
```
POST /files
```

**Authentication:** Required

**Request:**
- Content-Type: `multipart/form-data`
- `file`: File to upload (required)
- `expires_at`: Optional expiration date (datetime-local format)

**Response:**
- Redirects back with success message
- Session flash: `success` or `errors`

**Validation:**
- File size: Max 2MB (configurable)
- File types: See `config/files.php`
- Rate limit: 10 uploads per minute

#### List Files
```
GET /files
```

**Authentication:** Required

**Response:**
- Returns view with user's files
- Files ordered by latest first

#### View File (Signed URL)
```
GET /files/{file}
```

**Authentication:** Not required (signed URL)

**Parameters:**
- `file`: File unique_id (route model binding)

**Response:**
- Returns file download page
- Requires valid signed URL signature

#### Download File (Signed URL)
```
GET /download/{file}
```

**Authentication:** Not required (signed URL)

**Parameters:**
- `file`: File unique_id (route model binding)

**Response:**
- Downloads the file
- Increments download count
- Requires valid signed URL signature

#### Delete File
```
DELETE /files/{file}
```

**Authentication:** Required

**Parameters:**
- `file`: File unique_id (route model binding)

**Authorization:**
- User can only delete their own files

**Response:**
- Redirects back with success message
- Deletes file from storage and database

---

### Profile Routes

#### View Profile
```
GET /profile
```

**Authentication:** Required

**Response:**
- Returns profile edit page

#### Update Profile
```
PATCH /profile
```

**Authentication:** Required

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com"
}
```

**Response:**
- Redirects to profile page
- Session flash: `status`

#### Delete Account
```
DELETE /profile
```

**Authentication:** Required

**Request Body:**
```json
{
  "password": "current-password"
}
```

**Response:**
- Logs out user
- Deletes user account
- Redirects to home page

---

### Dashboard Route

#### Dashboard
```
GET /dashboard
```

**Authentication:** Required

**Response:**
- Returns dashboard with statistics
- Shows recent files
- Displays total files, downloads, storage used

---

## Response Formats

### Success Response

**Redirect with Flash:**
```php
return back()->with('success', 'File uploaded successfully!');
```

**Session Flash:**
- `success`: Success message
- `status`: Status message (for profile updates)

### Error Response

**Validation Errors:**
```php
return back()->withErrors(['file' => 'The file is required.']);
```

**HTTP Errors:**
- `403`: Forbidden (unauthorized access)
- `404`: Not Found (file not found or expired)
- `422`: Unprocessable Entity (validation failed)
- `429`: Too Many Requests (rate limit exceeded)
- `500`: Internal Server Error

---

## Signed URLs

Files are accessed via signed URLs for security. Generate signed URLs:

```php
use Illuminate\Support\Facades\URL;

$url = URL::signedRoute('files.show', $file);
$downloadUrl = URL::signedRoute('files.download', $file);
```

**Signed URL Format:**
```
/files/{unique_id}?signature={signature}&expires={timestamp}
```

**Validation:**
- Signature must be valid
- URL must not be expired (if expiration set)
- File must not be expired

---

## Rate Limiting

### File Upload
- **Limit:** 10 requests per minute
- **Scope:** Per authenticated user
- **Response:** 429 Too Many Requests if exceeded

---

## File Validation

### Size Limits
- **Default:** 2MB (2048 KB)
- **Configurable:** `MAX_FILE_SIZE` in `.env`

### Allowed File Types

**Documents:**
- PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV

**Images:**
- JPG, JPEG, PNG, GIF, WEBP, SVG

**Archives:**
- ZIP, RAR, TAR, GZ

Configure in `config/files.php`.

---

## Storage

### Storage Disks

Files can be stored on different backends:

- `public`: Local storage (default)
- `s3`: AWS S3
- `gcs`: Google Cloud Storage
- `azure`: Azure Blob Storage

Configure via `FILE_STORAGE_DISK` in `.env`.

---

## Examples

### Upload File (cURL)

```bash
curl -X POST http://localhost:8000/files \
  -H "Cookie: laravel_session=..." \
  -F "file=@document.pdf" \
  -F "expires_at=2025-12-31T23:59"
```

### Generate Signed URL

```php
$file = File::find(1);
$url = URL::signedRoute('files.show', $file);
// Returns: /files/{unique_id}?signature=...&expires=...
```

### Download File

```bash
curl -X GET "http://localhost:8000/download/{unique_id}?signature=...&expires=..."
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 302 | Redirect |
| 403 | Forbidden (unauthorized) |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Server Error |

---

## Security Considerations

1. **Signed URLs**: All file access uses signed URLs
2. **CSRF Protection**: All state-changing operations require CSRF token
3. **Authentication**: Protected routes require login
4. **Authorization**: Users can only access their own files
5. **File Validation**: Type and size restrictions
6. **Rate Limiting**: Prevents abuse

---

## Testing

All endpoints are covered by comprehensive tests. See [TESTING.md](TESTING.md) for details.

---

## Support

For issues or questions, please open an issue on the repository.

