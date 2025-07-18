<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Riwayat Reservasi') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap');
        body { font-family: 'Lato', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        .gradient-bg { background: linear-gradient(135deg, #1f2937 0%, #111827 100%); }
        .glass-effect { background: rgba(55, 65, 81, 0.3); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .table-row:hover { background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(55, 65, 81, 0.05) 100%); transform: translateX(5px); transition: all 0.3s ease; }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen">

    <?= $this->include('layouts/admin_navbar') ?>
    
    <div class="max-w-7xl mx-auto mt-8 p-6">
        <!-- Notifikasi -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/10 border border-green-500/30 text-green-300 px-6 py-4 rounded-xl mb-6" role="alert">
                <span class="font-semibold"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>
        
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-shadow"><span class="text-red-400">Riwayat</span> Reservasi</h2>
            <p class="text-gray-300 text-lg mt-1">Daftar semua reservasi yang telah selesai.</p>
        </div>

        <div class="glass-effect rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-hidden">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-700/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Nama Tamu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Tipe Kamar</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Total Bayar</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                    <i class='bx bx-archive-out text-4xl mb-2'></i>
                                    <p>Belum ada riwayat reservasi yang tersimpan.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $history): ?>
                            <tr class="table-row">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold"><?= esc($history['nama_tamu']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= esc($history['tipe_kamar']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    <?= date('d M Y', strtotime($history['tgl_masuk'])) ?> - <?= date('d M Y', strtotime($history['tgl_keluar'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-400">
                                    Rp <?= number_format($history['total_harga_reservasi'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-green-300 text-gray-900 rounded-full text-xs font-semibold">
                                        <?= esc($history['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
