<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Sepuluh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap');
    body {
        font-family: 'playfair display',serif;
    }
    
    .hero {
            height: 100vh;
            background-image: url('<?= base_url("assets/bandung.png") ?>'); 
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 10px black;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        .btn-link {
            width: 150px;
            height: 50px;
            display: flex;
            align-items: center;
            background: #e62222;
            border-radius: 5px;
            box-shadow: 1px 1px 3px rgba(0,0,0,0.15);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            padding-left: 15px;
        }

        .btn-link .text {
            transform: translateX(20px);
            color: white;
            font-weight: bold;
            transition: 200ms;
        }

        .btn-link .icon {
            position: absolute;
            border-left: 1px solid #c41b1b;
            right: 0;
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 200ms;
        }

        .btn-link svg {
            width: 15px;
            fill: #eee;
            transition: transform 200ms;
        }

        .btn-link:hover {
            background: #ff3636;
            text-decoration: none;
        }

        .btn-link:hover .text {
            color: transparent;
        }

        .btn-link:hover .icon {
            width: 150px;
            border-left: none;
            right: auto;
            left: 0;
        }

        .btn-link:active .icon svg {
            transform: scale(0.8);
        }

        .position-relative {
            animation: focus-in-contract 1.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) 1.5s both;
        }

        @keyframes focus-in-contract {
            0% {
                letter-spacing: 1em;
                filter: blur(12px);
                opacity: 0;
            }
            100% {
                filter: blur(0px);
                opacity: 1;
            }
        }

        .card {
            width: 250px;
            height: 350px;
            background: rgba(211, 211, 211, 0.199);
            position: absolute;
            transition: .3s ease-in-out;
            cursor: pointer;
            box-shadow: 0px 0px 30px -10px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        #c1 { background-image: url("assets/floating.jpg"); }
        #c2 { background-image: url("assets/musola.jpeg");}
        #c3 { background-image: url("assets/tangkuban.jpg");}
        #c4 { background-image: url("assets/tsm.jpg");}

        .main:hover #c1 { transform: translateX(-100px) rotate(-40deg); }
        .main:hover #c2 { transform: translateX(-50px) rotate(-30deg); }
        .main:hover #c3 { transform: translateX(0) rotate(-20deg); }
        .main:hover #c4 { transform: translateX(50px) rotate(-10deg); }

        #c1:hover { transform: translateX(-150px) rotate(0deg) !important; z-index: 5; }
        #c2:hover { transform: translateX(-100px) rotate(0deg) !important; z-index: 5; }
        #c3:hover { transform: translateX(-50px) rotate(0deg) !important; z-index: 5; }
        #c4:hover { transform: translateX(50px) rotate(0deg) !important; z-index: 5; }


        @keyframes floatIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .scroll-float { opacity: 0; transition: all 2s ease-out; }
        .scroll-float.show { animation: floatIn 2s ease forwards; }

        .art {
            background-size: cover;
            background-position: center;
            color: white;
            font-weight: bold;
            font-size: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
            border-radius: 0.5rem;
        }

        .wisata {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body class="bg-black text-white">

    <!-- Navbar -->
    <nav class="absolute w-full flex items-center justify-between px-6 py-4 z-50">
      <h1 class="text-2xl font-bold tracking-widest text-shadow">
        <span class="text-red-500 font-semibold">Hotel</span> Sepuluh
      </h1>
        <div class="lg:flex gap-4">
            <a class="btn-link" href="<?= base_url('login') ?>">
                <span class="text" style="font-family: 'lato',sans-serif;">Login</span>
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M0 32C0 14.3 14.3 0 32 0L480 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 384c17.7 0 32 14.3 32 32s-14.3 32-32 32l-176 0 0-48c0-26.5-21.5-48-48-48s-48 21.5-48 48l0 48L32 512c-17.7 0-32-14.3-32-32s14.3-32 32-32L32 64C14.3 64 0 49.7 0 32zm96 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zM240 96c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zM112 192c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM328 384c13.3 0 24.3-10.9 21-23.8c-10.6-41.5-48.2-72.2-93-72.2s-82.5 30.7-93 72.2c-3.3 12.8 7.8 23.8 21 23.8l144 0z"/></svg>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="text-center position-relative">
            <h1 class="text-4xl select-none font-bold">Hotel Sepuluh Bandung</h1>
            <p class="text-xl text-red-500 select-none ">A sanctuary of calm and luxury in Bandung</p>
        </div>
    </section>
<!-- Intro & Cards -->
<section class="py-16 px-4 bg-gray-900">
    <div class="flex flex-col lg:flex-row items-center gap-8">
        <!-- Bagian Kiri (Cards) -->
        <div class="w-full lg:w-1/2">
            <div class="main flex gap-4 relative h-[40vmax] justify-center items-center">
                <div class="card rounded-2xl" id="c1"></div>
                <div class="card rounded-2xl" id="c2"></div>
                <div class="card rounded-2xl" id="c3"></div>
                <div class="card rounded-2xl" id="c4"></div>
            </div>
        </div>

        <!-- Bagian Kanan (Text) -->
        <div class="w-full lg:w-1/2 scroll-float flex justify-center lg:justify-start">
            <div class="max-w-xl">
                <h1 class="text-3xl font-bold  text-white select-none mb-4">Hotel Sepuluh Bandung</h1>
                <p class="text-lg  text-red-400 select-none leading-relaxed" style="font-family: 'lato',sans-serif;">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt odio libero maxime reprehenderit velit
                    accusamus quos hic temporibus maiores placeat! Laboriosam eius, voluptatem culpa architecto ea sint
                    rerum porro aliquam.
                </p>
            </div>
        </div>
    </div>
</section>

    <div class="wisata flex justify-center items-center bg-gray-900 p-3.5 ">
        <h1 class="text-3xl scroll-float font-bold mb-4">Wisata Terdekat</h1>
    </div>
    <section class="h-screen px-10 py-10 flex items-center justify-center bg-gray-900">
      <div class="w-full scroll-float max-w-6xl">
        <div class="grid grid-cols-3 gap-4">
          <a href="floating">
          <div class="art bg-[url(assets/floating.jpg)] hover:opacity-55 duration-150 "></div>
          </a>
          <a href="ciwidey">
          <div class="art bg-[url(assets/ciwidey.jpg)] hover:opacity-55 duration-150"></div>
          </a>
          <a href="farmhouse">
          <div class="art bg-[url(assets/farmhouse.jpg)] hover:opacity-55 duration-150"></div>
          </a>
          <a href="tangkuban" class="col-span-2">
          <div class="art bg-[url(assets/tangkuban.jpg)] hover:opacity-55 duration-150"></div>
          </a>
          <a href="cukul">
          <div class="art bg-[url(assets/cukul.jpg)] hover:opacity-55 duration-150"></div>
          </a>
          <a href="tsm">
          <div class="art bg-[url(assets/tsm.jpg)] hover:opacity-55 duration-150"></div>
          </a>
          <a href="museum" class="col-span-2 ">
          <div class="art bg-[url(assets/museum.jpg)] hover:opacity-55 duration-150"></div>
          </a>
        </div>
      </div>
    </section>


    <section class="bg-gray-900 py-10">
        <h1 class="text-center text-xl font-bold mb-4" >TENTANG KAMI</h1>
    <div class="flex flex-col lg:flex-row gap-4 px-4 max-w-7xl mx-auto">
        
        <!-- Container kiri -->
        <div class="w-full lg:w-1/3 scroll-float bg-gray-900 text-red-400 p-6 rounded shadow">
        <div class="max-w-5xl mx-auto px-2 flex flex-col space-y-4">
            
            <!-- Container atas -->
            <div class="bg-gray-900 p-4 rounded shadow">
            <h1 class="text-xl font-bold text-center text-red-400 mb-2">Temui Kami</h1>
            <p style="font-family: 'lato',sans-serif;">Jl. Buah Batu No.81, Malabar, Kec. Lengkong, Kota Bandung, Jawa Barat</p>
            <p style="font-family: 'lato',sans-serif;">Telp: (022) 1234567</p>
            <p style="font-family: 'lato',sans-serif;">Email: info@hotelsepuluh.com</p>
            </div>

            <!-- Container bawah -->
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


        <!-- Container kanan -->
        <div class="w-full lg:w-2/3 scroll-float text-red-400  bg-gray-900 p-6 rounded shadow">
        <h2 class="text-xl font-bold text-center mb-4">Lokasi Kami</h2>
            <div class="w-full h-64 rounded overflow-hidden">
                <iframe class="h-full w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.634749821576!2d107.61782427356557!3d-6.934183967873822!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e88195d6aaab%3A0x792389b3a14079a1!2sHotel%20Sepuluh%20Buah%20Batu%20Bandung!5e0!3m2!1sen!2sid!4v1750495114135!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        </div>

    </div>
    </section>

      <!-- Footer -->
  <footer class="bg-black/50 backdrop-blur-sm mt-20 py-8">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <p class="text-gray-400">© 2024 Hotel Sepuluh Bandung. Semua hak cipta dilindungi.</p>
    </div>
  </footer>
  

<script>
    const scrollElements = document.querySelectorAll('.scroll-float');

    const elementInView = (el, offset = 100) => {
        const elementTop = el.getBoundingClientRect().top;
        return elementTop <= (window.innerHeight - offset);
    };

    const displayScrollElement = (element) => {
        element.classList.add('show');
    };

    const handleScrollAnimation = () => {
        scrollElements.forEach((el) => {
            if (elementInView(el)) {
                displayScrollElement(el);
            }
        });
    };

    window.addEventListener('scroll', handleScrollAnimation);
</script>
</body>
</html>
