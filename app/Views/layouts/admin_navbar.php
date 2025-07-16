<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <title>Document</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Playfair+Display:wght@400;700;900&display=swap');
        body {
            font-family: 'Lato', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

    .nav-glass {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(220, 38, 38, 0.2);
            color: #fff
        }
</style>

<?php
    // Mengambil service URI untuk mendapatkan path URL saat ini
    $uri = service('uri');
?>
    <nav class="nav-glass p-4 shadow-2xl sticky top-0 z-50">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold tracking-widest text-shadow">
                <span class="text-red-500">Admin</span> Panel
            </h1>
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-6">
                <?php
                    $dashboard_class = ($uri->getSegment(2) == 'dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
                    $history_class = ($uri->getSegment(2) == 'history') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
                    $kamar_class = ($uri->getSegment(2) == 'kamar') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
                ?>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="<?= base_url('admin/kamar') ?>" class="<?= $kamar_class ?> text-gray-300 hover:text-red-400 transition-colors flex items-center space-x-2">
                        <i class='bx bx-bed text-lg'></i>
                        <span>Kamar</span>
                    </a>
                    <a href="<?= base_url('admin/history') ?>" class="<?= $history_class ?> text-gray-300 hover:text-red-400 transition-colors flex items-center space-x-2">
                        <i class='bx  bx-history'  ></i> 
                        <span>History</span>
                    </a>
                    </a>
                    <a href="<?= base_url('admin/dashboard') ?>" class="<?= $history_class ?> text-gray-300 hover:text-red-400 transition-colors flex items-center space-x-2">
                        <i class='bx  bx-user'  ></i>  
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="<?= $dashboard_class ?> text-red-300 hover:text-red-600 transition-colors flex items-center space-x-2">
                        <i class='bx bx-chevrons-left'></i> 
                        <span>Logout</span>
                    </a>
                </div>

            </div>
                <div class="w-px h-6 bg-gray-600 hidden md:block"></div>
                <div class="flex items-center space-x-2 text-gray-300">
                    <i class='bx bx-user-circle text-2xl'></i>
                    <span class="hidden sm:inline">Admin</span>
                </div>
            </div>
        </div>
    </nav>

