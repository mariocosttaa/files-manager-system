<x-app-layout title="My Files">
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    My Files
                </h2>
                <p class="text-base text-gray-600">
                    Manage your documents and share them securely.
                </p>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="bg-blue-50 px-6 py-4 rounded-lg border border-blue-200 flex items-center gap-4 min-w-[160px]">
                    <div class="p-3 bg-blue-600 rounded-lg">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Total Files</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $files->count() }}</p>
                    </div>
                </div>
                <div class="bg-blue-50 px-6 py-4 rounded-lg border border-blue-200 flex items-center gap-4 min-w-[160px]">
                    <div class="p-3 bg-blue-600 rounded-lg">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Downloads</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $files->sum('download_count') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Upload Section -->
            <div class="bg-white rounded-lg border border-gray-200 mb-6 overflow-hidden">
                <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm" class="relative">
                    @csrf
                    <input type="file" name="file" id="fileInput" class="hidden" onchange="handleFileSelect(this)" required>

                    <!-- Drop Zone -->
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-16 text-center hover:border-blue-400 hover:bg-blue-50 transition-all cursor-pointer"
                         onclick="document.getElementById('fileInput').click()"
                         ondrop="handleDrop(event)"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)">
                        <div class="space-y-4">
                            <div class="mx-auto h-16 w-16 text-gray-400">
                                <svg stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-base text-gray-700">
                                    <span class="font-medium text-blue-600">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    @php
                                        $maxSize = config('files.max_file_size', 2048);
                                        $maxSizeMB = round($maxSize / 1024, 1);
                                        $phpLimit = ini_get('upload_max_filesize');
                                    @endphp
                                    Max file size: {{ $maxSizeMB }}MB (PHP limit: {{ $phpLimit }})
                                </p>
                            </div>
                            <p id="fileName" class="text-sm font-medium text-blue-600 hidden bg-blue-50 inline-block px-4 py-2 rounded-lg"></p>
                        </div>
                    </div>

                    <!-- Optional Expiration (Hidden by default) -->
                    <div id="expirationSection" class="hidden px-6 pb-6 border-t border-gray-200 pt-4">
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expiration (Optional)</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
                    </div>

                    <!-- Upload Button (Shown when file selected) -->
                    <div id="uploadButton" class="hidden px-6 pb-6 border-t border-gray-200 pt-4">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload File
                        </button>
                    </div>
                </form>
            </div>

            <!-- Files List -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Uploads</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $files->count() }} files
                    </span>
                </div>

                @if($files->isNotEmpty())
                    <div class="divide-y divide-gray-200">
                        @foreach ($files as $file)
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 min-w-0 flex-1">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $file->original_name }}</p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                    {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ number_format($file->size / 1024, 2) }} KB</span>
                                                <span class="text-gray-300">•</span>
                                                <span class="text-xs text-gray-500">{{ $file->download_count }} downloads</span>
                                                <span class="text-gray-300">•</span>
                                                <span class="text-xs text-gray-500">{{ $file->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <div class="text-right hidden sm:block">
                                            <p class="text-xs {{ $file->isExpired() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                {{ $file->expires_at ? ($file->isExpired() ? 'Expired' : 'Expires ' . $file->expires_at->diffForHumans()) : 'Never expires' }}
                                            </p>
                                        </div>
                                        <button onclick="copyLink('{{ URL::signedRoute('files.show', $file) }}', this)" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                            <svg class="mr-1.5 h-3.5 w-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            Copy Link
                                        </button>
                                        <a href="{{ URL::signedRoute('files.download', $file) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                            Download
                                        </a>
                                        <form action="{{ route('files.destroy', $file) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                                <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No files</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new upload.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        const maxSizeKB = {{ config('files.max_file_size', 2048) }};
        const maxSizeBytes = maxSizeKB * 1024;

        function handleFileSelect(input) {
            const file = input.files[0];
            if (!file) return;

            const display = document.getElementById('fileName');
            const uploadButton = document.getElementById('uploadButton');
            const expirationSection = document.getElementById('expirationSection');
            const dropZone = document.getElementById('dropZone');

            if (file.size > maxSizeBytes) {
                display.textContent = `Error: ${file.name} is too large (Max ${Math.round(maxSizeKB / 1024)}MB)`;
                display.classList.remove('hidden', 'text-blue-600', 'bg-blue-50');
                display.classList.add('text-red-600', 'bg-red-50');
                uploadButton.classList.add('hidden');
                expirationSection.classList.add('hidden');
            } else {
                display.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
                display.classList.remove('hidden', 'text-red-600', 'bg-red-50');
                display.classList.add('text-blue-600', 'bg-blue-50');
                uploadButton.classList.remove('hidden');
                expirationSection.classList.remove('hidden');
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            }
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropZone = document.getElementById('dropZone');
            dropZone.classList.remove('border-blue-500', 'bg-blue-100');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const fileInput = document.getElementById('fileInput');
                fileInput.files = files;
                handleFileSelect(fileInput);
            }
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropZone = document.getElementById('dropZone');
            dropZone.classList.add('border-blue-500', 'bg-blue-100');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            const dropZone = document.getElementById('dropZone');
            dropZone.classList.remove('border-blue-500', 'bg-blue-100');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('uploadForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const fileInput = document.getElementById('fileInput');
                    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                        e.preventDefault();
                        alert('Please select a file to upload.');
                        return false;
                    }
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<svg class="animate-spin mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Uploading...';
                    }
                });
            }
        });

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
</x-app-layout>
