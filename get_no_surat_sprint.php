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
$count_query    = mysqli_query($config, "SELECT COUNT(*) as cnt FROM tbl_sprint WHERE tgl_surat = '$tgl'");
$count_row      = mysqli_fetch_assoc($count_query);
$count_for_date = (int) $count_row['cnt'];

if ($count_for_date > 0) {
    // Tanggal sudah ada → cari posisi bloknya (berdasarkan urutan first insertion)
    $dates_query = mysqli_query($config,
        "SELECT tgl_surat FROM tbl_sprint
         GROUP BY tgl_surat
         ORDER BY MIN(id_sprint) ASC"
    );
    $block_index = 0;
    while ($row = mysqli_fetch_assoc($dates_query)) {
        if ($row['tgl_surat'] == $tgl) {
            break;
        }
        $block_index++;
    }
    $no_surat = ($block_index * 20) + $count_for_date + 1;
} else {
    // Tanggal baru → blok berikutnya setelah semua tanggal yang sudah ada
    $no_surat = ($total_dates * 20) + 1;
}

echo json_encode([
    'no_surat' => $no_surat,
    'info'     => 'Berhasil'
]);
?>
