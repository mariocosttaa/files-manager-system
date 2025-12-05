<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Mini-Drive') }} - Secure File Sharing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#10b981',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(2deg); }
        }

        .animate-float { animation: float 8s ease-in-out infinite; }

        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased" x-data="{
    mobileMenuOpen: false,
    loginOpen: {{ ($errors->has('email') || $errors->has('password')) && !$errors->has('name') ? 'true' : 'false' }},
    registerOpen: {{ $errors->has('name') || $errors->has('password_confirmation') ? 'true' : 'false' }},
    forgotPasswordOpen: false
}">

    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-lg border-b border-gray-100 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="relative">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 group-hover:shadow-xl group-hover:shadow-blue-300 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                </div>
                <span class="text-2xl font-bold text-blue-600">Mini-Drive</span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-8">
                <!-- GitHub Link -->
                <a href="https://github.com/mariocosttaa/files-manager-system" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-gray-900 transition-colors" title="View on GitHub - files-manager-system">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">GitHub</span>
                </a>
                
                <div class="flex items-center gap-3 ml-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @else
                        <button @click="loginOpen = true" class="px-5 py-2.5 text-gray-700 hover:text-blue-600 font-semibold rounded-xl transition-colors">
                            Log In
                        </button>
                        @if (Route::has('register'))
                            <button @click="registerOpen = true" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-200 hover:shadow-xl hover:-translate-y-0.5">
                                Get Started Free
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 hover:text-blue-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-100 bg-white">
            <div class="px-6 py-6 space-y-4">
                <!-- GitHub Link (Mobile) -->
                <a href="https://github.com/mariocosttaa/files-manager-system" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 px-4 py-3 text-gray-700 hover:text-gray-900 transition-colors border border-gray-200 rounded-xl">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">View on GitHub</span>
                </a>
                
                @auth
                    <a href="{{ url('/dashboard') }}" class="block w-full px-6 py-3 bg-gray-900 text-white text-center font-semibold rounded-xl transition-all shadow-lg">
                        Dashboard
                    </a>
                @else
                    <button @click="loginOpen = true; mobileMenuOpen = false" class="block w-full px-5 py-3 text-gray-700 text-center hover:text-blue-600 font-semibold rounded-xl transition-colors border border-gray-200">
                        Log In
                    </button>
                    @if (Route::has('register'))
                        <button @click="registerOpen = true; mobileMenuOpen = false" class="block w-full px-6 py-3 bg-blue-600 text-white text-center font-semibold rounded-xl transition-all shadow-lg">
                            Get Started Free
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center px-6 pt-20 overflow-hidden bg-gradient-to-br from-blue-50 via-white to-green-50">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center relative z-10 py-20">
            <!-- Left Content -->
            <div class="text-center lg:text-left">
                <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight text-gray-900">
                    Share Files
                    <span class="block gradient-text mt-2">Securely & Instantly</span>
                </h1>

                <p class="text-xl text-gray-600 mb-10 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Professional file sharing platform with enterprise-grade security. Upload, share, and track your files with ease.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-12">
                    @auth
                        <a href="{{ route('dashboard') }}" class="group px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-xl shadow-blue-200 hover:shadow-2xl hover:-translate-y-1">
                            <span class="flex items-center justify-center gap-2">
                                Go to Dashboard
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </a>
                    @else
                        <button @click="registerOpen = true" class="group px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-xl shadow-blue-200 hover:shadow-2xl hover:-translate-y-1">
                            <span class="flex items-center justify-center gap-2">
                                Get Started
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </button>
                    @endauth
                </div>
            </div>

            <!-- Right Content - File Management Showcase -->
            <div class="relative hidden lg:block">
                <div class="relative h-[650px] flex items-center">
                    <!-- Main Dashboard Preview -->
                    <div class="w-full bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden animate-float">
                        <div class="bg-blue-600 h-12 flex items-center px-6 gap-2">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-white/80"></div>
                                <div class="w-3 h-3 rounded-full bg-white/80"></div>
                                <div class="w-3 h-3 rounded-full bg-white/80"></div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- File Item 1 -->
                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-white rounded-xl border border-blue-100 hover:shadow-lg transition-all cursor-pointer">
                                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 mb-1">Annual_Report_2024.pdf</h4>
                                    <p class="text-sm text-gray-500">2.4 MB • Shared 2 hours ago</p>
                                </div>
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm font-semibold">Active</span>
                                </div>
                            </div>

                            <!-- File Item 2 -->
                            <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-lg transition-all cursor-pointer">
                                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 mb-1">Team_Meeting_Photo.jpg</h4>
                                    <p class="text-sm text-gray-500">5.8 MB • 247 downloads</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">247</div>
                                    <div class="text-xs text-gray-500">views</div>
                                </div>
                            </div>

                            <!-- File Item 3 -->
                            <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-lg transition-all cursor-pointer">
                                <div class="w-14 h-14 bg-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 mb-1">Q4_Financial_Data.xlsx</h4>
                                    <p class="text-sm text-gray-500">1.2 MB • Expires in 5 days</p>
                                </div>
                                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    Share
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Background Decorative Elements -->
                    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-30 -z-10"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-green-100 rounded-full blur-3xl opacity-30 -z-10"></div>
                </div>
            </div>
        </div>

        <!-- Decorative Background -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s"></div>
            <div class="absolute bottom-20 left-1/3 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 4s"></div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold text-gray-900">Mini-Drive</span>
            </div>
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Mini-Drive. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Login Modal -->
    <div x-show="loginOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="loginOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="loginOpen = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="loginOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-6" id="modal-title">
                                Welcome Back
                            </h3>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <!-- Email Address -->
                                <div>
                                    <label for="email" class="block font-medium text-sm text-gray-700 mb-1">Email</label>
                                    <input id="email" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 bg-gray-50 text-gray-900 placeholder-gray-400 transition-all" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Password -->
                                <div class="mt-4">
                                    <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password</label>
                                    <input id="password" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 bg-gray-50 text-gray-900 placeholder-gray-400 transition-all" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="block mt-4">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                                    </label>
                                </div>

                                <div class="flex items-center justify-end mt-6 gap-4">
                                    <button type="button" @click="loginOpen = false; forgotPasswordOpen = true" class="underline text-sm text-gray-600 hover:text-gray-900">
                                        Forgot password?
                                    </button>

                                    <button class="px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                                        Log in
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div x-show="registerOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="registerOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="registerOpen = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="registerOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-6" id="modal-title">
                                Create Account
                            </h3>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block font-medium text-sm text-gray-700 mb-1">Name</label>
                                    <input id="name" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 bg-gray-50 text-gray-900 placeholder-gray-400 transition-all" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Email Address -->
                                <div class="mt-4">
                                    <label for="email" class="block font-medium text-sm text-gray-700 mb-1">Email</label>
                                    <input id="email" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 bg-gray-50 text-gray-900 placeholder-gray-400 transition-all" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Password -->
                                <div class="mt-4">
                                    <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password</label>
                                    <input id="password" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 bg-gray-50 text-gray-900 placeholder-gray-400 transition-all" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mt-4">
                                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-1">Confirm Password</label>
                                    <input id="password_confirmation" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 bg-gray-50 text-gray-900 placeholder-gray-400 transition-all" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                                </div>

                                <div class="flex items-center justify-end mt-6 gap-4">
                                    <button type="button" @click="registerOpen = false; loginOpen = true" class="underline text-sm text-gray-600 hover:text-gray-900">
                                        Already registered?
                                    </button>

                                    <button class="px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div x-show="forgotPasswordOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="forgotPasswordOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="forgotPasswordOpen = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="forgotPasswordOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                Reset Password
                            </h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Forgot your password? No problem. Just enter your email address and we'll email you a password reset link.
                            </p>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <!-- Email Address -->
                                <div>
                                    <label for="forgot-email" class="block font-medium text-sm text-gray-700 mb-1">Email</label>
                                    <input id="forgot-email" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 bg-gray-50 text-gray-900 placeholder-gray-400 transition-all" type="email" name="email" required autofocus placeholder="you@example.com" />
                                </div>

                                <div class="flex items-center justify-end mt-6 gap-4">
                                    <button type="button" @click="forgotPasswordOpen = false; loginOpen = true" class="underline text-sm text-gray-600 hover:text-gray-900">
                                        Back to login
                                    </button>

                                    <button class="px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                                        Send Reset Link
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
