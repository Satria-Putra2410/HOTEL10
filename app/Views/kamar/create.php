<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kamar Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    
    <?= $this->include('layouts/admin_navbar') ?>

    <div class="container mx-auto mt-8 p-5">
        <h2 class="text-3xl font-extrabold mb-6">Tambah Kamar Baru</h2>

        <?php if (session()->has('errors')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <strong class="font-bold">Gagal menyimpan data!</strong>
                <ul class="mt-2 list-disc list-inside">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <form action="<?= base_url('admin/kamar/create') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div>
                        <div class="mb-4">
                            <label for="tipe_kamar" class="block text-gray-700 text-sm font-bold mb-2">Tipe Kamar</label>
                            <input type="text" name="tipe_kamar" id="tipe_kamar" value="<?= old('tipe_kamar') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="harga_kamar" class="block text-gray-700 text-sm font-bold mb-2">Harga / Malam</label>
                            <input type="number" name="harga_kamar" id="harga_kamar" value="<?= old('harga_kamar') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="jenis_ranjang" class="block text-gray-700 text-sm font-bold mb-2">Jenis Ranjang</label>
                            <input type="text" name="jenis_ranjang" id="jenis_ranjang" placeholder="Contoh: 1 King Bed" value="<?= old('jenis_ranjang') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="jumlah_tamu" class="block text-gray-700 text-sm font-bold mb-2">Kapasitas Tamu</label>
                            <input type="number" name="jumlah_tamu" id="jumlah_tamu" value="<?= old('jumlah_tamu') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div>
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= old('deskripsi') ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="foto" class="block text-gray-700 text-sm font-bold mb-2">Foto Kamar</label>
                            <input type="file" name="foto" id="foto" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end">
                    <a href="<?= base_url('admin/kamar') ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan Kamar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>