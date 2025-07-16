<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hotel 10 Bandung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .bgbandung {
        background-image: url('assets/bandung.png');
        background-size: cover;
        background-position: bottom;
        background-repeat: no-repeat;
      }
    </style>
  </head>
  <body class="bg-amber-100">

    <!-- Section PESANAN -->
    <section class="relative h-screen flex flex-col justify-center items-center">
      <div class="absolute inset-0 bgbandung bg-fixed z-0"></div>

      <div class="relative z-10 w-full">
        <div class="mx-auto box-border bg-amber-200 bg-opacity-90 p-10 w-3/4 max-w-4xl text-gray-800 text-center shadow-lg rounded-xl">

          <h1 class="text-3xl font-bold mb-6">Pesan Kamar Anda</h1>

          <!-- Form Check-in / Check-out -->
          <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Check-in -->
            <div class="flex flex-col text-left">
              <label for="checkin" class="mb-2 font-semibold">Check-in</label>
              <input type="date" id="checkin" name="checkin"
                class="p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <!-- Check-out -->
            <div class="flex flex-col text-left">
              <label for="checkout" class="mb-2 font-semibold">Check-out</label>
              <input type="date" id="checkout" name="checkout"
                class="p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>

            <!-- Tipe Kamar -->
            <div class="flex flex-col text-left md:col-span-2">
              <label for="tipe" class="mb-2 font-semibold">Tipe Kamar</label>
              <select id="tipe" name="tipe"
                class="p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                <option disabled selected>Pilih tipe kamar</option>
                <option value="VIP">VIP</option>
                <option value="Regular">Regular</option>
              </select>
            </div>

            <!-- Tombol -->
            <div class="col-span-2 text-center mt-4">
              <button type="submit"
                class="rounded-md w-60 h-10 bg-red-500 text-white font-semibold hover:bg-red-600 transition">
                Cari Kamar
              </button>
            </div>
          </form>

        </div>
      </div>
    </section>

    <!-- Section ARTIKEL -->
    <div class="wisata flex justify-center items-center font-bold bg-amber-300 p-3.5 ">
        <h1 class="text-3xl font-bold mb-6">Wisata Terdekat</h1>
    </div>
    <section class="h-screen px-10 py-10 flex items-center justify-center bg-amber-300">
      <div class="w-full max-w-6xl">
        <div class="grid grid-cols-3 gap-4">
          <div class="art bg-[url(assets/floating.jpg)]"></div>
          <div class="art bg-[url(assets/ciwidey.jpg)]"></div>
          <div class="art bg-[url(assets/farmhouse.jpg)]"></div>
          <div class="art bg-[url(assets/tangkuban.jpg)] col-span-2"></div>
          <div class="art bg-[url(assets/cukul.jpg)]"></div>
          <div class="art bg-[url(assets/tsm.jpg)]"></div>
          <div class="art bg-[url(assets/museum.jp=8g)] col-span-2"></div>
        </div>
      </div>
    </section>

  </body>
</html>
