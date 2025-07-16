<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hotel 10 Bandung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Playfair+Display:wght@400;700;900&display=swap');

    body {
      font-family: 'Lato', sans-serif;
    }

        .bgbandung {
        background-image: url('assets/ciwidey.jpg');
        background-size: cover;
        background-repeat: no-repeat;
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

        

    </style>
  </head>
  <body class="bg-gray-900">
      <nav class="absolute w-full flex items-center justify-between px-6 py-4 z-50">
        <a href="#" class="text-white text-xl font-bold">Hotel Sepuluh</a>
        <div class="lg:flex gap-4">
            <a class="btn-link" href="<?= base_url('/') ?>">
                <span class="text" style="font-family: 'lato',sans-serif;">Kembali</span>
                <span class="icon">  
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </a>
        </div>
    </nav>

    <section class="relative h-screen flex flex-col justify-center items-center">
      <div class="absolute inset-0 bgbandung bg-cover z-0"></div>

      <div class="relative z-10 w-full">
       
      </div>
    </section>

    <!-- Section ARTIKEL -->
     <article class="h-screen bg-gray-900 text-white py-10 px-6" style="font-family: 'lato',sans-serif;">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold mb-4 text-center text-red-500">Pesona Alam Ciwidey</h2>
            <p class="text-lg leading-relaxed mb-4">
            Ciwidey adalah kawasan wisata alam yang terletak di selatan Kota Bandung dan dikenal dengan keindahan alam pegunungan yang menakjubkan. Udara yang sejuk, hamparan kebun teh, serta pemandangan hijau yang memanjakan mata menjadikan Ciwidey sebagai destinasi favorit wisatawan lokal maupun mancanegara.
            </p>
            <p class="text-lg leading-relaxed mb-4">
            Beberapa tempat wisata populer di Ciwidey antara lain Kawah Putih dengan danau belerang berwarna toska yang ikonik, Ranca Upas yang menawarkan pengalaman berkemah dan memberi makan rusa, serta Glamping Lakeside yang menyajikan pengalaman menginap mewah di tengah alam. Tak ketinggalan, pemandian air panas Cimanggu dan Ciwalini yang cocok untuk relaksasi.
            </p>
            <p class="text-lg leading-relaxed mb-4">
             Bagi tamu Hotel Sepuluh, Ciwidey dapat dijangkau dalam waktu sekitar 1,5 jam berkendara. Perjalanan menuju Ciwidey akan memanjakan Anda dengan panorama alam yang memukau, menjadikannya pilihan sempurna untuk berlibur bersama keluarga atau pasangan. Jangan lewatkan pengalaman tak terlupakan menjelajahi keindahan alam Ciwidey yang memikat.
            </p>
        </div>
     </article>


    <section class="bg-gray-900 py-10">
        <h1 class="text-center text-xl font-bold mb-4 text-white" >TENTANG KAMI</h1>
    <div class="flex flex-col lg:flex-row gap-4 px-4 max-w-7xl mx-auto">
        
        <!-- Container kiri -->
        <div class="w-full lg:w-1/3 scroll-float bg-gray-900 text-white p-6 rounded shadow">
        <div class="max-w-5xl mx-auto px-2 flex flex-col space-y-4">
            
            <!-- Container atas -->
            <div class="bg-gray-900 p-4 rounded shadow">
            <h1 class="text-xl font-bold text-center text-white mb-2">Temui Kami</h1>
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
        <div class="w-full lg:w-2/3 scroll-float text-white  bg-gray-900 p-6 rounded shadow">
        <h2 class="text-xl font-bold text-center mb-4">Lokasi Kami</h2>
            <div class="w-full h-64 rounded overflow-hidden">
                <iframe class="h-full w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.634749821576!2d107.61782427356557!3d-6.934183967873822!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e88195d6aaab%3A0x792389b3a14079a1!2sHotel%20Sepuluh%20Buah%20Batu%20Bandung!5e0!3m2!1sen!2sid!4v1750495114135!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        </div>

    </div>
    </section>



  </body>
</html>
