<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap');
        body {
            font-family: "Montserrat", sans-serif;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-cover bg-center bg-gray-900 p-4">

    <!-- Menampilkan Error Validasi -->
    <?php if (session()->has('errors')): ?>
        <div class="w-full max-w-md bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Oops! Registrasi Gagal.</strong>
            <ul class="mt-2 list-disc list-inside">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="w-full max-w-md bg-white/30 backdrop-blur-md rounded-xl p-8">
        <h1 class="text-3xl font-extrabold text-center mb-4">Register</h1>

        <form method="post" action="<?= base_url('register') ?>">
            <?= csrf_field() ?>

            <!-- PERUBAHAN: name="nama" menjadi name="nama_tamu" -->
            <div class="relative w-full h-12 my-5">
                <input type="text" name="nama_tamu" id="nama_tamu" placeholder="Nama Lengkap" required value="<?= old('nama_tamu') ?>"
                    class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-gray-800 focus:outline-none">
                <i class='bx bxs-user absolute right-5 top-1/2 -translate-y-1/2 text-xl'></i>
            </div>

            <!-- PERUBAHAN: name="notelp" menjadi name="no_hp_tamu" -->
            <div class="relative w-full h-12 my-5">
                <input type="tel" name="no_hp_tamu" id="no_hp_tamu" placeholder="Nomor Telepon" required value="<?= old('no_hp_tamu') ?>"
                    class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-gray-800 focus:outline-none">
                <i class='bx bxs-phone absolute right-5 top-1/2 -translate-y-1/2 text-xl'></i>
            </div>

            <!-- Email (Tidak berubah) -->
            <div class="relative w-full h-12 my-5">
                <input type="email" name="email" id="email" placeholder="Email Aktif" required value="<?= old('email') ?>"
                    class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-gray-800 focus:outline-none">
                <i class='bx bx-envelope absolute right-5 top-1/2 -translate-y-1/2 text-xl'></i>
            </div>

            <!-- Password -->
            <div class="relative w-full h-12 my-5">
                <input type="password" name="password" id="password" placeholder="Password (Min. 8 Karakter)" required
                    class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-gray-800 focus:outline-none">
                <i class='bx bxs-lock absolute right-5 top-1/2 -translate-y-1/2 text-xl'></i>
            </div>
            
            <!-- Konfirmasi Password -->
            <div class="relative w-full h-12 my-5">
                <input type="password" name="password_confirm" id="password_confirm" placeholder="Konfirmasi Password" required
                    class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-gray-800 focus:outline-none">
                <i class='bx bxs-lock-alt absolute right-5 top-1/2 -translate-y-1/2 text-xl'></i>
            </div>

            <button type="submit"
                class="w-full h-11 rounded-full bg-gray-100 text-gray-800 font-medium shadow-md hover:bg-red-600 hover:text-white hover:font-semibold transition duration-500">Register</button>
        </form>

        <p class="text-center pt-6">Sudah punya akun? <a href="<?= base_url('login') ?>" class="text-blue-800 font-semibold">Login di sini</a></p>
    </div>

</body>
</html>
