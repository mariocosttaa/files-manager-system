<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-blue-600 rounded-xl shadow-sm mb-6">
                <div class="p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Welcome back, {{ Auth::user()->name }}</h3>
                    <p class="text-blue-100 text-sm mb-4">Manage your documents and share them securely.</p>
                    <a href="{{ route('files.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Upload New File
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Total Files -->
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Files</p>
                    <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->files()->count() }}</p>
                </div>

                <!-- Downloads -->
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Downloads</p>
                    <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->files()->sum('download_count') }}</p>
                </div>

                <!-- Storage Used -->
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Storage Used</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format(Auth::user()->files()->sum('size') / 1024 / 1024, 2) }} MB
                    </p>
                </div>
            </div>

            <!-- Recent Files -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Files</h3>
                    <a href="{{ route('files.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        View all →
                    </a>
                </div>
                <div>
                    @php
                        $recentFiles = Auth::user()->files()->latest()->take(5)->get();
                    @endphp

                    @if($recentFiles->isNotEmpty())
                        <div class="divide-y divide-gray-200">
                            @foreach ($recentFiles as $file)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $file->original_name }}</p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ number_format($file->size / 1024, 2) }} KB •
                                                    {{ $file->download_count }} downloads •
                                                    {{ $file->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex items-center gap-2">
                                            <button onclick="copyLink('{{ URL::signedRoute('files.show', $file) }}', this)" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                                <svg class="mr-1.5 h-3.5 w-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                Copy Link
                                            </button>
                                            <a href="{{ URL::signedRoute('files.download', $file) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                                Download
                                            </a>
                                            <form action="{{ route('files.destroy', $file) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-3 text-sm font-medium text-gray-900">No files yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by uploading your first file.</p>
                            <a href="{{ route('files.index') }}" class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                Upload File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink(url, btn) {
            navigator.clipboard.writeText(url).then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = `<svg class="mr-1.5 h-3.5 w-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copied!`;
                btn.classList.add('text-green-700', 'border-green-300', 'bg-green-50');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('text-green-700', 'border-green-300', 'bg-green-50');
                }, 2000);
            });
        }
    </script>
</x-app-layout>
