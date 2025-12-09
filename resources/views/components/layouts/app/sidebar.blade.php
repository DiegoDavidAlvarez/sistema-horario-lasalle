<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

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

        /* Estilos personalizados para los menús Flux en el nuevo diseño */
        .flux-menu-custom .flux-menu__item {
            @apply flex items-center gap-2 px-2 py-1.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 dark:bg-slate-900 flex flex-col">

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
            <flux:dropdown position="top" align="end" class="lg:hidden">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

                <flux:menu class="flux-menu-custom">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-700 text-black dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span
                                        class="truncate font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span>
                                    <span
                                        class="truncate text-xs text-slate-600 dark:text-slate-400">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full"
                            data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
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
                            {{ __('Horario') }}</div>
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
                            {{ __('Docente') }}</div>
                    </div>
                </a>
            </div>

            <!-- Enlaces externos -->
            <div class="p-2 space-y-1 shrink-0">
                <a href="https://github.com/laravel/livewire-starter-kit" target="_blank"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400">
                            {{ __('Repository') }}</div>
                    </div>
                </a>

                <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank"
                    class="w-full text-left px-3 py-3 rounded flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div
                        class="w-8 h-8 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-xs font-bold group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div>
                        <div
                            class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-700 dark:group-hover:text-blue-400">
                            {{ __('Documentation') }}</div>
                    </div>
                </a>
            </div>

            <!-- Desktop User Menu -->
            <div class="mt-auto shrink-0">
                <flux:dropdown class="hidden lg:block" position="top" align="start">
                    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                        <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                            icon:trailing="chevrons-up-down" data-test="sidebar-menu-button" class="w-full" />
                    </div>

                    <flux:menu class="w-[220px] flux-menu-custom">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-700 text-black dark:text-white">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span
                                            class="truncate font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span>
                                        <span
                                            class="truncate text-xs text-slate-600 dark:text-slate-400">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full" data-test="logout-button">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </flux:sidebar>

        <!-- Contenido principal con scroll -->
        <div class="main-content">
            {{ $slot }}
        </div>
    </div>

    @fluxScripts
</body>

</html>