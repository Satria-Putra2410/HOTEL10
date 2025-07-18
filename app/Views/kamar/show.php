<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Detail Kamar') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    
    <?= $this->include('layouts/admin_navbar') ?>

    <div class="container mx-auto mt-8 p-5">
        <h2 class="text-3xl font-extrabold mb-6">Detail Kamar: <?= esc($room['tipe_kamar']) ?></h2>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri - Informasi Kamar -->
                <div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Kamar:</label>
                        <p class="text-lg"><?= esc($room['tipe_kamar']) ?></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga / Malam:</label>
                        <p class="text-lg">Rp <?= number_format(esc($room['harga_kamar']), 0, ',', '.') ?></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Ranjang:</label>
                        <p class="text-lg"><?= esc($room['jenis_ranjang']) ?></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kapasitas Tamu:</label>
                        <p class="text-lg"><?= esc($room['jumlah_tamu']) ?> Orang</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                        <p class="text-lg leading-relaxed"><?= nl2br(esc($room['deskripsi'])) ?></p>
                    </div>
                </div>
                <!-- Kolom Kanan - Foto Kamar -->
                <div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Kamar:</label>
                        <?php if ($room['foto']): ?>
                            <img src="<?= base_url('uploads/kamar/' . esc($room['foto'])) ?>" alt="Foto Kamar <?= esc($room['tipe_kamar']) ?>" class="w-full h-64 object-cover rounded-lg shadow-md">
                        <?php else: ?>
                            <p class="text-gray-500">Tidak ada foto tersedia.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end">
                <a href="<?= base_url('admin/kamar/edit/' . $room['id_kamar']) ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mr-2">Edit Kamar</a>
                <a href="<?= base_url('admin/kamar') ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Kembali ke Daftar Kamar</a>
            </div>
        </div>
    </div>
</body>
</html>
