@extends('layouts.app')

@section('content')
    <div class="min-h-[60vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-xl sm:px-10 border border-gray-100">
                <div class="text-center mb-8">
                    <div class="mx-auto h-20 w-20 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 transform transition hover:scale-105 duration-300">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">{{ $file->original_name }}</h2>
                    
                    <div class="flex items-center justify-center gap-3 text-sm text-gray-500 mt-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md font-medium bg-gray-100 text-gray-800 uppercase tracking-wide text-xs">
                            {{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}
                        </span>
                        <span>{{ number_format($file->size / 1024, 2) }} KB</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <a href="{{ route('files.download', $file) }}" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download File
                    </a>

                    @if($file->expires_at)
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Expiration Notice</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>This link will expire {{ $file->expires_at->diffForHumans() }}.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-center text-xs text-gray-400">
                            This file is available for unlimited downloads.
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Shared via <span class="font-semibold text-indigo-600">Mini-Drive</span>
                </p>
            </div>
        </div>
    </div>
@endsection
