<?php

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin dashboard is displayed', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('files can be uploaded', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('document.pdf', 100);

    $response = $this->post('/files', [
        'file' => $file,
    ]);

    $response->assertRedirect('/');
    
    // Assert file was stored...
    Storage::disk('public')->assertExists('uploads/' . $file->hashName());

    // Assert database record exists...
    $this->assertDatabaseHas('files', [
        'original_name' => 'document.pdf',
    ]);
});

test('files can be downloaded via signed url', function () {
    Storage::fake('public');
    
    $file = File::create([
        'original_name' => 'test.txt',
        'path' => 'uploads/test.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
    ]);

    Storage::disk('public')->put('uploads/test.txt', 'content');

    $url = URL::signedRoute('files.show', $file);

    $response = $this->get($url);
    $response->assertStatus(200);
    $response->assertSee('test.txt');
});

test('expired files cannot be downloaded', function () {
    $file = File::create([
        'original_name' => 'expired.txt',
        'path' => 'uploads/expired.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
        'expires_at' => now()->subHour(),
    ]);

    $url = URL::signedRoute('files.show', $file);

    $response = $this->get($url);
    $response->assertStatus(404);
});

test('file upload validation fails for invalid data', function () {
    $response = $this->post('/files', [
        'file' => 'not-a-file',
    ]);

    $response->assertSessionHasErrors('file');
});

test('file upload fails if expiration is in the past', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('doc.pdf', 100);

    $response = $this->post('/files', [
        'file' => $file,
        'expires_at' => now()->subHour()->toDateTimeString(),
    ]);

    $response->assertSessionHasErrors('expires_at');
});

test('file upload fails if file is too large', function () {
    Storage::fake('public');
    // Create a file larger than 10MB (10240KB)
    $file = UploadedFile::fake()->create('large.pdf', 10241);

    $response = $this->post('/files', [
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});
