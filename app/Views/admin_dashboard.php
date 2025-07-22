<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Playfair+Display:wght@400;700;900&display=swap');

        body {
            font-family: 'Lato', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

        .scroll-float {
            opacity: 0;
            transition: all 2s ease-out;
        }

        .scroll-float.show {
            animation: floatIn 1.5s ease forwards;
        }

        @keyframes floatIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
        }

        .glass-effect {
            background: rgba(55, 65, 81, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-glass {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(220, 38, 38, 0.2);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .card-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .card-hover:hover::before {
            left: 100%;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(220, 38, 38, 0.15);
        }

        .table-row:hover {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.05) 0%, rgba(55, 65, 81, 0.05) 100%);
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .btn-checkin { /* Mengubah nama class agar lebih sesuai */
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-checkin::before { /* Mengubah nama class agar lebih sesuai */
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-checkin:hover::before { /* Mengubah nama class agar lebih sesuai */
            left: 100%;
        }

        .btn-checkin:hover { /* Mengubah nama class agar lebih sesuai */
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            border: 1px solid rgba(16, 185, 129, 0.3);
            backdrop-filter: blur(10px);
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(185, 28, 28, 0.1) 100%);
            border: 1px solid rgba(220, 38, 38, 0.3);
            backdrop-filter: blur(10px);
        }

        .table-header {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(55, 65, 81, 0.2) 100%);
            backdrop-filter: blur(10px);
        }

        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }

        .glow-effect {
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.2);
        }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen">

    <!-- Memanggil Navbar dari layout terpisah -->
    <?= $this->include('layouts/admin_navbar') ?>
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto mt-8 p-6">
        <!-- Notifikasi Sukses atau Error -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success text-green-300 px-6 py-4 rounded-xl mb-6 scroll-float glow-effect" role="alert">
                <div class="flex items-center space-x-3">
                    <i class='bx bx-check-circle text-xl floating-icon'></i>
                    <span class="font-semibold"><?= session()->getFlashdata('success') ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error text-red-300 px-6 py-4 rounded-xl mb-6 scroll-float glow-effect" role="alert">
                <div class="flex items-center space-x-3">
                    <i class='bx bx-error-circle text-xl floating-icon'></i>
                    <span class="font-semibold"><?= session()->getFlashdata('error') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Header Section -->
        <div class="mb-8 scroll-float">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-3 bg-red-600/20 rounded-xl">
                    <i class='bx bx-log-in-circle text-3xl text-red-400 floating-icon'></i> <!-- Icon diubah -->
                </div>
                <div>
                    <h2 class="text-4xl font-bold text-shadow">
                        <span class="text-red-400">Reservasi</span> Menunggu Check-In
                    </h2>
                    <p class="text-gray-300 text-lg mt-1">Daftar tamu yang telah melakukan reservasi dan siap untuk check-in.</p>
                </div>
            </div>
            
            <!-- Stats Cards (Tidak diubah) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-effect p-6 rounded-xl card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-semibold">Menunggu Check-In</p>
                            <p class="text-2xl font-bold text-red-400"><?= count($reservations ?? []) ?></p>
                        </div>
                        <div class="p-3 bg-red-600/20 rounded-lg">
                            <i class='bx bx-calendar-event text-xl text-red-400'></i>
                        </div>
                    </div>
                </div>
                
                <div class="glass-effect p-6 rounded-xl card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-semibold">Hari Ini</p>
                            <p class="text-2xl font-bold text-yellow-400">
                                <?= date('d M Y') ?>
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-600/20 rounded-lg">
                            <i class='bx bx-time text-xl text-yellow-400'></i>
                        </div>
                    </div>
                </div>
                
                <div class="glass-effect p-6 rounded-xl card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-semibold">Status</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></div>
                                <span class="text-green-400 font-semibold">Online</span>
                            </div>
                        </div>
                        <div class="p-3 bg-green-600/20 rounded-lg">
                            <i class='bx bx-wifi text-xl text-green-400'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Table -->
        <div class="glass-effect rounded-2xl overflow-hidden shadow-2xl scroll-float">
            <div class="overflow-x-hidden">
                <table class="min-w-full">
                    <thead>
                        <tr class="table-header">
                            <!-- PERUBAHAN: Header tabel disesuaikan -->
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Nama Tamu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Tipe Kamar</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Tgl Check-in</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center"> <!-- PERUBAHAN: colspan disesuaikan -->
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="p-6 bg-gray-600/20 rounded-full">
                                            <i class='bx bx-moon text-4xl text-gray-400'></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-300 mb-2">Tidak ada reservasi</h3>
                                            <p class="text-gray-500">Belum ada tamu yang menunggu untuk check-in.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $booking): ?>
                            <tr class="table-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                            <i class='bx bx-user text-white text-sm'></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-200"><?= esc($booking['nama_tamu']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="px-3 py-1 bg-blue-600/20 text-blue-400 rounded-full text-xs font-semibold">
                                        <?= esc($booking['tipe_kamar']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <i class='bx bx-calendar text-green-400 text-sm'></i>
                                        <span class="text-sm text-gray-300"><?= date('d M Y', strtotime($booking['tgl_masuk'])) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full pulse-dot"></div>
                                        <span class="text-sm font-semibold text-yellow-400"><?= esc($booking['status']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- PERUBAHAN: Tombol dan link diubah untuk aksi Check-In -->
                                    <a href="<?= base_url('admin/reservasi/checkin/' . $booking['id_reservasi']) ?>" 
                                       class="btn-checkin text-white font-bold py-2 px-4 rounded-lg text-xs flex items-center space-x-2"
                                       onclick="return confirm('Anda yakin tamu ini akan Check-In sekarang?')">
                                        <i class='bx bx-log-in-circle'></i>
                                        <span>Check-In</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-black/50 backdrop-blur-sm mt-20 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-gray-400">© 2024 Hotel Sepuluh Bandung - Admin Panel. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <script>
        // Scroll Animation
        const scrollElements = document.querySelectorAll('.scroll-float');
        const elementInView = (el, offset = 100) => el.getBoundingClientRect().top <= (window.innerHeight - offset);
        const displayScrollElement = (element) => element.classList.add('show');
        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el)) displayScrollElement(el);
            });
        };
        window.addEventListener('scroll', handleScrollAnimation);
        window.addEventListener('load', handleScrollAnimation);

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-success, .alert-error');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

</body>
</html>