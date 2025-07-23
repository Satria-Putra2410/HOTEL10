<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Form Reservasi - Hotel Sepuluh</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            background: rgba(55, 65, 81, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-glass {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(220, 38, 38, 0.2);
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

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }

        .form-input {
            background: rgba(55, 65, 81, 0.3);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: white;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            background: rgba(55, 65, 81, 0.5);
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .summary-card {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(55, 65, 81, 0.3) 100%);
            border: 1px solid rgba(220, 38, 38, 0.2);
        }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen">

    <!-- Navigation -->
    <nav class="nav-glass p-4 shadow-2xl sticky top-0 z-50">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold tracking-widest text-shadow">
                <span class="text-red-500">Hotel</span> Sepuluh
            </h1>
            <div class="flex items-center space-x-4">
                <a href="<?= base_url('tamu/dashboard') ?>" class="text-red-400 hover:text-red-300 transition-colors">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 py-12">
        <h2 class="text-4xl font-bold text-center mb-8 text-shadow">
            <span class="text-red-400">Form</span> Reservasi Kamar
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form Section -->
            <div class="glass-effect p-8 rounded-2xl shadow-2xl card-pattern">
                <h3 class="text-2xl font-bold text-red-400 mb-6">Detail Reservasi</h3>
                
                <form id="reservasiForm" class="space-y-6">
                    <!-- Data Tamu -->
                    <div class="border-b border-gray-600 pb-6">
                        <h4 class="text-lg font-semibold text-gray-300 mb-4">Data Tamu</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_tamu" class="block text-gray-300 text-sm font-bold mb-2">Nama Lengkap:</label>
                                <input type="text" id="nama_tamu" name="nama_tamu" 
                                       value="<?= esc($tamu['nama']) ?>"
                                       class="form-input w-full py-3 px-4 rounded-lg focus:outline-none" required>
                            </div>
                            <div>
                                <label for="email_tamu" class="block text-gray-300 text-sm font-bold mb-2">Email:</label>
                                <input type="email" id="email_tamu" name="email_tamu" 
                                       value="<?= esc($tamu['email']) ?>"
                                       class="form-input w-full py-3 px-4 rounded-lg focus:outline-none bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="no_hp_tamu" class="block text-gray-300 text-sm font-bold mb-2">Nomor HP:</label>
                            <input type="tel" id="no_hp_tamu" name="no_hp_tamu" 
                                   value="<?= esc($tamu['no_hp'] ?? '') ?>"
                                   placeholder="Masukkan nomor HP Anda"
                                   class="form-input w-full py-3 px-4 rounded-lg focus:outline-none" required>
                        </div>
                    </div>

                    <!-- Detail Kamar -->
                    <div class="border-b border-gray-600 pb-6">
                        <h4 class="text-lg font-semibold text-gray-300 mb-4">Detail Kamar</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Tipe Kamar:</label>
                                <input type="text" value="<?= esc($kamar['tipe_kamar']) ?>" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Nomor Kamar:</label>
                                <input type="text" value="<?= esc($unit_kamar['nomor_kamar']) ?>" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Jenis Ranjang:</label>
                                <input type="text" value="<?= esc($kamar['jenis_ranjang']) ?>" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Kapasitas:</label>
                                <input type="text" value="<?= esc($kamar['jumlah_tamu']) ?> Tamu" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Reservasi -->
                    <div class="pb-6">
                        <h4 class="text-lg font-semibold text-gray-300 mb-4">Detail Reservasi</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Tanggal Check-in:</label>
                                <input type="text" value="<?= date('d F Y', strtotime($tgl_masuk)) ?>" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Tanggal Check-out:</label>
                                <input type="text" value="<?= date('d F Y', strtotime($tgl_keluar)) ?>" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Jumlah Tamu:</label>
                                <input type="text" value="<?= esc($jumlah_tamu) ?> Orang" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm font-bold mb-2">Durasi Menginap:</label>
                                <input type="text" value="<?= $jumlah_hari ?> Malam" 
                                       class="form-input w-full py-3 px-4 rounded-lg bg-gray-700 cursor-not-allowed" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="id_unit_kamar" value="<?= $unit_kamar['id_unit_kamar'] ?>">
                    <input type="hidden" name="tgl_masuk" value="<?= $tgl_masuk ?>">
                    <input type="hidden" name="tgl_keluar" value="<?= $tgl_keluar ?>">
                    <input type="hidden" name="total_harga" value="<?= $total_harga ?>">

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full text-white font-bold py-4 px-6 rounded-lg focus:outline-none focus:shadow-outline">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="submitText">Konfirmasi Reservasi</span>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Summary Section -->
            <div class="space-y-6">
                <!-- Room Image -->
                <?php if (!empty($unit_kamar['foto'])): ?>
                <div class="glass-effect p-6 rounded-2xl shadow-2xl">
                    <img src="<?= base_url('uploads/kamar/' . $unit_kamar['foto']) ?>" 
                         alt="<?= esc($kamar['tipe_kamar']) ?>" 
                         class="w-full h-64 object-cover rounded-lg">
                </div>
                <?php endif; ?>

                <!-- Price Summary -->
                <div class="summary-card p-8 rounded-2xl shadow-2xl">
                    <h3 class="text-2xl font-bold text-red-400 mb-6">Ringkasan Biaya</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Harga per malam:</span>
                            <span class="font-semibold">Rp <?= number_format($harga_kamar, 0, ',', '.') ?></span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Durasi menginap:</span>
                            <span class="font-semibold"><?= $jumlah_hari ?> malam</span>
                        </div>
                        
                        <div class="border-t border-gray-600 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-red-400">Total Biaya:</span>
                                <span class="text-xl font-bold text-red-400">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Room Description -->
                    <?php if (!empty($kamar['deskripsi'])): ?>
                    <div class="mt-6 pt-6 border-t border-gray-600">
                        <h4 class="text-lg font-semibold text-gray-300 mb-2">Deskripsi Kamar:</h4>
                        <p class="text-gray-400 leading-relaxed"><?= esc($kamar['deskripsi']) ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Terms -->
                    <div class="mt-6 pt-6 border-t border-gray-600">
                        <h4 class="text-lg font-semibold text-gray-300 mb-2">Ketentuan:</h4>
                        <ul class="text-gray-400 text-sm space-y-1">
                            <li>• Check-in: 14:00 WIB</li>
                            <li>• Check-out: 12:00 WIB</li>
                            <li>• Pembatalan gratis hingga 24 jam sebelum check-in</li>
                            <li>• Tidak termasuk sarapan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl text-center">
            <svg class="animate-spin h-12 w-12 text-red-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-white">Memproses reservasi...</p>
        </div>
    </div>

    <script>
        document.getElementById('reservasiForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const submitText = document.getElementById('submitText');
            const loadingModal = document.getElementById('loadingModal');
            
            // Show loading
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            loadingModal.classList.remove('hidden');
            
            // Collect form data
            const formData = {
                id_unit_kamar: document.querySelector('input[name="id_unit_kamar"]').value,
                tgl_masuk: document.querySelector('input[name="tgl_masuk"]').value,
                tgl_keluar: document.querySelector('input[name="tgl_keluar"]').value,
                total_harga: document.querySelector('input[name="total_harga"]').value,
                nama_tamu: document.getElementById('nama_tamu').value,
                no_hp_tamu: document.getElementById('no_hp_tamu').value
            };
            
            try {
                const response = await fetch('<?= base_url('tamu/proses-reservasi') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                // Hide loading
                loadingModal.classList.add('hidden');
                submitBtn.disabled = false;
                submitText.textContent = 'Konfirmasi Reservasi';
                
                if (result.status === 'success') {
                    alert('Reservasi berhasil dibuat! ID Reservasi: ' + result.id_reservasi);
                    window.location.href = '<?= base_url('tamu/riwayat-reservasi') ?>';
                } else {
                    alert('Error: ' + result.message);
                }
                
            } catch (error) {
                // Hide loading
                loadingModal.classList.add('hidden');
                submitBtn.disabled = false;
                submitText.textContent = 'Konfirmasi Reservasi';
                
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses reservasi.');
            }
        });
    </script>

</body>
</html>