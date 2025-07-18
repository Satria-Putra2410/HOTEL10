<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <!-- Anda bisa menambahkan link ke file CSS di sini untuk styling -->
    <style>
        .errors { color: #dc3545; list-style-type: none; padding: 0; }
        .success { color: #28a745; }
    </style>
</head>
<body>

    <h2>Edit Profil</h2>
    <p>Perbarui informasi pribadi Anda di bawah ini.</p>

    <!-- Menampilkan pesan sukses jika ada -->
    <?php if (session()->getFlashdata('success')): ?>
        <p class="success"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <!-- Menampilkan error validasi jika ada -->
    <?php $errors = session()->getFlashdata('errors'); ?>
    <?php if (!empty($errors)): ?>
        <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>


    <form action="<?= site_url('tamu/profil') ?>" method="post">
        
        <?= csrf_field() ?>

        <div>
            <label for="nama_tamu">Nama Lengkap</label><br>
            <!-- PERBAIKAN: name="nama_tamu" dan mengambil old input -->
            <input type="text" name="nama_tamu" id="nama_tamu" value="<?= old('nama_tamu', esc($tamu['nama_tamu'] ?? '')) ?>">
        </div>
        <br>

        <div>
            <label for="no_hp_tamu">Nomor HP</label><br>
            <!-- PERBAIKAN: Menambahkan field no_hp_tamu -->
            <input type="text" name="no_hp_tamu" id="no_hp_tamu" value="<?= old('no_hp_tamu', esc($tamu['no_hp_tamu'] ?? '')) ?>">
        </div>
        <br>

        <div>
            <label for="email">Email</label><br>
            <!-- PERBAIKAN: mengambil old input -->
            <input type="email" name="email" id="email" value="<?= old('email', esc($tamu['email'] ?? '')) ?>">
        </div>
        <br>
        
        <div>
            <label for="password">Password Baru (opsional)</label><br>
            <input type="password" name="password" id="password">
            <small>Kosongkan jika tidak ingin mengubah password.</small>
        </div>
        <br>

        <div>
            <button type="submit">Simpan Perubahan</button>
        </div>

    </form>

</body>
</html>
