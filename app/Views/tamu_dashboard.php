<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard Tamu</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(220, 38, 38, 0.2);
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

        .hero-gradient {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.8) 0%, rgba(0, 0, 0, 0.8) 100%);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .glow-effect {
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.3);
        }

        .btn-logout {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-logout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-logout:hover::before {
            left: 100%;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }

        .welcome-text {
            background: linear-gradient(135deg, #ffffff, #f3f4f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-overlay {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.2) 0%, rgba(0, 0, 0, 0.7) 100%);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(55, 65, 81, 0.3) 100%);
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .icon-glow {
            filter: drop-shadow(0 0 10px rgba(220, 38, 38, 0.5));
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .card-pattern {
            position: relative;
        }

        .card-pattern::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(220, 38, 38, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
    </style>
</head> 
<body class="gradient-bg text-white min-h-screen">

    <nav class="nav-glass p-4 shadow-2xl sticky top-0 z-50">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold tracking-widest text-shadow">
                <span class="text-red-500">Hotel</span> Sepuluh
            </h1>
            <div class="flex items-center space-x-4">
                <div class="hidden md:block">
                    <span class="mr-4 welcome-text font-semibold">Selamat Datang, <strong class="text-red-400"><?= session()->get('nama') ?></strong>!</span>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn-logout text-white font-bold py-2 px-6 rounded-lg shadow-lg">
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Logout</span>
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <section class="relative h-[50vh] bg-cover bg-center overflow-hidden" style="background-image: url('<?= base_url("assets/bandung.png") ?>');">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-black/60"></div>
        
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-2 h-2 bg-red-500 rounded-full opacity-60 pulse-animation"></div>
            <div class="absolute top-32 right-32 w-1 h-1 bg-white rounded-full opacity-40 pulse-animation" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-40 left-40 w-3 h-3 bg-red-400 rounded-full opacity-30 pulse-animation" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="text-center max-w-4xl px-6">
                <h2 class="text-5xl md:text-6xl font-bold mb-6 text-shadow">
                    <span class="block floating-icon">Dashboard</span>
                    <span class="text-red-400">Tamu</span>
                </h2>
                <p class="text-xl md:text-2xl text-shadow opacity-90">Kelola reservasi dan informasi Anda dengan mudah</p>
                <div class="mt-8">
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="stats-card p-6 rounded-xl text-center scroll-float">
                    <div class="text-red-400 text-3xl font-bold">24/7</div>
                    <div class="text-gray-300 font-semibold">Layanan Tersedia</div>
                </div>
                <div class="stats-card p-6 rounded-xl text-center scroll-float">
                    <div class="text-red-400 text-3xl font-bold">100+</div>
                    <div class="text-gray-300 font-semibold">Kamar Tersedia</div>
                </div>
                <div class="stats-card p-6 rounded-xl text-center scroll-float">
                    <div class="text-red-400 text-3xl font-bold">★ 4.9</div>
                    <div class="text-gray-300 font-semibold">Rating Kepuasan</div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-12">
        <h2 class="text-4xl font-bold text-center mb-4 scroll-float text-shadow">
            <span class="text-red-400">Akses</span> Cepat
        </h2>
        <p class="text-center text-gray-300 mb-16 text-lg scroll-float">Pilih layanan yang Anda butuhkan dengan sekali klik</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <a href="#booking-form-section" class="scroll-float card-hover glass-effect p-8 rounded-2xl shadow-2xl card-pattern group">
                <div class="flex items-center gap-6 mb-4">
                    <div class="p-4 bg-red-600/20 rounded-2xl group-hover:bg-red-600/30 transition-all duration-300">
                        <svg class="w-10 h-10 fill-red-400 icon-glow floating-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path d="M96 224H544c8.8 0 16 7.2 16 16v240c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V240c0-8.8 7.2-16 16-16zM544 192H96c-26.5 0-48 21.5-48 48v240c0 26.5 21.5 48 48 48H544c26.5 0 48-21.5 48-48V240c0-26.5-21.5-48-48-48zM352 96c0-17.7-14.3-32-32-32s-32 14.3-32 32v64h64V96z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-shadow group-hover:text-red-300 transition-colors">Pesan Kamar</h3>
                        <div class="w-12 h-1 bg-red-500 rounded-full mt-2 group-hover:w-20 transition-all duration-300"></div>
                    </div>
                </div>
                <p class="text-gray-300 text-lg leading-relaxed">Lihat ketersediaan dan lakukan reservasi sekarang dengan sistem booking yang mudah dan cepat.</p>
                <div class="mt-6 flex items-center text-red-400 group-hover:text-red-300 transition-colors">
                    <span class="font-semibold">Mulai Booking</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </a>

            <a href="<?= base_url('tamu/riwayat-reservasi') ?>" class="scroll-float card-hover glass-effect p-8 rounded-2xl shadow-2xl card-pattern group">
                <div class="flex items-center gap-6 mb-4">
                    <div class="p-4 bg-red-600/20 rounded-2xl group-hover:bg-red-600/30 transition-all duration-300">
                        <svg class="w-10 h-10 fill-red-400 icon-glow floating-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="animation-delay: 0.5s;">
                            <path d="M496 64H416V32c0-17.7-14.3-32-32-32H128C110.3 0 96 14.3 96 32V64H16C7.2 64 0 71.2 0 80v16c0 8.8 7.2 16 16 16H32v320c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V112h16c8.8 0 16-7.2 16-16V80c0-8.8-7.2-16-16-16zM128 32H384V64H128V32zM416 464H96c-17.7 0-32-14.3-32-32V112H448V432c0 17.7-14.3 32-32 32z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-shadow group-hover:text-red-300 transition-colors">Riwayat Reservasi</h3>
                        <div class="w-12 h-1 bg-red-500 rounded-full mt-2 group-hover:w-20 transition-all duration-300"></div>
                    </div>
                </div>
                <p class="text-gray-300 text-lg leading-relaxed">Lihat semua histori pemesanan Anda sebelumnya dengan detail lengkap dan status terkini.</p>
                <div class="mt-6 flex items-center text-red-400 group-hover:text-red-300 transition-colors">
                    <span class="font-semibold">Lihat Riwayat</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </a>

            <a href="<?= base_url('tamu/profil') ?>" class="scroll-float card-hover glass-effect p-8 rounded-2xl shadow-2xl card-pattern group">
                <div class="flex items-center gap-6 mb-4">
                    <div class="p-4 bg-red-600/20 rounded-2xl group-hover:bg-red-600/30 transition-all duration-300">
                        <svg class="w-10 h-10 fill-red-400 icon-glow floating-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="animation-delay: 1s;">
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zM313.6 288H134.4C60.2 288 0 348.2 0 422.4C0 456.7 27.3 480 61.6 480H386.4c34.3 0 61.6-23.3 61.6-57.6C448 348.2 387.8 288 313.6 288z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-shadow group-hover:text-red-300 transition-colors">Edit Profil</h3>
                        <div class="w-12 h-1 bg-red-500 rounded-full mt-2 group-hover:w-20 transition-all duration-300"></div>
                    </div>
                </div>
                <p class="text-gray-300 text-lg leading-relaxed">Perbarui nama, email, password, dan informasi pribadi lainnya untuk pengalaman yang lebih personal.</p>
                <div class="mt-6 flex items-center text-red-400 group-hover:text-red-300 transition-colors">
                    <span class="font-semibold">Edit Sekarang</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </a>
        </div>
    </section>

    <section id="booking-form-section" class="max-w-4xl mx-auto px-6 py-12">
        <h2 class="text-4xl font-bold text-center mb-8 scroll-float text-shadow">
            <span class="text-red-400">Pesan</span> Kamar
        </h2>
        <div class="glass-effect p-8 rounded-2xl shadow-2xl scroll-float">
            <form id="bookingForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="checkin_date" class="block text-gray-300 text-sm font-bold mb-2">Tanggal Check-in:</label>
                    <input type="date" id="checkin_date" name="tgl_masuk" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>
                <div>
                    <label for="checkout_date" class="block text-gray-300 text-sm font-bold mb-2">Tanggal Check-out:</label>
                    <input type="date" id="checkout_date" name="tgl_keluar" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>
                <div>
                    <label for="num_guests" class="block text-gray-300 text-sm font-bold mb-2">Jumlah Tamu:</label>
                    <input type="number" id="num_guests" name="jumlah_tamu" min="1" value="1" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
                        Cari Kamar Tersedia
                    </button>
                </div>
            </form>

            <div id="searchResults" class="mt-8">
                <h3 class="text-2xl font-bold text-red-400 mb-4">Hasil Pencarian Kamar:</h3>
                <div id="roomList" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <p class="text-gray-400 text-center md:col-span-2">Silakan masukkan tanggal check-in, check-out, dan jumlah tamu untuk mencari kamar.</p>
                </div>
                <p id="noRoomsFound" class="hidden text-gray-400 text-center md:col-span-2 mt-4">Tidak ada kamar tersedia untuk kriteria yang Anda pilih.</p>
                <div id="loadingIndicator" class="hidden text-center text-red-400 mt-4">
                    <svg class="animate-spin h-8 w-8 text-red-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p>Mencari kamar...</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-900 py-10">
        <h1 class="text-center text-xl font-bold mb-4" >TENTANG KAMI</h1>
        <div class="flex flex-col lg:flex-row gap-4 px-4 max-w-7xl mx-auto">
            
            <div class="w-full lg:w-1/3 scroll-float bg-gray-900 text-red-400 p-6 rounded shadow">
                <div class="max-w-5xl mx-auto px-2 flex flex-col space-y-4">
                    
                    <div class="bg-gray-900 p-4 rounded shadow">
                        <h1 class="text-xl font-bold text-center text-red-400 mb-2">Temui Kami</h1>
                        <p style="font-family: 'lato',sans-serif;">Jl. Buah Batu No.81, Malabar, Kec. Lengkong, Kota Bandung, Jawa Barat</p>
                        <p style="font-family: 'lato',sans-serif;">Telp: (022) 1234567</p>
                        <p style="font-family: 'lato',sans-serif;">Email: info@hotelsepuluh.com</p>
                    </div>

                    <div class="bg-gray-900 p-2 rounded shadow">
                        <div class="flex justify-center gap-9 ">
                            <a href="https://instagram.com" target="_blank">
                                <img src="<?= base_url('icon/Instagram_Glyph_Gradient.png') ?>" alt="Instagram" class="w-8 h-8 hover:opacity-80 hover:animate-bounce">
                            </a>
                            <a href="https://tiktok.com" target="_blank">
                                <img src="<?= base_url('icon/TikTok-logo-RGB-Stacked-white.png') ?>" alt="TikTok" class="w-8 h-8 hover:opacity-80 hover:animate-bounce">
                            </a>
                            <a href="https://whatsapp.com" target="_blank">
                                <img src="<?= base_url('icon/Digital_Glyph_Green.png') ?>" alt="WhatsApp" class="w-8 h-8 hover:opacity-80 hover:animate-bounce">
                            </a>
                            <a href="https://x.com" target="_blank">
                                <img src="<?= base_url('icon/logo-white.png') ?>" alt="WhatsApp" class="w-8 h-8 hover:opacity-80 hover:animate-bounce">
                            </a>
                            <a href="https://facebook.com" target="_blank">
                                <img src="<?= base_url('icon/Facebook_Logo_Primary.png') ?>" alt="WhatsApp" class="w-8 h-8 hover:opacity-80 hover:animate-bounce">
                            </a>
                        </div>
                    </div>

                </div>
            </div>


            <div class="w-full lg:w-2/3 scroll-float text-red-400  bg-gray-900 p-6 rounded shadow">
                <h2 class="text-xl font-bold text-center mb-4">Lokasi Kami</h2>
                <div class="w-full h-64 rounded overflow-hidden">
                    <iframe class="h-full w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.634749821576!2d107.61782427356557!3d-6.934183967873822!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e88195d6aaab%3A0x792389b3a14079a1!2sHotel%20Sepuluh%20Buah%20Batu%20Bandung!5e0!3m2!1sen!2sid!4v1750495114135!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

        </div>
    </section>


    <footer class="bg-black/50 backdrop-blur-sm mt-20 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-gray-400">© 2024 Hotel Sepuluh Bandung. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <script>
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

        // --- Booking Form Logic ---
        document.addEventListener('DOMContentLoaded', () => {
            const bookingForm = document.getElementById('bookingForm');
            const checkinDateInput = document.getElementById('checkin_date');
            const checkoutDateInput = document.getElementById('checkout_date');
            const roomListDiv = document.getElementById('roomList');
            const noRoomsFound = document.getElementById('noRoomsFound');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Set min date for check-in to today
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            const todayISO = today.toISOString().split('T')[0];
            const tomorrowISO = tomorrow.toISOString().split('T')[0];
            checkinDateInput.min = todayISO;
            checkoutDateInput.min = tomorrowISO;

            checkinDateInput.addEventListener('change', () => {
                if (checkinDateInput.value) {
                    const checkinDate = new Date(checkinDateInput.value);
                    const minCheckoutDate = new Date(checkinDate);
                    minCheckoutDate.setDate(minCheckoutDate.getDate() + 1);
                    checkoutDateInput.min = minCheckoutDate.toISOString().split('T')[0];
                    
                    // If checkout date is before or same as new checkin date, reset it
                    if (checkoutDateInput.value && new Date(checkoutDateInput.value) <= checkinDate) {
                        checkoutDateInput.value = minCheckoutDate.toISOString().split('T')[0];
                    }
                } else {
                    checkoutDateInput.min = tomorrowISO;
                }
            });


            bookingForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                roomListDiv.innerHTML = ''; // Clear previous results
                noRoomsFound.classList.add('hidden'); // Hide "no rooms found" message
                loadingIndicator.classList.remove('hidden'); // Show loading indicator

                const checkinDate = checkinDateInput.value;
                const checkoutDate = checkoutDateInput.value;
                const numGuests = document.getElementById('num_guests').value;

                if (!checkinDate || !checkoutDate || !numGuests) {
                    alert('Mohon lengkapi semua kolom tanggal dan jumlah tamu.');
                    loadingIndicator.classList.add('hidden');
                    return;
                }

                // Client-side date validation
                if (new Date(checkinDate) >= new Date(checkoutDate)) {
                    alert('Tanggal Check-out harus setelah Tanggal Check-in.');
                    loadingIndicator.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch('<?= base_url('tamu/check-room-availability') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            tgl_masuk: checkinDate,
                            tgl_keluar: checkoutDate,
                            jumlah_tamu: numGuests
                        })
                    });

                    const data = await response.json();
                    loadingIndicator.classList.add('hidden'); // Hide loading indicator

                    if (data.status === 'success' && data.rooms.length > 0) {
                        data.rooms.forEach(room => {
                            const roomCard = `
                                <div class="glass-effect p-6 rounded-xl shadow-lg flex flex-col gap-4">
                                    ${room.foto ? `<img src="<?= base_url('uploads/kamar/') ?>${room.foto}" alt="${room.tipe_kamar}" class="w-full h-48 object-cover rounded-lg mb-4">` : `<div class="w-full h-48 bg-gray-700 flex items-center justify-center rounded-lg mb-4 text-gray-400">Tidak ada foto</div>`}
                                    <h4 class="text-xl font-bold text-red-300">${room.tipe_kamar} - Kamar No. ${room.nomor_kamar}</h4>
                                    <p class="text-gray-300">Jenis Ranjang: ${room.jenis_ranjang}</p>
                                    <p class="text-gray-300">Kapasitas: ${room.jumlah_tamu} Tamu</p>
                                    <p class="text-gray-300">Deskripsi: ${room.deskripsi || 'Tidak ada deskripsi.'}</p>
                                    <p class="text-2xl font-bold text-red-400 mt-auto">Rp ${parseFloat(room.harga_kamar).toLocaleString('id-ID')}</p>
                                    <button class="book-room-btn w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out"
                                            data-id-kamar="${room.id_kamar}"
                                            data-tipe-kamar="${room.tipe_kamar}"
                                            data-nomor-kamar="${room.nomor_kamar}">
                                        Pesan Kamar Ini
                                    </button>
                                </div>
                            `;
                            roomListDiv.insertAdjacentHTML('beforeend', roomCard);
                        });

                        // Add event listeners for booking buttons
                        document.querySelectorAll('.book-room-btn').forEach(button => {
                            button.addEventListener('click', async (event) => {
                                const idKamar = event.target.dataset.idKamar;
                                const tipeKamar = event.target.dataset.tipeKamar;
                                const nomorKamar = event.target.dataset.nomorKamar;

                                if (!confirm(`Anda yakin ingin memesan kamar ${tipeKamar} No. ${nomorKamar} dari ${checkinDate} sampai ${checkoutDate}?`)) {
                                    return;
                                }

                                try {
                                    const reservationResponse = await fetch('<?= base_url('tamu/create-reservation') ?>', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        body: JSON.stringify({
                                            id_kamar: idKamar,
                                            tgl_masuk: checkinDate,
                                            tgl_keluar: checkoutDate
                                        })
                                    });

                                    const reservationData = await reservationResponse.json();

                                    if (reservationResponse.ok && reservationData.status === 'success') {
                                        alert('Pemesanan berhasil! ID Reservasi Anda: ' + reservationData.id_reservasi);
                                        window.location.reload(); // Reload dashboard to reflect changes
                                    } else {
                                        // Display server-side error message
                                        alert('Pemesanan gagal: ' + (reservationData.message || 'Terjadi kesalahan pada server.'));
                                    }

                                } catch (error) {
                                    console.error('Error during reservation:', error);
                                    alert('Terjadi kesalahan saat memesan kamar.');
                                }
                            });
                        });

                    } else {
                        noRoomsFound.classList.remove('hidden'); // Show "no rooms found"
                    }

                } catch (error) {
                    console.error('Error fetching room availability:', error);
                    loadingIndicator.classList.add('hidden');
                    roomListDiv.innerHTML = '<p class="text-red-500 text-center md:col-span-2">Terjadi kesalahan saat mencari kamar. Silakan coba lagi.</p>';
                }
            });
        });
    </script>

</body>
</ht