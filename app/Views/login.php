<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap');

        body {
            font-family: "Montserrat", sans-serif;
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

        .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-cover bg-center bg-gray-900">

    <?php if (session()->getFlashdata('error')): ?>
        <p class="text-red-500"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <div class="w-[420px] bg-white/30 backdrop-blur-md rounded-xl p-8">
        <a href="<?= base_url('/') ?>">
            <button type="button" aria-label="Close" class="float-right text-black text-4xl">&times;</button>
        </a>
        <h1 class="text-3xl font-extrabold text-center">Login</h1>
        <form action="<?= base_url('login') ?>" method="POST" class="mt-6">
            <?= csrf_field() ?>

            <div class="relative w-full h-12 my-6">
                <input type="email" name="email" id="email" placeholder="Email" required
                    class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-white focus:outline-none">
                <i class='bx bxs-user absolute right-5 top-1/2 -translate-y-1/2 text-xl'></i>
            </div>

            <div class="relative w-full h-12 my-6">
                <input type="password" name="password" id="password" placeholder="Password" required
                    class="w-full h-full bg-transparent border-2 border-white/20 rounded-full text-black px-5 placeholder-white focus:outline-none">
                <i class='bx bxs-lock absolute right-5 top-1/2 -translate-y-1/2 text-xl'></i>
            </div>

            <button type="submit"
                class="w-full h-11 rounded-full bg-gray-100 text-gray-800 font-medium shadow-md hover:bg-red-600 hover:text-white hover:font-semibold transition duration-500">Login</button>
        </form>

        <a href="<?= base_url('register') ?>"
            class="block  text-center mt-4 w-full h-11 bg-gray-100 rounded-full shadow-md text-gray-800 font-medium hover:bg-black hover:text-white hover:font-semibold transition duration-500">Register</a>
    </div>

    <div id="logoutPopup" class="popup">
        <div class="popup-content">
            <p>Email atau password yang anda masukkan salah</p>
            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded" onclick="closeLogoutPopup()">Kembali</button>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("logoutPopup").style.display = "flex";
            });
        </script>
    <?php endif; ?>

    <script>
        function closeLogoutPopup() {
            document.getElementById("logoutPopup").style.display = "none";
        }
    </script>
</body>

</html>
