<?php
/**
 * AJAX Endpoint: Hitung No. Surat Sprint
 * 
 * Logika:
 * - Jika belum ada data sama sekali → no_surat = 1
 * - Setiap tanggal unik baru mendapat blok berikutnya (kelipatan 20)
 * - Urutan blok berdasarkan urutan pertama kali tanggal digunakan (insertion order)
 * - no_surat = (block_index * 20) + jumlah_entri_di_tanggal_tsb + 1
 */

ob_start();
session_start();

if (empty($_SESSION['admin'])) {
    echo json_encode(['error' => 'Unauthorized']);
    die();
}

require_once 'include/config.php';
require_once 'include/functions.php';
$config = conn($host, $username, $password, $database);

$tgl = isset($_GET['tgl']) ? mysqli_real_escape_string($config, trim($_GET['tgl'])) : '';

if (empty($tgl)) {
    echo json_encode(['no_surat' => 1, 'info' => 'Tanggal kosong']);
    die();
}

// Hitung total tanggal unik yang sudah digunakan
$total_dates_query = mysqli_query($config, "SELECT COUNT(DISTINCT tgl_surat) as total FROM tbl_sprint");
$total_dates_row    = mysqli_fetch_assoc($total_dates_query);
$total_dates        = (int) $total_dates_row['total'];

if ($total_dates == 0) {
    // Belum ada data sama sekali → no. surat = 1 (first use ever)
    echo json_encode([
        'no_surat' => 1,
        'info'     => 'Pertama kali digunakan, nomor surat dimulai dari 1'
    ]);
    die();
}

// Cek apakah tanggal ini sudah pernah digunakan
$cek_query = mysqli_query($config, "SELECT no_surat FROM tbl_sprint WHERE tgl_surat = '$tgl' LIMIT 1");

if (mysqli_num_rows($cek_query) > 0) {
    // Tanggal sudah ada → gunakan nomor yang sama
    $row = mysqli_fetch_assoc($cek_query);
    $no_surat = $row['no_surat'];
} else {
    // Tanggal baru → ambil nomor terbesar + 20 (kelipatan 20 per tanggal baru)
    $max_query = mysqli_query($config, "SELECT MAX(no_surat) as max_no FROM tbl_sprint");
    $max_row = mysqli_fetch_assoc($max_query);
    $max_no = (int)$max_row['max_no'];
    
    if ($max_no == 0) {
        $no_surat = 1;
    } else {
        $no_surat = $max_no + 20;
    }
}

echo json_encode([
    'no_surat' => $no_surat,
    'info'     => 'Berhasil'
]);
?>
