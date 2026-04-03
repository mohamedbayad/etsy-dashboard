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
    <body class="font-sans antialiased"
      x-data="{
          dark: localStorage.getItem('dark') === 'true',
          toggleTheme() {
              this.dark = !this.dark;
              localStorage.setItem('dark', this.dark);
              document.documentElement.classList.toggle('dark', this.dark);
          }
      }"
      x-init="
          document.documentElement.classList.toggle('dark', localStorage.getItem('dark') === 'true');
      ">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-background via-background to-primary/5">

            {{-- Decorative background elements --}}
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-primary/5 blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-primary/5 blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <a href="/" class="inline-flex items-center gap-3 group">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-lg font-bold text-primary-foreground shadow-lg shadow-primary/20 transition-transform group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <span class="text-xl font-bold text-foreground">{{ config('app.name', 'Dashboard') }}</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-card/80 backdrop-blur-sm border border-border/60 shadow-xl overflow-hidden sm:rounded-2xl ring-1 ring-black/[0.02] dark:ring-white/[0.03]">
                {{ $slot }}
            </div>

            {{-- Theme toggle --}}
            <button @click="toggleTheme" type="button"
                    class="relative z-10 mt-6 inline-flex items-center gap-2 rounded-full border border-border bg-card/80 backdrop-blur-sm px-3 py-1.5 text-xs font-medium text-muted-foreground transition hover:text-foreground hover:bg-accent">
                <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="4" />
                    <path d="M12 2v2m0 16v2m8-10h2M2 12h2m12.24-5.76 1.42-1.42M6.34 17.66 4.93 19.07m12.73 0-1.42-1.41M6.34 6.34 4.93 4.93" />
                </svg>
                <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                </svg>
                <span x-text="dark ? 'Dark' : 'Light'"></span>
            </button>
        </div>
    </body>
</html>
