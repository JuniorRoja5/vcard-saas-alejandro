@extends('user.layouts.index', ['header' => true, 'nav' => true])

@section('title')
    {{ __('Mi Tienda') }}
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Mobile -->
    <div class="lg:hidden px-4 py-4 bg-white border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Mi Tienda</h1>
                <p class="text-sm text-gray-600">Gestiona tu perfil público y contenido</p>
            </div>
            <a href="{{ route('user.cards.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Layout Desktop: Two Panels -->
    <div class="hidden lg:flex min-h-screen">
        <!-- Left Panel: Edición -->
        <div class="w-1/2 bg-white overflow-y-auto">
            <div class="p-8">
                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Mi Tienda</h1>
                        <p class="text-gray-600">Gestiona tu perfil público y contenido</p>
                    </div>
                    <a href="{{ route('user.cards.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                </div>

                <!-- Profile Section -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl" id="profileInitial">T</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900" id="profileName">Trading Sharks</h3>
                            <p class="text-gray-600" id="profileUsername">@tradingsharks</p>
                        </div>
                        <button onclick="editProfile()" class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                    </div>
                    
                    <p class="text-gray-700 mb-4" id="profileBio">Aquí le cambia la vida 🔥📈</p>
                    
                    <!-- Social Links -->
                    <div class="flex items-center gap-4" id="socialLinks">
                        <span class="text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </span>
                        <span class="text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </span>
                        <span class="text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.343-.09.377-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 23.998 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </span>
                        <span class="text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </span>
                        <span class="text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </span>
                        <span class="text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <span class="text-gray-400">✖</span>
                    </div>
                </div>

                <!-- Products/Links Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Mis Enlaces / Productos</h2>
                        <button onclick="createNew()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear Nuevo
                        </button>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-4" id="itemsList">
                        <!-- Link Item -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Link</span>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="text-xs text-green-600 font-medium">activo</span>
                                        </div>
                                    </div>
                                    <h3 class="font-medium text-gray-900">🔥NAGA - Acción GRATUITA de hasta $100 USD</h3>
                                    <p class="text-sm text-gray-600">📊 https://naga.com/TradingSharks</p>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Product Item -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-medium text-white bg-purple-600 px-2 py-1 rounded">Producto</span>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="text-xs text-green-600 font-medium">activo</span>
                                        </div>
                                    </div>
                                    <h3 class="font-medium text-gray-900">Guía completa para invertir en video</h3>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-lg font-bold text-gray-900">$49.95</span>
                                        <span class="text-sm text-gray-500">• 0 ventas</span>
                                    </div>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Product Item 2 -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-medium text-white bg-orange-600 px-2 py-1 rounded">Producto</span>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="text-xs text-green-600 font-medium">activo</span>
                                        </div>
                                    </div>
                                    <h3 class="font-medium text-gray-900">Guía completa para invertir</h3>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-lg font-bold text-gray-900">$90</span>
                                        <span class="text-sm text-gray-500">• 0 ventas</span>
                                    </div>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Preview -->
        <div class="w-1/2 bg-gray-100 flex items-center justify-center p-8">
            <div class="flex flex-col items-center">
                <!-- iPhone Mockup -->
                <div class="relative bg-black rounded-[2.8rem] h-[680px] w-[340px] shadow-2xl p-[2px] mb-6">
                    <!-- Notch -->
                    <div class="absolute top-2 left-1/2 transform -translate-x-1/2 w-20 h-1 bg-gray-800 rounded-full z-10"></div>
                    
                    <!-- Screen -->
                    <div class="relative w-full h-full bg-white rounded-[2.6rem] overflow-hidden">
                        <iframe 
                            id="previewFrame" 
                            src="" 
                            class="w-full h-full border-none"
                            style="background: #1a1a1a;"
                        ></iframe>
                    </div>
                </div>
                
                <!-- URL Section -->
                <div class="text-center max-w-sm">
                    <p class="text-sm text-gray-600 mb-2">Tu enlace público:</p>
                    <div class="flex items-center gap-2 bg-white rounded-lg p-3 border">
                        <input
                            type="text"
                            value="https://clickmy.link/tradingsharks"
                            readonly
                            class="flex-1 text-sm text-gray-700 bg-transparent border-none outline-none"
                            id="publicUrl"
                        />
                        <button onclick="copyUrl()" class="text-blue-600 hover:text-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Layout: Single Column -->
    <div class="lg:hidden px-4 space-y-6 pb-6">
        <!-- Profile Section Mobile -->
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-xl">T</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-gray-900">Trading Sharks</h3>
                        <button onclick="editProfile()" class="text-gray-500 hover:bg-gray-100 p-1 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-600 text-sm">@tradingsharks</p>
                    <p class="text-gray-700 leading-relaxed mt-2 text-sm">Aquí le cambia la vida 🔥📈</p>
                </div>
            </div>
            
            <!-- Social Links Mobile -->
            <div class="flex items-center justify-start gap-3 flex-wrap mt-4">
                <span class="text-gray-400"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/></svg></span>
                <span class="text-gray-400"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></span>
                <span class="text-gray-400"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.343-.09.377-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 23.998 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/></svg></span>
                <span class="text-gray-400"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></span>
                <span class="text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></span>
                <span class="text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                <span class="text-gray-400">✖</span>
            </div>
        </div>

        <!-- Products Section Mobile -->
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Mis Enlaces / Productos</h2>
                <button onclick="createNew()" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>

            <!-- Items List Mobile -->
            <div class="space-y-3">
                <!-- Link Item Mobile -->
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Link</span>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-xs text-green-600 font-medium">activo</span>
                                </div>
                            </div>
                            <h3 class="font-medium text-gray-900 text-sm mb-1">🔥NAGA - Acción GRATUITA de hasta $100 USD</h3>
                            <p class="text-xs text-gray-600 truncate">📊 https://naga.com/TradingSharks</p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Product Items Mobile -->
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-white bg-purple-600 px-2 py-1 rounded">Producto</span>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-xs text-green-600 font-medium">activo</span>
                                </div>
                            </div>
                            <h3 class="font-medium text-gray-900 text-sm mb-1">Guía completa para invertir en video</h3>
                            <div class="flex items-center gap-3">
                                <span class="text-base font-bold text-gray-900">$49.95</span>
                                <span class="text-xs text-gray-500">• 0 ventas</span>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-white bg-orange-600 px-2 py-1 rounded">Producto</span>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-xs text-green-600 font-medium">activo</span>
                                </div>
                            </div>
                            <h3 class="font-medium text-gray-900 text-sm mb-1">Guía completa para invertir</h3>
                            <div class="flex items-center gap-3">
                                <span class="text-base font-bold text-gray-900">$90</span>
                                <span class="text-xs text-gray-500">• 0 ventas</span>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Preview Toggle -->
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-medium text-gray-900">Vista previa</h3>
                <button onclick="toggleMobilePreview()" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                    Ver preview
                </button>
            </div>
            <div class="text-center">
                <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-3">
                    <input
                        type="text"
                        value="https://clickmy.link/tradingsharks"
                        readonly
                        class="flex-1 text-sm text-gray-700 bg-transparent border-none outline-none"
                    />
                    <button onclick="copyUrl()" class="text-blue-600 hover:text-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Create New Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">¿Qué quieres crear?</h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="space-y-3">
            <button onclick="createLink()" class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Enlace</h4>
                        <p class="text-sm text-gray-600">Añade un enlace a cualquier URL</p>
                    </div>
                </div>
            </button>
            
            <button onclick="createProduct()" class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Producto Digital</h4>
                        <p class="text-sm text-gray-600">Vende archivos, cursos, membresías</p>
                    </div>
                </div>
            </button>
            
            <button onclick="createService()" class="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 01-4-4V7a4 4 0 118 0v4m-4 8V7"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Servicio</h4>
                        <p class="text-sm text-gray-600">Ofrece consultoría, coaching, etc.</p>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

<!-- Mobile Preview Modal -->
<div id="mobilePreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 lg:hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-sm w-full">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Vista previa</h3>
                <button onclick="closeMobilePreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="bg-black rounded-2xl p-1 mx-auto" style="max-width: 280px;">
                    <div class="bg-white rounded-xl overflow-hidden" style="height: 500px;">
                        <iframe 
                            id="mobilePreviewFrame" 
                            src="" 
                            class="w-full h-full border-none"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-js')
<script>
// Global variables
window.currentCardId = '{{ $card_id ?? "new" }}';
window.cardType = '{{ $card_type ?? "personal" }}';
window.baseUrl = '{{ url("") }}';

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeInterface();
    loadPreview();
});

// Initialize interface
function initializeInterface() {
    console.log('Initializing ClickMyLink Unified Interface');
    console.log('Card ID:', window.currentCardId);
    console.log('Card Type:', window.cardType);
    
    // Load real data if available
    loadUserData();
}

// Load user data from backend
function loadUserData() {
    // TODO: Fetch real user data via AJAX
    // This will connect to existing backend routes
    
    fetch(`${window.baseUrl}/user/get-card-data/${window.currentCardId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateProfileData(data.profile);
                updateItemsList(data.items);
            }
        })
        .catch(error => {
            console.log('Using demo data for now');
            // Keep demo data visible for development
        });
}

// Update profile section with real data
function updateProfileData(profile) {
    if (!profile) return;
    
    document.getElementById('profileName').textContent = profile.name || 'Trading Sharks';
    document.getElementById('profileUsername').textContent = `@${profile.username || 'tradingsharks'}`;
    document.getElementById('profileBio').textContent = profile.bio || 'Aquí le cambia la vida 🔥📈';
    document.getElementById('profileInitial').textContent = (profile.name || 'T')[0].toUpperCase();
    
    // Update URL
    const publicUrl = `${window.baseUrl}/${profile.username || 'tradingsharks'}`;
    document.getElementById('publicUrl').value = publicUrl;
}

// Update items list with real data
function updateItemsList(items) {
    if (!items || !items.length) return;
    
    const itemsList = document.getElementById('itemsList');
    itemsList.innerHTML = '';
    
    items.forEach(item => {
        const itemElement = createItemElement(item);
        itemsList.appendChild(itemElement);
    });
}

// Create item element
function createItemElement(item) {
    const div = document.createElement('div');
    div.className = 'bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow';
    
    const typeColor = item.type === 'link' ? 'blue' : item.type === 'product' ? 'purple' : 'green';
    const statusColor = item.is_active ? 'green' : 'gray';
    
    div.innerHTML = `
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-${typeColor}-100 rounded-lg flex items-center justify-center">
                ${getTypeIcon(item.type, typeColor)}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium ${item.type === 'link' ? 'text-gray-500 bg-gray-100' : 'text-white bg-' + typeColor + '-600'} px-2 py-1 rounded">
                        ${item.type === 'link' ? 'Link' : item.type === 'product' ? 'Producto' : 'Servicio'}
                    </span>
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 bg-${statusColor}-500 rounded-full"></div>
                        <span class="text-xs text-${statusColor}-600 font-medium">${item.is_active ? 'activo' : 'inactivo'}</span>
                    </div>
                </div>
                <h3 class="font-medium text-gray-900">${item.title}</h3>
                ${item.price ? `
                    <div class="flex items-center gap-4 mt-1">
                        <span class="text-lg font-bold text-gray-900">${item.price}</span>
                        <span class="text-sm text-gray-500">• ${item.sales || 0} ventas</span>
                    </div>
                ` : `
                    <p class="text-sm text-gray-600">${item.url || item.description}</p>
                `}
            </div>
            <button onclick="editItem('${item.id}')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                </svg>
            </button>
        </div>
    `;
    
    return div;
}

// Get type icon
function getTypeIcon(type, color) {
    const iconClass = `w-5 h-5 text-${color}-600`;
    
    switch(type) {
        case 'link':
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>`;
        case 'product':
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`;
        case 'service':
            return `<svg class="${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 01-4-4V7a4 4 0 118 0v4m-4 8V7"/></svg>`;
        default:
            return '';
    }
}

// Load preview in iframe
function loadPreview() {
    const previewFrame = document.getElementById('previewFrame');
    if (previewFrame && window.currentCardId !== 'new') {
        // Use existing preview route from backend
        previewFrame.src = `${window.baseUrl}/user/view-preview/${window.currentCardId}`;
    } else {
        // Show placeholder or demo
        previewFrame.style.background = '#1a1a1a';
    }
}

// Edit profile function
function editProfile() {
    // TODO: Open profile edit modal or redirect to edit page
    console.log('Edit profile clicked');
    // For now, redirect to existing profile edit
    window.location.href = `${window.baseUrl}/user/edit-profile`;
}

// Create new item functions
function createNew() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function createLink() {
    closeCreateModal();
    // TODO: Open link creation modal or redirect
    console.log('Create link clicked');
    window.location.href = `${window.baseUrl}/user/payment-links/${window.currentCardId}`;
}

function createProduct() {
    closeCreateModal();
    // TODO: Open product creation modal or redirect
    console.log('Create product clicked');
    window.location.href = `${window.baseUrl}/user/vproducts/${window.currentCardId}`;
}

function createService() {
    closeCreateModal();
    // TODO: Open service creation modal or redirect
    console.log('Create service clicked');
    window.location.href = `${window.baseUrl}/user/services/${window.currentCardId}`;
}

// Edit item function
function editItem(itemId) {
    // TODO: Open edit modal or redirect based on item type
    console.log('Edit item clicked:', itemId);
}

// Mobile preview functions
function toggleMobilePreview() {
    document.getElementById('mobilePreviewModal').classList.remove('hidden');
    loadMobilePreview();
}

function closeMobilePreview() {
    document.getElementById('mobilePreviewModal').classList.add('hidden');
}

function loadMobilePreview() {
    const mobileFrame = document.getElementById('mobilePreviewFrame');
    if (mobileFrame && window.currentCardId !== 'new') {
        mobileFrame.src = `${window.baseUrl}/user/view-preview/${window.currentCardId}`;
    }
}

// Copy URL function
function copyUrl() {
    const urlInput = document.getElementById('publicUrl');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        
        // Show success feedback
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = `
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        `;
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
        
    } catch (err) {
        console.error('Failed to copy URL:', err);
    }
}

// Auto-refresh preview when data changes
function refreshPreview() {
    loadPreview();
    if (document.getElementById('mobilePreviewModal').classList.contains('hidden') === false) {
        loadMobilePreview();
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const createModal = document.getElementById('createModal');
    const mobilePreviewModal = document.getElementById('mobilePreviewModal');
    
    if (event.target === createModal) {
        closeCreateModal();
    }
    
    if (event.target === mobilePreviewModal) {
        closeMobilePreview();
    }
});

// Handle escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateModal();
        closeMobilePreview();
    }
});

console.log('ClickMyLink Unified Interface loaded successfully');
</script>
@endpush 