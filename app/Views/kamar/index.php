<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Playfair+Display:wght@400;700;900&display=swap');

    body {
      font-family: 'Lato', sans-serif;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
    }

    .gradient-bg {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }
    .glass-effect {
        background: rgba(55, 65, 81, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .table-row:hover {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(55, 65, 81, 0.05) 100%);
        transform: translateX(5px);
        transition: all 0.3s ease;
    }
</style>
</head>
<body class="gradient-bg text-white ">
    
    <?= $this->include('layouts/admin_navbar') ?>

    <div class="container mx-auto mt-8 p-5">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold">
                <span class="text-red-500">Manajemen </span> Kamar</h2>
            <a href="<?= base_url('admin/kamar/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                + Tambah Kamar
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Tabel Kamar -->
        <div class="glass-effect rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-hidden">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-700/50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Nomor Kamar</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Tipe Kamar</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Harga/Malam</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <?php if (empty($rooms)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                    <p>Belum ada data unit kamar.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rooms as $room): ?>
                            <tr class="table-row">
                                <td class="px-6 py-4">
                                    <img src="<?= base_url('uploads/kamar/' . $room['foto']) ?>" alt="<?= esc($room['tipe_kamar']) ?>" class="w-24 h-16 object-cover rounded">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-white">
                                    <?= esc($room['nomor_kamar']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-white">
                                    <?= esc($room['tipe_kamar']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-400">
                                    Rp <?= number_format($room['harga_kamar'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="<?= base_url('admin/kamar/edit/' . $room['id_kamar']) ?>" class="inline-block bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 text-sm">Edit Tipe</a>
                                    
                                    <!-- PERBAIKAN: Tautan Hapus sekarang mengirim id_kamar dan memiliki URL yang lebih spesifik -->
                                    <a href="<?= base_url('admin/kamar/' . $room['id_kamar'] . '/delete') ?>"
                                       class="inline-block bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm"
                                       onclick="return confirm('PERINGATAN: Anda akan menghapus TIPE KAMAR \'<?= esc($room['tipe_kamar']) ?>\' dan SEMUA unit kamarnya. Lanjutkan?')">Hapus Tipe</a>
                                       
                                    <a href="<?= base_url('admin/kamar/' . $room['id_kamar']) ?>" class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Detail</a>
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
