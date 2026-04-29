<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'SMART Pupuk') }}</title>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gray-50 antialiased font-sans">

    {{-- Sidebar Overlay (mobile) --}}
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- Sidebar --}}
    @include('layouts.app.sidebar')

    {{-- Top Header --}}
    <header id="app-header" class="flex items-center px-4 sm:px-6">
        {{-- Left side: Mobile hamburger (takes space on mobile, empty on desktop) --}}
        <div class="flex-1 flex items-center">
            <button id="sidebar-toggle" onclick="toggleSidebar()"
                class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-all duration-150">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        {{-- Center: App name --}}
        <div class="flex-none text-center">
            <span class="text-sm sm:text-base font-bold text-emerald-900 tracking-wide uppercase">Sistem Pakar SMART Pupuk</span>
        </div>

        {{-- Right: User Dropdown --}}
        <div class="flex-1 flex justify-end">
            <div class="relative" id="user-dropdown-wrapper">
                <button onclick="toggleUserDropdown()"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-gray-100 transition-all duration-150 cursor-pointer">
                    <div class="size-9 rounded-full bg-emerald-700 flex items-center justify-center text-white text-xs font-bold tracking-wide shadow-sm flex-shrink-0">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div class="hidden sm:flex flex-col items-start leading-tight">
                        <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-gray-400">{{ auth()->user()->email }}</span>
                    </div>
                    <svg class="size-4 text-gray-400 transition-transform duration-150" id="dropdown-chevron" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div class="user-dropdown-menu" id="user-dropdown-menu">
                    <a href="{{ route('profile.edit') }}" wire:navigate class="dropdown-item">
                        <svg class="size-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengaturan
                    </a>
                    <div class="dropdown-separator"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
    </header>

    {{-- Main Content --}}
    <main id="app-main" class="flex flex-col">
        <div class="max-w-7xl mx-auto w-full flex-1 px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </div>

        <footer class="mt-auto py-6 border-t border-gray-200 text-center bg-white/50">
            <p class="text-sm font-medium text-gray-400">© {{ date('Y') }} SMART Pupuk. All rights reserved.</p>
        </footer>
    </main>

    {{-- Toast Container --}}
    <div id="toast-container" aria-live="polite" aria-label="Notifikasi"></div>

    {{-- Global JS: Modal + Toast + Sidebar + Dropdown --}}
    <script>
        // ── Sidebar ──────────────────────────────────────────────────
        const sidebar = document.getElementById('app-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const header = document.getElementById('app-header');
        const mainEl = document.getElementById('app-main');
        const isDesktop = () => window.innerWidth >= 1024;

        function openSidebar() {
            sidebar.classList.remove('sidebar-hidden');
            if (!isDesktop()) {
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSidebar() {
            if (!isDesktop()) {
                sidebar.classList.add('sidebar-hidden');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        function toggleSidebar() {
            sidebar.classList.contains('sidebar-hidden') ? openSidebar() : closeSidebar();
        }

        // On resize, reset mobile overflow
        window.addEventListener('resize', () => {
            if (isDesktop()) {
                sidebar.classList.remove('sidebar-hidden');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('sidebar-hidden');
            }
        });

        // Init: hide sidebar on mobile
        if (!isDesktop()) {
            sidebar.classList.add('sidebar-hidden');
        }

        // ── User Dropdown ─────────────────────────────────────────────
        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            const chevron = document.getElementById('dropdown-chevron');
            const isOpen = menu.classList.contains('open');
            menu.classList.toggle('open', !isOpen);
            chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        }

        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('user-dropdown-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('user-dropdown-menu')?.classList.remove('open');
                document.getElementById('dropdown-chevron').style.transform = '';
            }
        });

        // ── Modal ─────────────────────────────────────────────────────
        function openModal(name) {
            const el = document.getElementById('modal-' + name);
            if (!el) return;
            el.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(name) {
            const el = document.getElementById('modal-' + name);
            if (!el) return;
            el.classList.remove('modal-open');
            document.body.style.overflow = '';
        }

        function closeModalOnBackdrop(event, name) {
            if (event.target === event.currentTarget) closeModal(name);
        }

        // ── Toast ─────────────────────────────────────────────────────
        const toastIcons = {
            success: `<svg class="size-5 icon-success" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            error: `<svg class="size-5 icon-error" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>`,
            info: `<svg class="size-5 icon-info"  fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>`,
        };

        function showToast(variant = 'success', heading = '', content = '') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const icon = toastIcons[variant] ?? toastIcons.info;
            toast.className = `toast-item toast-${variant}`;
            toast.innerHTML = `
                    <div class="toast-icon-wrap">${icon}</div>
                    <div class="flex-1 min-w-0">
                        ${heading ? `<p class="toast-heading">${heading}</p>` : ''}
                        ${content ? `<p class="toast-content">${content}</p>` : ''}
                    </div>
                    <button onclick="this.closest('.toast-item').remove()" class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                `;
            container.appendChild(toast);
            // Auto dismiss after 4s
            setTimeout(() => {
                toast.classList.add('toast-out');
                setTimeout(() => toast.remove(), 280);
            }, 4000);
        }

        // ── Livewire Event Listeners ──────────────────────────────────
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-modal', ({
                name
            }) => openModal(name));
            Livewire.on('close-modal', ({
                name
            }) => closeModal(name));
            Livewire.on('toast', ({
                variant,
                heading,
                content
            }) => showToast(variant, heading, content));
        });
    </script>

</body>

</html>