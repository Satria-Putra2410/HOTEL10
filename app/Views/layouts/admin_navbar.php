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
                <a href="<?= base_url('admin/dashboard') ?>" class="text-white hover:text-red-500 transition-colors"><span class="text-red-500">Admin</span> Panel</a>
            </h1>
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-6">
                <?php
                    // Logika untuk menentukan class 'active' pada setiap link
                    $base_class      = 'px-3 py-2 rounded-lg transition-colors flex items-center space-x-2';
                    $active_class    = 'bg-red-600/50 text-white';
                    $inactive_class  = 'text-gray-300 hover:bg-gray-700/50';

                    $dashboard_class = ($uri->getSegment(2) == 'dashboard') ? $active_class : $inactive_class;
                    $checkin_class   = ($uri->getSegment(2) == 'checkin') ? $active_class : $inactive_class;
                    $kamar_class     = ($uri->getSegment(2) == 'kamar') ? $active_class : $inactive_class;
                    $history_class   = ($uri->getSegment(2) == 'history') ? $active_class : $inactive_class;
                ?>
                <div class="hidden md:flex items-center space-x-2">
                    <a href="<?= base_url('admin/dashboard') ?>" class="<?= $base_class ?> <?= $dashboard_class ?>">
                        <i class='bx bx-grid-alt text-lg'></i>  
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="<?= base_url('admin/checkin') ?>" class="<?= $base_class ?> <?= $checkin_class ?>">
                        <i class='bx bx-log-in-circle text-lg'></i> <!-- PERBAIKAN: Ikon diubah -->
                        <span>Check-In</span>
                    </a>

                    <a href="<?= base_url('admin/kamar') ?>" class="<?= $base_class ?> <?= $kamar_class ?>">
                        <i class='bx bx-bed text-lg'></i>
                        <span>Kamar</span>
                    </a>

                    <a href="<?= base_url('admin/history') ?>" class="<?= $base_class ?> <?= $history_class ?>">
                        <i class='bx bx-history text-lg'></i> 
                        <span>History</span>
                    </a>
                    <!-- PERBAIKAN: Tag <a> berlebih dihapus dari sini -->
                </div>

            </div>
                <div class="w-px h-6 bg-gray-600 hidden md:block"></div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-gray-300">
                        <i class='bx bx-user-circle text-2xl'></i>
                        <span class="hidden sm:inline">Admin</span>
                    </div>
                     <a href="<?= base_url('logout') ?>" class="text-gray-300 hover:text-red-500 transition-colors flex items-center space-x-2" title="Logout">
                        <i class='bx bx-log-out text-2xl'></i> 
                    </a>
                </div>
            </div>
        </div>
    </nav>
