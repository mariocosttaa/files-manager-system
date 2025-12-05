<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $file->original_name }} - Mini-Drive</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12 sm:py-16">
        <div class="w-full max-w-md">
            <!-- File Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- File Icon Section -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-8 py-16 text-center">
                    <div class="mx-auto h-28 w-28 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="h-14 w-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4 px-2 break-words leading-tight">{{ $file->original_name }}</h2>

                    <div class="flex items-center justify-center gap-3 text-sm text-gray-600">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-medium bg-white text-gray-700 border border-gray-200 uppercase tracking-wide text-xs">
                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}
                        </span>
                        <span class="text-gray-500">{{ number_format($file->size / 1024, 2) }} KB</span>
                    </div>
                </div>

                <!-- Action Section -->
                <div class="px-8 py-8 space-y-5">
                    <a href="{{ URL::signedRoute('files.download', $file) }}" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download File
                    </a>

                    @if($file->expires_at)
                        <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-600 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-semibold text-amber-900 mb-1">Expiration Notice</h3>
                                    <p class="text-sm text-amber-800">
                                        This link will expire {{ $file->expires_at->diffForHumans() }}.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl bg-green-50 border border-green-200 p-4">
                            <div class="flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-medium text-green-800">
                                    This file is available for unlimited downloads
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Shared via <span class="font-semibold text-blue-600">Mini-Drive</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
