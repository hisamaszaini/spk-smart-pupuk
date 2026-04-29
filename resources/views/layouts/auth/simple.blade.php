<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gray-50 antialiased font-sans">

        <div class="min-h-screen flex">

            {{-- ── Left Panel: Branding ── --}}
            <div class="hidden lg:flex lg:w-1/2 bg-emerald-900 flex-col justify-between p-12 relative overflow-hidden">

                {{-- Decorative circles --}}
                <div class="absolute -top-20 -left-20 size-72 rounded-full bg-white/5"></div>
                <div class="absolute top-1/3 -right-16 size-56 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-16 left-1/4 size-48 rounded-full bg-white/5"></div>
                <div class="absolute bottom-1/4 -right-8 size-32 rounded-full bg-emerald-800/60"></div>

                {{-- Logo --}}
                <div class="relative flex items-center gap-3">
                    <div class="bg-white p-2 rounded-xl shadow-lg">
                        <svg class="size-7 text-emerald-900" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">SMART Pupuk</span>
                </div>

                {{-- Hero text --}}
                <div class="relative space-y-6">
                    <div class="space-y-3">
                        <p class="text-emerald-300 text-sm font-semibold uppercase tracking-widest">Sistem Pakar</p>
                        <h1 class="text-4xl font-bold text-white leading-tight">
                            Distribusi Pupuk<br>
                            <span class="text-emerald-300">Cerdas & Adil</span>
                        </h1>
                        <p class="text-emerald-100/70 text-base leading-relaxed max-w-sm">
                            Menggunakan metode SMART untuk mengalokasikan pupuk secara proporsional dan transparan kepada seluruh petani.
                        </p>
                    </div>

                    {{-- Feature pills --}}
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-full text-xs font-semibold text-white border border-white/10">
                            <svg class="size-3.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Metode SMART
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-full text-xs font-semibold text-white border border-white/10">
                            <svg class="size-3.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Perangkingan Otomatis
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-full text-xs font-semibold text-white border border-white/10">
                            <svg class="size-3.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Laporan Terperinci
                        </span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="relative">
                    <p class="text-emerald-100/40 text-xs">© {{ date('Y') }} SMART Pupuk. All rights reserved.</p>
                </div>
            </div>

            {{-- ── Right Panel: Form ── --}}
            <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-12">
                {{-- Mobile logo --}}
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <div class="bg-emerald-900 p-1.5 rounded-xl">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">SMART Pupuk</span>
                </div>

                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>
        </div>

        {{-- Toast container for auth pages --}}
        <div id="toast-container" aria-live="polite"></div>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('toast', ({ variant, heading, content }) => {
                    const icons = {
                        success: `<svg class="size-5 icon-success" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
                        error:   `<svg class="size-5 icon-error"   fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>`,
                    };
                    const c = document.getElementById('toast-container');
                    const t = document.createElement('div');
                    t.className = `toast-item toast-${variant}`;
                    t.innerHTML = `<div class="toast-icon-wrap">${icons[variant]??icons.error}</div><div class="flex-1"><p class="toast-heading">${heading}</p>${content?`<p class="toast-content">${content}</p>`:''}</div>`;
                    c.appendChild(t);
                    setTimeout(() => { t.classList.add('toast-out'); setTimeout(() => t.remove(), 280); }, 4000);
                });
            });
        </script>

    </body>
</html>
