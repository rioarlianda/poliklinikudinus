<?php
// Mulai session jika belum dimulai
if (!isset($_SESSION)) {
    session_start();
}

// Sambungkan ke database (pastikan $mysqli sudah diinisialisasi sebelumnya)
include('koneksi.php'); // Ubah sesuai dengan file koneksi Anda

// Pastikan hanya akses dari tombol cetak yang diizinkan
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

// Ambil ID dari parameter URL
$id_periksa = $_GET['id'];

// Query untuk mendapatkan data periksa berdasarkan ID
$query = "SELECT daftar_poli.*, pasien.nama AS nama, jadwal_periksa.hari, periksa.tgl_periksa, periksa.catatan, periksa.biaya_periksa, obat.nama_obat AS nama_obat
          FROM daftar_poli
          JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id 
          JOIN pasien ON daftar_poli.id_pasien = pasien.id
          LEFT JOIN periksa ON daftar_poli.id = periksa.id_daftar_poli
          LEFT JOIN detail_periksa ON periksa.id = detail_periksa.id_periksa
          LEFT JOIN obat ON detail_periksa.id_obat = obat.id
          WHERE daftar_poli.id = '$id_periksa'";

$result = mysqli_query($mysqli, $query);

// Memastikan ada hasil dari query
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cetak Data Periksa</title>
        <link
            rel="icon"
            href=
"img/favicon.png"
            type="image/x-icon"
        />
        <style>
            /* Gaya cetak Anda bisa disesuaikan di sini */
            body {
                font-family: Arial, sans-serif;
                font-size: 30px;
                text-align: center;
            }
            h1 {
                font-family: Arial, sans-serif;
                font-size: 15px;
                text-align: center;
            }
            /* CSS tambahan sesuai kebutuhan cetak */
        </style>
    </head>
    <body>
        <h2>Invoice Periksa Pasien</h2>
        <table align="center" border="3">
            <tr>
                <th>Tanggal Periksa</th>
                <td><?php echo $data['tgl_periksa']; ?></td>
            </tr>
            <tr>
                <th>Nama Pasien</th>
                <td><?php echo $data['nama']; ?></td>
            </tr>
            <tr>
                <th>Nomor Antrian</th>
                <td><?php echo $data['no_antrian']; ?></td>
            </tr>
            <tr>
                <th>Keluhan</th>
                <td><?php echo $data['keluhan']; ?></td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td><?php echo $data['catatan']; ?></td>
            </tr>
            <tr>
                <th>Harga Obat</th>
                <td><?php 
                $biayadokter=150000;
                echo $data['biaya_periksa']-$biayadokter; ?></td>
            </tr>
            <tr>
                <th>Biaya Dokter</th>
                <td><?php 
                $biayadokter=150000;
                echo $biayadokter; ?></td>
            </tr>
            <tr>
                <th>Total Biaya</th>
                <td><?php echo $data['biaya_periksa']; ?></td>
            </tr>
            <tr>
                <th>Nama Obat</th>
                <td><?php echo $data['nama_obat']; ?></td>
            </tr>
            <!-- Tambahkan kolom lain sesuai kebutuhan -->
        </table>
        <script>
            window.print(); // Secara otomatis membuka jendela cetak saat halaman dimuat
        </script>
        <h1>Semoga Lekas Sembuh :)</h1>
        <h1>- Poliklinik Udinus -</h1>
    </body>
    </html>
<?php
} else {
    echo "Data tidak ditemukan.";
}
?>
