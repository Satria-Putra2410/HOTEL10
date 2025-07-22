<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Riwayat Reservasi - Hotel Sepuluh</title>
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
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 20px 40px rgba(220, 38, 38, 0.15);
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

        .btn-back {
            background: linear-gradient(135deg, #374151, #4b5563);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-back::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-back:hover::before {
            left: 100%;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(75, 85, 99, 0.4);
        }

        .welcome-text {
            background: linear-gradient(135deg, #ffffff, #f3f4f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* PERBAIKAN: Menyamakan class dengan status dari database */
        .status-reserved {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .status-check-in {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .status-selesai {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
        }

        .reservation-card {
            border-left: 4px solid transparent;
        }

        /* PERBAIKAN: Menyamakan class dengan status dari database */
        .reservation-card.reserved {
            border-left-color: #f59e0b;
        }

        .reservation-card.check-in {
            border-left-color: #10b981;
        }

        .reservation-card.selesai {
            border-left-color: #6366f1;
        }

        .detail-modal {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background: linear-gradient(135deg, rgba(55, 65, 81, 0.9) 0%, rgba(31, 41, 55, 0.9) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .loading-skeleton {
            background: linear-gradient(90deg, rgba(75, 85, 99, 0.4) 25%, rgba(107, 114, 128, 0.6) 50%, rgba(75, 85, 99, 0.4) 75%);
            background-size: 200% 100%;
            animation: loading 2s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .filter-active {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .filter-inactive {
            background: rgba(55, 65, 81, 0.5);
            color: #d1d5db;
        }

        .filter-inactive:hover {
            background: rgba(75, 85, 99, 0.7);
            color: white;
        }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen">

    <!-- Navigation -->
    <nav class="nav-glass p-4 shadow-2xl sticky top-0 z-50">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <a href="<?= base_url('tamu/dashboard') ?>" class="btn-back text-white font-bold py-2 px-4 rounded-lg shadow-lg">
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L6.414 9H17a1 1 0 110 2H6.414l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Kembali</span>
                    </span>
                </a>
                <h1 class="text-2xl font-bold tracking-widest text-shadow">
                    <span class="text-red-500">Hotel</span> Sepuluh
                </h1>
            </div>
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

    <!-- Hero Section -->
    <section class="relative py-16 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-2 h-2 bg-red-500 rounded-full opacity-60 pulse-animation"></div>
            <div class="absolute top-32 right-32 w-1 h-1 bg-white rounded-full opacity-40 pulse-animation" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-40 left-40 w-3 h-3 bg-red-400 rounded-full opacity-30 pulse-animation" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-5xl md:text-6xl font-bold mb-6 text-shadow scroll-float">
                <span class="text-red-400 floating-icon">Riwayat</span>
                <span class="block">Reservasi</span>
            </h2>
            <p class="text-xl md:text-2xl text-shadow opacity-90 scroll-float">Lihat semua histori pemesanan Anda</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="max-w-7xl mx-auto px-6 mb-8">
        <div class="glass-effect p-6 rounded-2xl shadow-2xl scroll-float">
            <h3 class="text-2xl font-bold text-red-400 mb-4">Filter Reservasi</h3>
            <div class="flex flex-wrap gap-3">
                <button class="filter-btn filter-active px-6 py-2 rounded-lg font-semibold transition-all duration-300" data-status="all">
                    Semua
                </button>
                <!-- PERBAIKAN: Menyamakan data-status dengan nilai dari database (dalam huruf kecil) -->
                <button class="filter-btn filter-inactive px-6 py-2 rounded-lg font-semibold transition-all duration-300" data-status="reserved">
                    Reserved
                </button>
                <button class="filter-btn filter-inactive px-6 py-2 rounded-lg font-semibold transition-all duration-300" data-status="check-in">
                    Check-In
                </button>
                <button class="filter-btn filter-inactive px-6 py-2 rounded-lg font-semibold transition-all duration-300" data-status="selesai">
                    Selesai
                </button>
                <!-- Anda bisa menambahkan tombol Dibatalkan jika ada statusnya di DB -->
                <!-- <button class="filter-btn filter-inactive px-6 py-2 rounded-lg font-semibold transition-all duration-300" data-status="dibatalkan">
                    Dibatalkan
                </button> -->
            </div>
        </div>
    </section>

    <!-- Reservations List -->
    <section class="max-w-7xl mx-auto px-6 pb-12">
        <div id="reservationsList" class="space-y-6">
            <!-- Loading skeletons will be shown initially -->
            <div class="loading-skeleton-container space-y-6">
                <div class="glass-effect p-6 rounded-2xl shadow-lg">
                    <div class="loading-skeleton h-6 w-3/4 mb-4 rounded"></div>
                    <div class="loading-skeleton h-4 w-1/2 mb-2 rounded"></div>
                    <div class="loading-skeleton h-4 w-2/3 mb-2 rounded"></div>
                    <div class="loading-skeleton h-4 w-1/3 rounded"></div>
                </div>
                <div class="glass-effect p-6 rounded-2xl shadow-lg">
                    <div class="loading-skeleton h-6 w-3/4 mb-4 rounded"></div>
                    <div class="loading-skeleton h-4 w-1/2 mb-2 rounded"></div>
                    <div class="loading-skeleton h-4 w-2/3 mb-2 rounded"></div>
                    <div class="loading-skeleton h-4 w-1/3 rounded"></div>
                </div>
            </div>
            
            <!-- Actual reservations will be loaded here -->
            <div id="reservationsContent" class="space-y-6" style="display: none;"></div>
            
            <!-- Empty state -->
            <div id="emptyState" class="glass-effect p-12 rounded-2xl shadow-2xl text-center" style="display: none;">
                <div class="text-6xl mb-6">📅</div>
                <h3 class="text-2xl font-bold text-gray-300 mb-4">Belum Ada Reservasi</h3>
                <p class="text-gray-400 mb-8">Anda belum memiliki riwayat reservasi. Mulai pesan kamar sekarang!</p>
                <a href="<?= base_url('tamu/dashboard') ?>" class="btn-logout text-white font-bold py-3 px-6 rounded-lg shadow-lg inline-block">
                    Pesan Kamar Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Detail Modal -->
    <div id="detailModal" class="detail-modal fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="modal-content w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-3xl font-bold text-red-400">Detail Reservasi</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black/50 backdrop-blur-sm py-8">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-gray-400">© 2024 Hotel Sepuluh Bandung. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <!-- ====================================================== -->
    <!-- MULAI KODE JAVASCRIPT                                    -->
    <!-- ====================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const skeletonContainer = document.querySelector('.loading-skeleton-container');
            const reservationsContent = document.getElementById('reservationsContent');
            const emptyState = document.getElementById('emptyState');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const detailModal = document.getElementById('detailModal');
            const closeModal = document.getElementById('closeModal');
            const modalContent = document.getElementById('modalContent');

            let allReservations = [];

            const formatDate = (dateString) => new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
            const formatCurrency = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            
            function renderReservations(statusFilter = 'all') {
                reservationsContent.innerHTML = '';
                
                const filteredReservations = statusFilter === 'all' 
                    ? allReservations 
                    : allReservations.filter(res => res.status && res.status.toLowerCase() === statusFilter);

                if (filteredReservations.length === 0) {
                    emptyState.style.display = 'block';
                    reservationsContent.style.display = 'none';
                } else {
                    emptyState.style.display = 'none';
                    reservationsContent.style.display = 'block';

                    filteredReservations.forEach(res => {
                        // PERBAIKAN: Menggunakan replace untuk menangani 'Check-In' menjadi 'check-in'
                        const status = res.status ? res.status.toLowerCase().replace(' ', '-') : 'reserved';
                        
                        // PERBAIKAN: Menyamakan keys dengan status dari database
                        const statusClasses = {
                            'reserved': 'status-reserved',
                            'check-in': 'status-check-in',
                            'selesai': 'status-selesai'
                        };
                        const borderClasses = {
                            'reserved': 'reserved',
                            'check-in': 'check-in',
                            'selesai': 'selesai'
                        };

                        const cardHTML = `
                            <div class="reservation-card ${borderClasses[status] || ''} card-hover glass-effect p-6 rounded-2xl shadow-lg cursor-pointer" data-id="${res.id_reservasi}">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                    <div>
                                        <h3 class="text-2xl font-bold mb-2">${res.nama_kamar || 'Tipe Kamar Tidak Diketahui'} - ${res.nomor_kamar || ''}</h3>
                                        <p class="text-gray-300">ID Reservasi: <span class="font-semibold text-red-400">${res.id_reservasi}</span></p>
                                        <p class="text-gray-300">Check-in: <span class="font-semibold">${formatDate(res.tgl_checkin)}</span></p>
                                        <p class="text-gray-300">Check-out: <span class="font-semibold">${formatDate(res.tgl_checkout)}</span></p>
                                    </div>
                                    <div class="mt-4 md:mt-0 text-right">
                                        <span class="status-badge ${statusClasses[status] || ''}">${res.status || 'Reserved'}</span>
                                        <p class="text-2xl font-bold mt-2">${formatCurrency(res.total_harga || 0)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        reservationsContent.insertAdjacentHTML('beforeend', cardHTML);
                    });
                }
                
                document.querySelectorAll('.reservation-card').forEach(card => {
                    card.addEventListener('click', () => showDetail(card.dataset.id));
                });
            }

            async function fetchReservations() {
                try {
                    skeletonContainer.style.display = 'block';
                    reservationsContent.style.display = 'none';
                    emptyState.style.display = 'none';

                    const response = await fetch('<?= base_url('tamu/api/riwayat') ?>');
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    
                    const data = await response.json();
                    allReservations = Array.isArray(data) ? data : [];
                    
                    if (allReservations.length > 0) {
                        renderReservations();
                    } else {
                        emptyState.style.display = 'block';
                    }

                } catch (error) {
                    console.error('Error fetching reservations:', error);
                    emptyState.querySelector('h3').textContent = 'Terjadi Kesalahan';
                    emptyState.querySelector('p').textContent = 'Tidak dapat memuat riwayat reservasi Anda. Silakan coba lagi nanti.';
                    emptyState.style.display = 'block';
                } finally {
                    skeletonContainer.style.display = 'none';
                }
            }

            function showDetail(reservationId) {
                const reservation = allReservations.find(res => res.id_reservasi == reservationId);
                if (!reservation) return;

                const status = reservation.status ? reservation.status.toLowerCase().replace(' ', '-') : 'reserved';
                const statusClassMap = {
                    'reserved': 'status-reserved',
                    'check-in': 'status-check-in',
                    'selesai': 'status-selesai'
                };
                const statusClass = statusClassMap[status] || '';

                modalContent.innerHTML = `
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Kamar</h4>
                                <p class="text-xl font-bold">${reservation.nama_kamar || 'N/A'} (${reservation.nomor_kamar || 'N/A'})</p>
                            </div>
                            <div>
                                <h4 class="text-gray-400 text-sm font-semibold tracking-wider uppercase">ID Reservasi</h4>
                                <p class="text-xl font-bold text-red-400">${reservation.id_reservasi}</p>
                            </div>
                        </div>
                        <hr class="border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Tanggal Check-in</h4>
                                <p class="text-lg">${formatDate(reservation.tgl_checkin)}</p>
                            </div>
                            <div>
                                <h4 class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Tanggal Check-out</h4>
                                <p class="text-lg">${formatDate(reservation.tgl_checkout)}</p>
                            </div>
                        </div>
                         <div>
                            <h4 class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Jumlah Tamu</h4>
                            <p class="text-lg">${reservation.jumlah_tamu || 1} Orang</p>
                        </div>
                        <hr class="border-gray-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Harga</h4>
                                <p class="text-3xl font-bold text-green-400">${formatCurrency(reservation.total_harga || 0)}</p>
                            </div>
                            <div>
                                <h4 class="text-gray-400 text-sm font-semibold tracking-wider uppercase text-right">Status</h4>
                                <span class="status-badge ${statusClass} mt-1 inline-block">${reservation.status || 'Reserved'}</span>
                            </div>
                        </div>
                    </div>
                `;
                detailModal.style.display = 'flex';
            }

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.replace('filter-active', 'filter-inactive'));
                    button.classList.replace('filter-inactive', 'filter-active');
                    renderReservations(button.dataset.status);
                });
            });

            closeModal.addEventListener('click', () => detailModal.style.display = 'none');
            detailModal.addEventListener('click', (e) => {
                if (e.target === detailModal) detailModal.style.display = 'none';
            });
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('show');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.scroll-float').forEach(el => observer.observe(el));

            fetchReservations();
        });
    </script>
</body>
</html>
