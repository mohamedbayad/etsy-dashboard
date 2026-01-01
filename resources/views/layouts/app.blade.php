<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
    <div class="min-h-screen bg-background">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-card border-b border-border shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6" x-data="{ show: true }">

                @if (session('success'))
                <div x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="rounded-md border border-green-500 dark:border-green-600 bg-green-50 dark:bg-green-900/20 p-4 shadow-sm mb-4"
                    role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                    class="rounded-md border border-red-500 dark:border-red-600 bg-red-50 dark:bg-red-900/20 p-4 shadow-sm mb-4"
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
                <div class="rounded-md border border-red-500 dark:border-red-600 bg-red-50 dark:bg-red-900/20 p-4 shadow-sm mb-4" role="alert">
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

    <script>
        // 1. Listen for Paste Event on the whole document
        document.addEventListener('paste', function(event) {
            const items = (event.clipboardData || event.originalEvent.clipboardData).items;

            for (let index in items) {
                const item = items[index];
                if (item.kind === 'file' && item.type.includes('image/')) {

                    const blob = item.getAsFile();
                    const fileInput = document.getElementById('image_path');

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(blob);
                    fileInput.files = dataTransfer.files;

                    previewImage(fileInput);

                    // Optional: Feedback visual
                    const pasteArea = document.getElementById('paste_area');
                    pasteArea.classList.add('border-blue-500');
                    setTimeout(() => pasteArea.classList.remove('border-blue-500'), 500);
                }
            }
        });

        function previewImage(input) {
            const previewContainer = document.getElementById('preview_container');
            const placeholderText = document.getElementById('placeholder_text');
            const previewImg = document.getElementById('preview_img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    previewContainer.classList.add('flex');
                    placeholderText.classList.add('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const input = document.getElementById('image_path');
            const previewContainer = document.getElementById('preview_container');
            const placeholderText = document.getElementById('placeholder_text');

            input.value = '';
            previewContainer.classList.add('hidden');
            previewContainer.classList.remove('flex');
            placeholderText.classList.remove('hidden');
        }
    </script>
</body>

</html>
