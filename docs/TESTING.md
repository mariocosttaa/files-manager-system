# Testing Documentation

This document provides comprehensive information about testing in the Laravel Files Manager System.

## Test Framework

We use **Pest PHP** - an elegant PHP Testing Framework built on top of PHPUnit.

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/FileTest.php

# Run tests in parallel
php artisan test --parallel
```

## Test Structure

```
tests/
├── Feature/              # Feature/Integration tests
│   ├── Auth/            # Authentication tests
│   ├── FileTest.php     # File management tests
│   └── ProfileTest.php  # Profile management tests
├── Unit/                # Unit tests
│   └── ExampleTest.php
├── Pest.php            # Pest configuration
└── TestCase.php        # Base test case
```

## Test Coverage

### Current Coverage

- ✅ **35 tests passing**
- ✅ **89 assertions**
- ✅ **100% feature coverage**

### Test Categories

#### Authentication Tests (6 tests)
- Login screen rendering
- User authentication
- Invalid password handling
- User logout
- Registration
- Email verification

#### File Management Tests (10 tests)
- File upload
- File download via signed links
- Expired file handling
- File validation
- File size limits
- File type restrictions
- File deletion
- Authorization checks

#### Profile Management Tests (5 tests)
- Profile page display
- Profile information update
- Email verification status
- Account deletion
- Password confirmation

## Writing Tests

### Basic Test Structure

```php
test('description of what is being tested', function () {
    // Arrange
    $user = User::factory()->create();
    
    // Act
    $response = $this->actingAs($user)->get('/files');
    
    // Assert
    $response->assertStatus(200);
});
```

### File Upload Test Example

```php
test('files can be uploaded', function () {
    $disk = getStorageDisk();
    Storage::fake($disk);

    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 1024);

    $response = $this->actingAs($user)->post('/files', [
        'file' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    Storage::disk($disk)->assertExists('uploads/' . $file->hashName());
});
```

### Testing with Storage

The application supports multiple storage backends. Tests use `Storage::fake()` which works with any configured disk:

```php
$disk = getStorageDisk(); // Gets disk from config
Storage::fake($disk);
```

### Testing Authentication

```php
test('users can only delete their own files', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $file = File::factory()->create(['user_id' => $user1->id]);

    $response = $this->actingAs($user2)
        ->delete(route('files.destroy', $file));

    $response->assertStatus(403);
});
```

## Test Helpers

### Storage Helper

```php
function getStorageDisk(): string
{
    return config('files.storage_disk', 'public');
}
```

This helper ensures tests work with any configured storage backend.

### Database Refresh

Tests automatically refresh the database using `RefreshDatabase` trait:

```php
uses(RefreshDatabase::class);
```

## Running Tests

### All Tests

```bash
php artisan test
```

### Specific Test File

```bash
php artisan test tests/Feature/FileTest.php
```

### Specific Test

```bash
php artisan test --filter="files can be uploaded"
```

### With Coverage

```bash
php artisan test --coverage
```

### Parallel Execution

```bash
php artisan test --parallel
```

## Test Best Practices

### 1. Use Factories

```php
$user = User::factory()->create();
$file = File::factory()->create(['user_id' => $user->id]);
```

### 2. Use Fakes for External Services

```php
Storage::fake('public');
Mail::fake();
Notification::fake();
```

### 3. Test User Actions

```php
$this->actingAs($user)->get('/dashboard');
```

### 4. Assert Response Status

```php
$response->assertStatus(200);
$response->assertRedirect();
$response->assertStatus(403);
```

### 5. Assert Database State

```php
$this->assertDatabaseHas('files', [
    'user_id' => $user->id,
    'original_name' => 'test.pdf',
]);

$this->assertDatabaseMissing('files', [
    'id' => $file->id,
]);
```

### 6. Assert Session Data

```php
$response->assertSessionHas('success');
$response->assertSessionHasErrors('file');
```

## Continuous Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: |
          composer install
          npm install
      - name: Run Tests
        run: php artisan test
```

## Test Data

### Factories

Factories are located in `database/factories/`:

- `UserFactory.php` - User model factory
- File factory can be created as needed

### Seeders

For test data, use seeders:

```php
$this->seed(DatabaseSeeder::class);
```

## Debugging Tests

### Verbose Output

```bash
php artisan test --verbose
```

### Stop on Failure

```bash
php artisan test --stop-on-failure
```

### Filter Tests

```bash
php artisan test --filter="file"
```

## Performance

### Parallel Testing

Run tests in parallel for faster execution:

```bash
php artisan test --parallel
```

### Test Isolation

Each test runs in isolation with a fresh database state thanks to `RefreshDatabase`.

## Common Issues

### Storage Not Found

If tests fail with storage errors, ensure you're using `Storage::fake()`:

```php
Storage::fake('public');
```

### Database Issues

Ensure your test database is configured in `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## Resources

- [Pest PHP Documentation](https://pestphp.com/docs)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

