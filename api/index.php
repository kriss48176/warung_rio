<?php

// Jalankan pembersihan cache secara otomatis setiap kali dipanggil di Vercel
exec('php artisan config:clear');
exec('php artisan view:clear');

require __DIR__ . '/../public/index.php';
