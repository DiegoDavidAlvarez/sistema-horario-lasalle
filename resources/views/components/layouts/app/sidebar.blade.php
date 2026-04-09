<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuración de SweetAlert con tema dinámico
        function getSwalTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e293b',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: isDark ? '#64748b' : '#94a3b8',
                customClass: {
                    popup: isDark
                        ? 'rounded-xl shadow-2xl border border-slate-600'
                        : 'rounded-xl shadow-2xl border border-slate-200'
                }
            };
        }

        // SweetAlert con tema - uso: SwalThemed.fire({...})
        const SwalThemed = {
            fire: function (options) {
                const theme = getSwalTheme();
                return Swal.fire({
                    ...theme,
                    ...options,
                    customClass: {
                        ...theme.customClass,
                        ...(options.customClass || {})
                    }
                });
            },
            success: function (title, text) {
                const theme = getSwalTheme();
                return Swal.fire({
                    ...theme,
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: title,
                    text: text,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    iconColor: '#22c55e',
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                        toast.addEventListener('click', Swal.close)
                    }
                });
            },
            error: function (title, text) {
                const theme = getSwalTheme();
                return Swal.fire({
                    ...theme,
                    icon: 'error',
                    title: title,
                    text: text,
                    iconColor: '#ef4444'
                });
            },
            warning: function (title, text) {
                const theme = getSwalTheme();
                return Swal.fire({
                    ...theme,
                    icon: 'warning',
                    title: title,
                    text: text,
                    iconColor: '#f59e0b'
                });
            },
            confirm: function (title, text, confirmText = 'Sí, eliminar', cancelText = 'Cancelar') {
                const theme = getSwalTheme();
                return Swal.fire({
                    ...theme,
                    icon: 'warning',
                    title: title,
                    text: text,
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText
                });
            }
        };

        // --- GLOBAL LOADING SCREEN CONTROL ---
        
        function showLoadingScreen() {
            let loader = document.getElementById('global-loader');
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'global-loader';
                loader.innerHTML = `
                    <div class="loader-content">
                        <div class="spinner"></div>
                        <p class="mt-4 text-white font-medium text-lg">Procesando solicitud...</p>
                    </div>
                `;
                document.body.appendChild(loader);
            }
            // Pequeño timeout para permitir que el navegador renderice si es necesario
            setTimeout(() => {
                loader.classList.add('active');
            }, 10);
        }

        function hideLoadingScreen() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.remove('active');
                // Esperar a que termine la transición de opacidad (300ms) para remover del DOM
                setTimeout(() => {
                    if (!loader.classList.contains('active')) {
                        loader.remove();
                    }
                }, 300); 
            }
        }

        // Interceptores Globales
        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. Envío de Formularios (Delegación de eventos para soportar modales y contenido dinámico)
            document.addEventListener('submit', function(e) {
                const form = e.target;
                // Verificar que sea un formulario
                if (!form || form.tagName !== 'FORM') return;

                // Si el formulario no es válido, no mostramos el loader
                if (!form.checkValidity()) return;
                
                // Si el form tiene target="_blank" no mostramos loader
                if (form.target === '_blank') return;
                
                showLoadingScreen();
            });

            // 2. Interceptor para envíos programáticos via .submit() (Ej: Botones de eliminar con SweetAlert)
            const originalSubmit = HTMLFormElement.prototype.submit;
            HTMLFormElement.prototype.submit = function() {
                if (this.target !== '_blank') {
                    showLoadingScreen();
                }
                originalSubmit.apply(this);
            };

            // 2. Interceptor global para jQuery AJAX (como el botón DNI)
            $(document).ajaxStart(function() {
                showLoadingScreen();
            }).ajaxStop(function() {
                hideLoadingScreen();
            });

            // 3. Manejo de Livewire (si se usa en el futuro para eventos globales)
            // Se puede descomentar si Livewire está presente globalmente
            /*
            if (window.Livewire) {
                 Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                    showLoadingScreen();
                    succeed(({ snapshot, effect }) => {
                        queueMicrotask(() => {
                            hideLoadingScreen();
                        })
                    })
                    fail(() => {
                        hideLoadingScreen();
                    })
                })
            }
            */
        });
    </script>
    <style>

        /* Estilos Global Loader */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro semitransparente */
            backdrop-filter: blur(4px); /* Efecto blur moderno */
            z-index: 99999; /* Por encima de todo, incluso modales (z-50) y Swal (z-10000 estandar) */
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            pointer-events: none; /* Mientras está invisible no bloquea clicks */
        }

        #global-loader.active {
            opacity: 1;
            pointer-events: all; /* Ahora sí bloquea */
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Spinner CSS Puro */
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #3b82f6; /* Azul Tailwind (blue-500) */
            animation: spin 1s ease-in-out infinite;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }



        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Patrón visual para el receso */
        .break-pattern {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 10px 10px;
            background-color: #f8fafc;
        }

        .dark .break-pattern {
            background-image: radial-gradient(#475569 1px, transparent 1px);
            background-color: #1e293b;
        }

        /* Asegurar que el contenido principal ocupe el espacio disponible */
        .main-content {
            flex: 1;
            overflow: auto;
            height: calc(100vh - 4rem);
        }

        /* Estilos personalizados para los menús de usuario */
        [data-flux-menu] {
            background: white !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        .dark [data-flux-menu] {
            background: #1e293b !important;
            border-color: #475569 !important;
        }

        /* Estilos para items del menú */
        [data-flux-menu-item] {
            color: #334155 !important;
            transition: all 0.15s ease !important;
        }

        [data-flux-menu-item]:hover {
            background: #f1f5f9 !important;
            color: #1e293b !important;
        }

        .dark [data-flux-menu-item] {
            color: #cbd5e1 !important;
        }

        .dark [data-flux-menu-item]:hover {
            background: #334155 !important;
            color: #f1f5f9 !important;
        }

        /* Separadores del menú */
        [data-flux-separator] {
            border-color: #e2e8f0 !important;
        }

        .dark [data-flux-separator] {
            border-color: #475569 !important;
        }

        /* Botón de cerrar sesión con efecto rojo */
        .logout-item:hover {
            background: #fef2f2 !important;
            color: #dc2626 !important;
        }

        .dark .logout-item:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #f87171 !important;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 dark:bg-slate-900 flex flex-col">
    @guest
        <script>window.location.href = "{{ route('login') }}";</script>
    @endguest

    <!-- Navbar Superior -->
    <header
        class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 h-16 flex items-center justify-between px-6 z-20 shrink-0">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 text-white w-8 h-8 rounded flex items-center justify-center font-bold">
                SA
            </div>
            <h1 class="font-bold text-slate-700 dark:text-slate-300 tracking-tight">Sistema Académico <span
                    class="font-normal text-slate-400 dark:text-slate-500">| {{ config('app.name', 'Laravel') }}</span>
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <!-- Mobile User Menu -->
            @auth
                <flux:dropdown position="top" align="end" class="lg:hidden">
                    <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

                    <flux:menu class="w-[220px]">
                        <flux:menu.radio.group>
                            <div class="p-3">
                                <div class="flex items-center gap-3">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white font-semibold text-sm">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full logout-item" data-test="logout-button">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @endauth
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Panel Lateral: Sidebar -->
        <flux:sidebar sticky stashable
            class="w-72 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 flex flex-col z-10 shrink-0">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <!-- Logo en sidebar -->
            <a href="{{ route('dashboard') }}"
                class="p-4 border-b border-slate-100 dark:border-slate-700 shrink-0 flex items-center space-x-2 rtl:space-x-reverse"
                wire:navigate>
                <x-app-logo />
            </a>

            <div class="p-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Navegación</h2>
            </div>

            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}"
                    wire:navigate>
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fa-solid fa-home"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400 {{ request()->routeIs('dashboard') ? 'text-blue-700 dark:text-blue-400' : '' }}">
                            {{ __('Horario') }}
                        </div>
                    </div>
                </a>

                <!-- Docente -->
                <a href="{{ route('admin.docente.index') }}"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500 {{ request()->routeIs('admin.docente.index') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}"
                    wire:navigate>
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition {{ request()->routeIs('admin.docente.index') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400 {{ request()->routeIs('admin.docente.index') ? 'text-blue-700 dark:text-blue-400' : '' }}">
                            {{ __('Docentes') }}
                        </div>
                    </div>
                </a>

                <!-- Programa de Estudios -->
                <a href="{{ route('admin.programa-estudio.index') }}"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500 {{ request()->routeIs('admin.programa-estudio.index') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}"
                    wire:navigate>
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition {{ request()->routeIs('admin.programa-estudio.index') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400 {{ request()->routeIs('admin.programa-estudio.index') ? 'text-blue-700 dark:text-blue-400' : '' }}">
                            {{ __('Programa de Estudios') }}
                        </div>
                    </div>
                </a>

                <!-- Unidades Didácticas -->
                <a href="{{ route('admin.unidad-didactica.index') }}"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500 {{ request()->routeIs('admin.unidad-didactica.index') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}"
                    wire:navigate>
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition {{ request()->routeIs('admin.unidad-didactica.index') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400 {{ request()->routeIs('admin.unidad-didactica.index') ? 'text-blue-700 dark:text-blue-400' : '' }}">
                            {{ __('Unidades Didácticas') }}
                        </div>
                    </div>
                </a>

                <!-- Malla Curricular -->
                <a href="{{ route('admin.malla-curricular.index') }}"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500 {{ request()->routeIs('admin.malla-curricular.index') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}"
                    wire:navigate>
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition {{ request()->routeIs('admin.malla-curricular.index') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400 {{ request()->routeIs('admin.malla-curricular.index') ? 'text-blue-700 dark:text-blue-400' : '' }}">
                            {{ __('Malla Curricular') }}
                        </div>
                    </div>
                </a>

                <!-- Espacio Físico -->
                <a href="{{ route('admin.espacio-fisico.index') }}"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500 {{ request()->routeIs('admin.espacio-fisico.index') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}"
                    wire:navigate>
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition {{ request()->routeIs('admin.espacio-fisico.index') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400 {{ request()->routeIs('admin.espacio-fisico.index') ? 'text-blue-700 dark:text-blue-400' : '' }}">
                            {{ __('Espacio Físico') }}
                        </div>
                    </div>
                </a>
            </div>

            <!-- Desktop User Menu -->
            <div class="mt-auto shrink-0">
                @auth
                    <flux:dropdown class="hidden lg:block" position="top" align="start">
                        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                                icon:trailing="chevrons-up-down" data-test="sidebar-menu-button" class="w-full" />
                        </div>

                        <flux:menu class="w-[240px]">
                            <flux:menu.radio.group>
                                <div class="p-3">
                                    <div class="flex items-center gap-3">
                                        <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                                            <span
                                                class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white font-semibold text-sm">
                                                {{ auth()->user()->initials() }}
                                            </span>
                                        </span>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">
                                                {{ auth()->user()->name }}
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                                {{ auth()->user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <flux:menu.radio.group>
                                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                    {{ __('Settings') }}
                                </flux:menu.item>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                    class="w-full logout-item" data-test="logout-button">
                                    {{ __('Log Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                @endauth
            </div>
        </flux:sidebar>

        <!-- Contenido principal con scroll -->
        <div class="main-content">
            {{ $slot }}

            {{-- Manejo de Errores de Sesión (Global dentro del contenido dinámico) --}}
            @if (session('error'))
                <script>
                    // Función para mostrar el error
                    (function() {
                        const errorMessage = "{{ session('error') }}";
                        const show = () => SwalThemed.error("¡Acceso Denegado!", errorMessage);
                        
                        // Intentar mostrar inmediatamente (para cargas normales)
                        if (document.readyState === 'complete' || document.readyState === 'interactive') {
                            show();
                        } else {
                            document.addEventListener('DOMContentLoaded', show);
                        }
                        
                        // También escuchar navegación de Livewire (para SPA)
                        document.addEventListener('livewire:navigated', show, { once: true });
                    })();
                </script>
            @endif
        </div>

    @fluxScripts
</body>

</html>