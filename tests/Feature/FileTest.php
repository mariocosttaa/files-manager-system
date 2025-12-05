<?php

use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Helper to get the storage disk from config (defaults to 'public' for tests)
function getStorageDisk(): string
{
    return config('files.storage_disk', 'public');
}

test('admin dashboard is displayed', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('files can be uploaded', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);

    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 1024);

    $response = $this->actingAs($user)->post('/files', [
        'file' => $file,
    ]);

    $response->assertRedirect(); // Back to dashboard
    $response->assertSessionHas('success');

    // Assert file was stored...
    Storage::disk($disk)->assertExists('uploads/' . $file->hashName());

    // Assert file record was created...
    $this->assertDatabaseHas('files', [
        'original_name' => 'document.pdf',
        'user_id' => $user->id,
    ]);

    // Assert unique_id was generated
    $uploadedFile = File::where('original_name', 'document.pdf')->first();
    expect($uploadedFile)->not->toBeNull();
    expect($uploadedFile->unique_id)->not->toBeNull();
    expect($uploadedFile->unique_id)->toBeString();
});

test('files can be downloaded via signed link', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);
    $user = User::factory()->create();
    $file = File::create([
        'unique_id' => \Illuminate\Support\Str::uuid()->toString(),
        'original_name' => 'test.txt',
        'path' => 'uploads/test.txt',
        'size' => 1024,
        'mime_type' => 'text/plain',
        'user_id' => $user->id,
    ]);

    Storage::disk($disk)->put('uploads/test.txt', 'content');

    $url = URL::signedRoute('files.show', $file);

    $response = $this->get($url);
    $response->assertStatus(200);
    $response->assertSee('test.txt');
});

test('expired files cannot be downloaded', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $file = File::create([
        'unique_id' => \Illuminate\Support\Str::uuid()->toString(),
        'original_name' => 'test.txt',
        'path' => 'uploads/test.txt',
        'size' => 1024,
        'mime_type' => 'text/plain',
        'expires_at' => now()->subDay(),
        'user_id' => $user->id,
    ]);

    $url = URL::signedRoute('files.show', $file);

    $response = $this->get($url);
    $response->assertStatus(404);
});

test('file upload validation fails for invalid data', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/files', [
        'file' => 'not-a-file',
    ]);

    $response->assertSessionHasErrors('file');
});

test('file upload fails if expiration is in the past', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 1024);

    $response = $this->actingAs($user)->post('/files', [
        'file' => $file,
        'expires_at' => now()->subHour()->toDateTimeString(),
    ]);

    $response->assertSessionHasErrors('expires_at');
});

test('file upload fails if file is too large', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('large.pdf', 11000); // 11MB

    $response = $this->actingAs($user)->post('/files', [
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});

test('file upload fails for disallowed file types', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);
    $user = User::factory()->create();
    // Create a PHP file which should be disallowed
    $file = UploadedFile::fake()->create('script.php', 1024, 'application/x-php');

    $response = $this->actingAs($user)->post('/files', [
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});

test('users can delete their own files', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);
    $user = User::factory()->create();
    $file = File::create([
        'unique_id' => \Illuminate\Support\Str::uuid()->toString(),
        'original_name' => 'test.txt',
        'path' => 'uploads/test.txt',
        'size' => 1024,
        'mime_type' => 'text/plain',
        'user_id' => $user->id,
    ]);

    Storage::disk($disk)->put('uploads/test.txt', 'content');

    $response = $this->actingAs($user)->delete(route('files.destroy', $file));

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('files', ['id' => $file->id]);
    Storage::disk($disk)->assertMissing('uploads/test.txt');
});

test('users cannot delete other users files', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $file = File::create([
        'unique_id' => \Illuminate\Support\Str::uuid()->toString(),
        'original_name' => 'test.txt',
        'path' => 'uploads/test.txt',
        'size' => 1024,
        'mime_type' => 'text/plain',
        'user_id' => $user1->id,
    ]);

    Storage::disk($disk)->put('uploads/test.txt', 'content');

    $response = $this->actingAs($user2)->delete(route('files.destroy', $file));

    $response->assertStatus(403);
    $this->assertDatabaseHas('files', ['id' => $file->id]);
    Storage::disk($disk)->assertExists('uploads/test.txt');
});
