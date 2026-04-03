<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased selection:bg-primary/15"
    x-data="{
          dark: localStorage.getItem('dark') === 'true',
          sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
          mobileOpen: false,

          toggleTheme() {
              this.dark = !this.dark;
              localStorage.setItem('dark', this.dark);
              document.documentElement.classList.toggle('dark', this.dark);
          },
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebarOpen', this.sidebarOpen);
          }
      }"
    x-init="
          document.documentElement.classList.toggle('dark', localStorage.getItem('dark') === 'true');
      ">

    {{-- ════════════ SIDEBAR ════════════ --}}
    @include('layouts.navigation')

    {{-- Mobile overlay --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="admin-sidebar-overlay lg:hidden"
         style="display:none;"></div>

    {{-- ════════════ MAIN CONTENT ════════════ --}}
    <div class="admin-content-wrapper min-h-screen flex flex-col"
         :class="{ 'sidebar-collapsed': !sidebarOpen }">

        {{-- Top bar --}}
        <header class="admin-topbar">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen" class="admin-btn-icon lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                {{-- Sidebar toggle (desktop) --}}
                <button @click="toggleSidebar" class="admin-btn-icon hidden lg:inline-flex" title="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>

                @if (isset($header))
                <div class="hidden sm:block">
                    {{ $header }}
                </div>
                @endif
            </div>

            <div class="flex items-center gap-2">
                {{-- Theme toggle --}}
                <button @click="toggleTheme" type="button"
                        class="admin-btn-icon" title="Toggle theme">
                    <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v2m0 16v2m8-10h2M2 12h2m12.24-5.76 1.42-1.42M6.34 17.66 4.93 19.07m12.73 0-1.42-1.41M6.34 6.34 4.93 4.93" />
                    </svg>
                    <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                    </svg>
                </button>

                {{-- User dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm font-medium text-foreground shadow-sm transition hover:bg-accent focus:outline-none focus:ring-2 focus:ring-ring/40 focus:ring-offset-1">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-xs font-bold text-primary">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        {{-- Mobile header (shows on small screens) --}}
        @if (isset($header))
        <div class="sm:hidden border-b border-border/50 bg-card/50 px-4 py-3">
            {{ $header }}
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1">

            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8" x-data="{ show: true }">

                @if (session('success'))
                <div x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="rounded-lg border border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-950/30 p-4 shadow-sm mb-4 animate-fade-up"
                    role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if (session('error'))
                <div x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="rounded-lg border border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-950/30 p-4 shadow-sm mb-4 animate-fade-up"
                    role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div class="rounded-lg border border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-950/30 p-4 shadow-sm mb-4 animate-fade-up" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Please fix the following errors:</h3>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{ $slot }}
        </main>
    </div>

        {{-- ════════════ GLOBAL CONFIRMATION MODAL ════════════ --}}
        <div x-cloak x-show="$store.confirm.open" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="$store.confirm.open = false"></div>

            {{-- Modal Content --}}
            <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-card border border-border shadow-2xl transition-all animate-in zoom-in-95 duration-200"
                 @click.away="$store.confirm.open = false">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full"
                             :class="$store.confirm.type === 'danger' ? 'bg-red-100 text-red-600' : 'bg-primary/10 text-primary'">
                            <svg x-show="$store.confirm.type === 'danger'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.876c1.27 0 2.09-1.383 1.455-2.433l-6.938-12a1.25 1.25 0 00-2.11 0l-6.938 12c-.635 1.05.185 2.433 1.455 2.433z" />
                            </svg>
                            <svg x-show="$store.confirm.type !== 'danger'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-foreground" x-text="$store.confirm.title"></h3>
                            <p class="mt-2 text-sm text-muted-foreground leading-relaxed font-medium" x-text="$store.confirm.message"></p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col-reverse gap-2 border-t border-border/50 bg-muted/30 p-6 sm:flex-row sm:justify-end sm:gap-3">
                    <button type="button" 
                            @click="$store.confirm.open = false"
                            class="admin-btn-secondary w-full sm:w-auto font-bold px-6">
                        Cancel
                    </button>
                    <button type="button" 
                            @click="$store.confirm.handle()"
                            class="w-full sm:w-auto font-bold px-6"
                            :class="$store.confirm.type === 'danger' ? 'admin-btn-danger' : 'admin-btn-primary'">
                        Proceed
                    </button>
                </div>
            </div>
        </div>

    <script>
        // Custom Confirmation Logic using Alpine Store
        document.addEventListener('alpine:init', () => {
            Alpine.store('confirm', {
                open: false,
                title: 'Confirm Action',
                message: 'Are you sure you want to proceed?',
                type: 'danger',
                form: null,

                trigger(form, title = 'Confirm Action', message = 'Are you sure?', type = 'danger') {
                    this.form = form;
                    this.title = title;
                    this.message = message;
                    this.type = type;
                    this.open = true;
                },
                handle() {
                    if (this.form) {
                        this.form.submit();
                    }
                    this.open = false;
                }
            });
        });

        // Global Helper for Forms
        window.confirmAdminAction = (form, title, message, type) => {
            if (window.Alpine && window.Alpine.store('confirm')) {
                window.Alpine.store('confirm').trigger(form, title, message, type);
            } else {
                // Last-resort fallback for non-Alpine context
                if (confirm(message)) {
                    form.submit();
                }
            }
        };

        // 1. Listen for Paste Event on the whole document
        document.addEventListener('paste', function(event) {
            const items = (event.clipboardData || event.originalEvent.clipboardData).items;

            for (let index in items) {
                const item = items[index];
                if (item.kind === 'file' && item.type.includes('image/')) {

                    const blob = item.getAsFile();
                    const fileInput = document.getElementById('image_path');

                    const dataTransfer = new DataTransfer();
                    if (fileInput && fileInput.files) {
                        Array.from(fileInput.files).forEach((file) => dataTransfer.items.add(file));
                    }
                    dataTransfer.items.add(blob);
                    fileInput.files = dataTransfer.files;

                    previewImage(fileInput);

                    // Optional: Feedback visual
                    const pasteArea = document.getElementById('paste_area');
                    pasteArea.classList.add('border-primary');
                    setTimeout(() => pasteArea.classList.remove('border-primary'), 500);
                }
            }
        });

        function previewImage(input) {
            const previewContainer = document.getElementById('preview_container');
            const placeholderText = document.getElementById('placeholder_text');
            const previewList = document.getElementById('preview_list');

            if (!previewList) {
                return;
            }

            if (input.files && input.files[0]) {
                previewList.innerHTML = '';
                Array.from(input.files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Image Preview';
                        img.className = 'h-20 w-20 rounded-lg object-cover border border-border';
                        previewList.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex');
                placeholderText.classList.add('hidden');
            }
        }

        function removeImage() {
            const input = document.getElementById('image_path');
            const previewContainer = document.getElementById('preview_container');
            const placeholderText = document.getElementById('placeholder_text');
            const previewList = document.getElementById('preview_list');

            input.value = '';
            previewContainer.classList.add('hidden');
            previewContainer.classList.remove('flex');
            placeholderText.classList.remove('hidden');
            if (previewList) {
                previewList.innerHTML = '';
            }
        }
    </script>
</body>

</html>
