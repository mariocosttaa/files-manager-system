@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold leading-7 text-gray-900 tracking-tight sm:truncate">
                    My Files
                </h2>
                <p class="mt-2 text-base text-gray-500">
                    Manage your documents and share them securely.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 gap-4">
                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Files</p>
                        <p class="text-lg font-bold text-gray-900">{{ $files->count() }}</p>
                    </div>
                </div>
                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Downloads</p>
                        <p class="text-lg font-bold text-gray-900">{{ $files->sum('download_count') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 transition hover:shadow-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Upload New File</h3>
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-indigo-50/50 hover:border-indigo-400 transition-all duration-200 cursor-pointer group" onclick="document.getElementById('fileInput').click()">
                    <input type="file" name="file" id="fileInput" class="hidden" onchange="updateFileName(this)">
                    
                    <div class="space-y-2">
                        <div class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition duration-200">
                            <svg stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium text-indigo-600 hover:text-indigo-500">Click to upload</span> or drag and drop
                        </div>
                        <p class="text-xs text-gray-500">Any file up to 10MB</p>
                    </div>
                    
                    <p id="fileName" class="mt-4 text-sm font-medium text-indigo-600 hidden bg-indigo-50 inline-block px-3 py-1 rounded-full"></p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expiration (Optional)</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2.5">
                    </div>
                    <div class="flex-shrink-0 mt-6">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Upload File
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Files List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Recent Uploads</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ $files->count() }} files
                </span>
            </div>
            
            <ul role="list" class="divide-y divide-gray-100">
                @foreach ($files as $file)
                    <li class="group hover:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center min-w-0 gap-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100 transition duration-150">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-indigo-600 transition">{{ $file->original_name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ number_format($file->size / 1024, 2) }} KB</span>
                                        <span class="text-gray-300">•</span>
                                        <span class="text-xs text-gray-500">{{ $file->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-6">
                                <div class="text-right hidden sm:block">
                                    <div class="flex items-center justify-end gap-1 text-sm text-gray-500 mb-1">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        <span>{{ $file->download_count }}</span>
                                    </div>
                                    <p class="text-xs {{ $file->isExpired() ? 'text-red-500 font-medium' : 'text-green-600' }}">
                                        {{ $file->expires_at ? ($file->isExpired() ? 'Expired' : 'Expires ' . $file->expires_at->diffForHumans()) : 'Never expires' }}
                                    </p>
                                </div>
                                
                                <button onclick="copyLink('{{ URL::signedRoute('files.show', $file) }}', this)" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    Copy Link
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
                
                @if($files->isEmpty())
                    <li class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No files</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new upload.</p>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const file = input.files[0];
            const display = document.getElementById('fileName');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            if (file) {
                if (file.size > 10 * 1024 * 1024) { // 10MB
                    display.textContent = `Error: ${file.name} is too large (Max 10MB)`;
                    display.classList.remove('hidden', 'text-indigo-600', 'bg-indigo-50');
                    display.classList.add('text-red-600', 'bg-red-50');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    display.textContent = file.name;
                    display.classList.remove('hidden', 'text-red-600', 'bg-red-50');
                    display.classList.add('text-indigo-600', 'bg-indigo-50');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                display.classList.add('hidden');
            }
        }

        function copyLink(url, btn) {
            navigator.clipboard.writeText(url).then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = `<svg class="-ml-0.5 mr-2 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copied!`;
                btn.classList.add('text-green-700', 'border-green-300', 'bg-green-50');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('text-green-700', 'border-green-300', 'bg-green-50');
                }, 2000);
            });
        }
    </script>
@endsection
