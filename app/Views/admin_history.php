<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Playfair+Display:wght@400;700;900&display=swap');

    body {
      font-family: 'Lato', sans-serif;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
    }

</style>
</head>
<body class="gradient-bg text-white ">

    <!-- Memanggil Navbar dari layout terpisah -->
    <?= $this->include('layouts/admin_navbar') ?>

    <div class="container mx-auto mt-8 p-5 text-3xl">
        <h2 class="text-2xl font-bold tracking-widest text-shadow">
        <span class=" text-red-500">Histori Rese</span>rvasi Selesai
        </h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-5 border-gray-50 bg-gray-500 text-left text-xs font-semibold text-gray-50 uppercase tracking-wider">Nama Tamu</th>
                        <th class="px-5 py-3 border-b-5 border-gray-50 bg-gray-500 text-left text-xs font-semibold text-gray-50 uppercase tracking-wider">Kamar</th>
                        <th class="px-5 py-3 border-b-5 border-gray-50 bg-gray-500 text-left text-xs font-semibold text-gray-50 uppercase tracking-wider">Periode Menginap</th>
                        <th class="px-5 py-3 border-b-5 border-gray-50 bg-gray-500 text-left text-xs font-semibold text-gray-50 uppercase tracking-wider">Total Bayar</th>
                        <th class="px-5 py-3 border-b-5 border-gray-50 bg-gray-500 text-left text-xs font-semibold text-gray-50 uppercase tracking-wider">Tanggal Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($histories)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">
                                Belum ada data histori.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($histories as $history): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 border-b border-gray-200 text-sm"><p class="text-gray-900 whitespace-no-wrap"><?= esc($history['nama_pemesan']) ?></p></td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm"><p class="text-gray-900 whitespace-no-wrap"><?= esc($history['tipe_kamar']) ?></p></td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm"><p class="text-gray-900 whitespace-no-wrap"><?= date('d M Y', strtotime($history['tgl_masuk'])) ?> - <?= date('d M Y', strtotime($history['tgl_keluar'])) ?></p></td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm"><p class="text-gray-900 whitespace-no-wrap">Rp <?= number_format($history['total_bayar'], 0, ',', '.') ?></p></td>
                            <td class="px-5 py-4 border-b border-gray-200 text-sm"><p class="text-gray-900 whitespace-no-wrap"><?= date('d M Y, H:i', strtotime($history['tgl_selesai'])) ?></p></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- FOOTER -->
      <footer class="bg-black/50 backdrop-blur-sm mt-20 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center">
          <p class="text-gray-400">© 2024 Hotel Sepuluh Bandung. Semua hak cipta dilindungi.</p>
        </div>
      </footer>
</body>


</html>
