<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    
    <?= $this->include('layouts/admin_navbar') ?>

    <div class="container mx-auto mt-8 p-5">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold">Manajemen Kamar</h2>
            <a href="<?= base_url('admin/kamar/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                + Tambah Kamar
            </a>
        </div>

        <!-- ====================================================== -->
        <!-- PERBAIKAN DI SINI: Menambahkan blok untuk pesan error -->
        <!-- ====================================================== -->
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
        <!-- ====================================================== -->

        <!-- Tabel Kamar -->
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe Kamar</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga/Malam</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rooms)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-500">Belum ada data kamar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rooms as $room): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <img src="<?= base_url('uploads/kamar/' . $room['foto']) ?>" alt="<?= esc($room['tipe_kamar']) ?>" class="w-24 h-16 object-cover rounded">
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm"><p class="text-gray-900 whitespace-no-wrap font-semibold"><?= esc($room['tipe_kamar']) ?></p></td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm"><p class="text-gray-900 whitespace-no-wrap">Rp <?= number_format($room['harga_kamar'], 0, ',', '.') ?></p></td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                <a href="<?= base_url('admin/kamar/edit/' . $room['id_kamar']) ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="<?= base_url('admin/kamar/delete/' . $room['id_kamar']) ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Anda yakin ingin menghapus kamar ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>