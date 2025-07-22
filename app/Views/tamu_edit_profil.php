<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <!-- Anda bisa menambahkan link ke file CSS di sini untuk styling -->
     <script src="https://cdn.tailwindcss.com"></script>

     <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Playfair+Display:wght@400;700;900&display=swap');

        .errors { color: #dc3545; list-style-type: none; padding: 0; }
        .success { color: #28a745; }

        .gradient-bg {
            background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
        }

        .lato {
            font-family: 'lato','sans-serif';
        }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            color: #000000;
        }

        .popup-content{
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }
    
    
        
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-cover bg-center bg-gray-900">
    <div class="w-[420px] bg-white/30 backdrop-blur-md rounded-xl p-8 lato">
        <h2 class="text-3xl font-bold text-center">Edit Profil</h2>
        <p class="text-center">Perbarui informasi pribadi Anda di bawah ini.</p>
        <br>


        <!-- Menampilkan pesan sukses jika ada -->
        <?php if (session()->getFlashdata('success')): ?>
            <p class="success"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>

        <!-- Menampilkan error validasi jika ada -->
        <?php $errors = session()->getFlashdata('errors'); ?>
        <?php if (!empty($errors)): ?>
            <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    <form class="w-full h-full bg-white/10 backdrop-blur-md rounded-xl p-3 lato" action="<?= site_url('tamu/profil') ?>" method="post">
        
        <?= csrf_field() ?>

        <div class="relative w-full h-12 my-6 p-2.5">
            <label class="text-xl text-gray-900 text-semibold" for="nama_tamu">Nama Lengkap</label><br>
            <!-- PERBAIKAN: name="nama_tamu" dan mengambil old input -->
            <input type="text" name="nama_tamu" id="nama_tamu" value="<?= old('nama_tamu', esc($tamu['nama_tamu'] ?? '')) ?>" class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-white focus:outline-none">
        </div>

        <div class="relative w-full h-12 my-6 p-2.5">
            <label class="text-xl text-gray-900 text-semibold" for="no_hp_tamu">Nomor HP</label><br>
            <!-- PERBAIKAN: Menambahkan field no_hp_tamu -->
            <input type="text" name="no_hp_tamu" id="no_hp_tamu" value="<?= old('no_hp_tamu', esc($tamu['no_hp_tamu'] ?? '')) ?>" class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-white focus:outline-none">
        </div>

        <div class="relative w-full h-12 my-6 p-2.5">
            <label class="text-xl text-gray-900 text-semibold" for="email">Email</label><br>
            <!-- PERBAIKAN: mengambil old input -->
            <input type="email" name="email" id="email" value="<?= old('email', esc($tamu['email'] ?? '')) ?>" class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-white focus:outline-none">
        </div>
        
        <div class="relative w-full h-12 my-6 p-2.5">
            <label class="text-xl text-gray-900 text-semibold" for="password">Password Baru (opsional)</label><br>
            <input type="password" name="password" id="password" class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-white focus:outline-none">
            <small>Kosongkan jika tidak ingin mengubah password.</small>
        </div>
        <br>

        <div class="p-1">
            <button class="w-full bg-red-500 hover:bg-red-700 transition-colors text-white px-4 py-2 rounded" type="submit">Simpan Perubahan</button>
        </div>
        <div class="p-1">
        <button href="<?= base_url('tamu/dashboard')?>" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded" type="submit">Kembali</button>
        </div>
    </form>
    </div>
</div>
</body>
</html>
