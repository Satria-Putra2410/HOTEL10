<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
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

        .gradient-bg {
            background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .scroll-float {
            opacity: 0;
            transition: all 1.5s ease-out;
        }

        .scroll-float.show {
            animation: floatIn 1.2s ease forwards;
        }

        @keyframes floatIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 0.75rem;
            color: #6ee7b7;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(185, 28, 28, 0.1));
            border: 1px solid rgba(220, 38, 38, 0.3);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 0.75rem;
            color: #f87171;
            margin-bottom: 1.5rem;
        }

        .text-shadow {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full glass-effect p-8 rounded-2xl scroll-float">
        <h2 class="text-4xl font-bold mb-2 text-center text-shadow text-red-400">Edit Profil</h2>
        <p class="text-center text-gray-300 mb-6">Perbarui informasi pribadi Anda di bawah ini.</p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <i class='bx bx-check-circle mr-2'></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors'); ?>
        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <ul class="list-disc ml-5">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('tamu/profil') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>

            <div>
                <label for="nama_tamu" class="block text-sm font-semibold text-gray-300 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_tamu" id="nama_tamu"
                       value="<?= old('nama_tamu', esc($tamu['nama_tamu'] ?? '')) ?>"
                       class="w-full px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>

            <div>
                <label for="no_hp_tamu" class="block text-sm font-semibold text-gray-300 mb-1">Nomor HP</label>
                <input type="text" name="no_hp_tamu" id="no_hp_tamu"
                       value="<?= old('no_hp_tamu', esc($tamu['no_hp_tamu'] ?? '')) ?>"
                       class="w-full px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
                <input type="email" name="email" id="email"
                       value="<?= old('email', esc($tamu['email'] ?? '')) ?>"
                       class="w-full px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-300 mb-1">Password Baru (opsional)</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400">
                <small class="text-gray-400">Kosongkan jika tidak ingin mengubah password.</small>
            </div>

            <div class="flex flex-col space-y-3 mt-6">
                <button type="submit" class="bg-red-600 hover:bg-red-700 transition duration-300 font-bold py-2 rounded-xl text-white">
                    Simpan Perubahan
                </button>
                <a href="<?= base_url('tamu/dashboard') ?>" class="bg-gray-700 hover:bg-gray-800 transition duration-300 text-white text-center py-2 rounded-xl">
                    Kembali
                </a>
            </div>
        </form>
    </div>

    <script>
        // Scroll animation
        const scrollElements = document.querySelectorAll('.scroll-float');
        const elementInView = (el, offset = 100) => el.getBoundingClientRect().top <= (window.innerHeight - offset);
        const displayScrollElement = (element) => element.classList.add('show');
        const handleScrollAnimation = () => {
            scrollElements.forEach(el => {
                if (elementInView(el)) displayScrollElement(el);
            });
        };
        window.addEventListener('scroll', handleScrollAnimation);
        window.addEventListener('load', handleScrollAnimation);

        // Auto-hide alerts
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
