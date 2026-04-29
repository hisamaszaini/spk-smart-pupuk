<section class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Pengaturan Akun') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('Kelola informasi profil dan keamanan kata sandi Anda.') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
        {{-- Profile Information Card --}}
        <div class="card p-6 md:p-8">
            <header class="mb-6 border-b border-gray-100 pb-4">
                <div class="flex items-center gap-3 text-emerald-700">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Informasi Profil') }}</h2>
                </div>
                <p class="text-gray-500 text-sm mt-1">{{ __('Perbarui nama dan alamat email akun Anda.') }}</p>
            </header>

            <form wire:submit="updateProfileInformation" class="space-y-6">
                <div>
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="search-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <input wire:model="name" id="name" type="text" class="form-input" required autofocus autocomplete="name" />
                    </div>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="search-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <input wire:model="email" id="email" type="email" class="form-input" required autocomplete="email" />
                    </div>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn-primary py-2.5 px-6">{{ __('Simpan Profil') }}</button>
                    <x-action-message class="text-emerald-600 font-medium" on="profile-updated">{{ __('Tersimpan.') }}</x-action-message>
                </div>
            </form>
        </div>

        {{-- Security Card --}}
        <div class="card p-6 md:p-8">
            <header class="mb-6 border-b border-gray-100 pb-4">
                <div class="flex items-center gap-3 text-emerald-700">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Keamanan') }}</h2>
                </div>
                <p class="text-gray-500 text-sm mt-1">{{ __('Ganti kata sandi Anda secara berkala.') }}</p>
            </header>

            <form wire:submit="updatePassword" class="space-y-6">
                <div>
                    <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                    <div class="search-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <input wire:model="current_password" id="current_password" type="password" class="form-input" autocomplete="current-password" placeholder="••••••••" />
                    </div>
                    @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="form-label">Kata Sandi Baru</label>
                    <div class="search-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <input wire:model="password" id="password" type="password" class="form-input" autocomplete="new-password" placeholder="••••••••" />
                    </div>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <div class="search-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-input" autocomplete="new-password" placeholder="••••••••" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn-primary py-2.5 px-6">{{ __('Ganti Kata Sandi') }}</button>
                    <x-action-message class="text-emerald-600 font-medium" on="password-updated">{{ __('Berhasil.') }}</x-action-message>
                </div>
            </form>
        </div>
    </div>
</section>
