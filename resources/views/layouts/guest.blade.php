<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest</title>

    <!-- Tailwind (jika pakai Breeze) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
