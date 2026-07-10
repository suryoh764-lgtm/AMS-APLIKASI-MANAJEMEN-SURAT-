<?php
    ob_start();
    //cek session
    session_start();

    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {
?>
<!--

Name        : Aplikasi Sederhana Manajemen Surat Menyurat
Version     : v1.0.1
Description : Aplikasi untuk mencatat data surat masuk dan keluar secara digital.
Date        : 2016
Developer   : BNN
Phone/WA    : 0852-3290-4156
Email       : rudi@masrud.com
Website     : https://bnn.com

-->
<!doctype html>
<html lang="en">

<!-- Include Head START -->
<?php include('include/head.php'); ?>
<!-- Include Head END -->

<!-- Body START -->
<body class="bg">

<!-- Header START -->
<header>

<!-- Include Navigation START -->
<?php include('include/menu.php'); ?>
<!-- Include Navigation END -->

</header>
<!-- Header END -->

<!-- Main START -->
<main>

    <!-- container START -->
    <div class="container">

    <?php
        if(isset($_REQUEST['page'])){
            $page = $_REQUEST['page'];
            switch ($page) {
                case 'tsm':
                    include "transaksi_surat_masuk.php";
                    break;
                case 'ctk':
                    include "cetak_disposisi.php";
                    break;
                case 'tsk':
                    include "transaksi_surat_keluar.php";
                    break;
                case 'asm':
                    include "agenda_surat_masuk.php";
                    break;
                case 'ask':
                    include "agenda_surat_keluar.php";
                    break;
                case 'ref':
                    include "referensi.php";
                    break;
                case 'sett':
                    include "pengaturan.php";
                    break;
                case 'pro':
                    include "profil.php";
                    break;
                case 'gsm':
                    include "galeri_sm.php";
                    break;
                case 'gsk':
                    include "galeri_sk.php";
                    break;
                case 'disp':
                    include "daftar_disposisi.php";
                    break;
                case 'sprint':
                    include "sprint.php";
                    break;
            }
        } else {
    ?>
        <!-- Row START -->
        <div class="row">

            <!-- Include Header Instansi START -->
            <?php include('include/header_instansi.php'); ?>
            <!-- Include Header Instansi END -->

            <!-- Welcome Message START -->
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <h4>Selamat Datang <?php echo $_SESSION['nama']; ?></h4>
                        <p class="description">Anda login sebagai
                        <?php
                            if($_SESSION['admin'] == 1){
                                echo "<strong>Super Admin</strong>. Anda memiliki akses penuh terhadap sistem.";
                            } elseif($_SESSION['admin'] == 2){
                                echo "<strong>Administrator</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                            } else {
                                echo "<strong>Petugas Disposisi</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                            }?></p>
                    </div>
                </div>
            </div>
            <!-- Welcome Message END -->

            <?php
                //menghitung jumlah surat masuk
                $count1 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_surat_masuk"));

                //menghitung jumlah surat keluar
                $count2 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_surat_keluar"));

                //menghitung jumlah disposisi
                $count3 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_disposisi"));

                //menghitung jumlah klasifikasi
                $count4 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_klasifikasi"));

                //menghitung jumlah pengguna
                $count5 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_user"));

                //menghitung jumlah sprint
                $count6 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM tbl_sprint"));

                //menghitung jumlah no. surat sprint terbesar
                $sprint_last_q = mysqli_query($config, "SELECT MAX(no_surat) as last_no FROM tbl_sprint");
                $sprint_last_r = mysqli_fetch_assoc($sprint_last_q);
                $sprint_last_no = $sprint_last_r['last_no'] ? $sprint_last_r['last_no'] : 0;

                //menghitung total tanggal unik sprint
                $sprint_dates_q = mysqli_query($config, "SELECT COUNT(DISTINCT tgl_surat) as total FROM tbl_sprint");
                $sprint_dates_r = mysqli_fetch_assoc($sprint_dates_q);
                $sprint_total_dates = (int)$sprint_dates_r['total'];
            ?>

            <!-- Info Statistic START -->
            <a href="?page=tsm">
                <div class="col s12 m4">
                    <div class="card cyan">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">mail</i> Jumlah Surat Masuk</span>
                            <?php echo '<h5 class="white-text link">'.$count1.' Surat Masuk</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <a href="?page=tsk">
                <div class="col s12 m4">
                    <div class="card lime darken-1">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">drafts</i> Jumlah Surat Keluar</span>
                            <?php echo '<h5 class="white-text link">'.$count2.' Surat Keluar</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <a href="?page=disp">
                <div class="col s12 m4">
                    <div class="card yellow darken-3">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">description</i> Jumlah Disposisi</span>
                            <?php echo '<h5 class="white-text link">'.$count3.' Disposisi</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

            <a href="?page=ref">
                <div class="col s12 m4">
                    <div class="card deep-orange">
                        <div class="card-content">
                            <span class="card-title white-text"><i class="material-icons md-36">class</i> Jumlah Klasifikasi Surat</span>
                            <?php echo '<h5 class="white-text link">'.$count4.' Klasifikasi Surat</h5>'; ?>
                        </div>
                    </div>
                </div>
            </a>

        <?php
            if($_SESSION['id_user'] == 1 || $_SESSION['admin'] == 2){?>
                <a href="?page=sett&sub=usr">
                    <div class="col s12 m4">
                        <div class="card blue accent-2">
                            <div class="card-content">
                                <span class="card-title white-text"><i class="material-icons md-36">people</i> Jumlah Pengguna</span>
                                <?php echo '<h5 class="white-text link">'.$count5.' Pengguna</h5>'; ?>
                            </div>
                        </div>
                    </div>
                </a>
            <!-- Info Statistic END -->
        <?php
            }
        ?>

            <!-- Sprint Section START -->
            <div class="col s12" style="margin-top: 10px;">
                <div class="card" style="border-left: 5px solid #009688; border-radius: 4px;">
                    <div class="card-content" style="padding: 15px 20px;">
                        <span class="card-title" style="font-size:1.4rem; color:#009688;">
                            Sprint
                            <a href="?page=sprint" class="btn-flat right teal-text waves-effect" style="margin-top:-5px;">
                                Lihat Semua <i class="material-icons right">arrow_forward</i>
                            </a>
                        </span>
                        <div class="row" style="margin: 10px 0 0;">
                            <div class="col s12 m4">
                                <div style="background:#e0f2f1; border-radius:8px; padding:12px 16px; text-align:center;">
                                    <p style="margin:0; font-size:0.85rem; color:#00796b;">Total Data Sprint</p>
                                    <h4 style="margin:4px 0; color:#009688; font-weight:bold;"><?php echo $count6; ?></h4>
                                    <p style="margin:0; font-size:0.8rem; color:#555;">entri</p>
                                </div>
                            </div>
                            <div class="col s12 m4">
                                <div style="background:#fce4ec; border-radius:8px; padding:12px 16px; text-align:center;">
                                    <p style="margin:0; font-size:0.85rem; color:#c62828;">No. Surat Terakhir</p>
                                    <h4 style="margin:4px 0; color:#e53935; font-weight:bold;"><?php echo $sprint_last_no ?: '—'; ?></h4>
                                    <p style="margin:0; font-size:0.8rem; color:#555;">nomor</p>
                                </div>
                            </div>
                            <div class="col s12 m4">
                                <div style="background:#e8f5e9; border-radius:8px; padding:12px 16px; text-align:center;">
                                    <p style="margin:0; font-size:0.85rem; color:#2e7d32;">Tanggal Unik Digunakan</p>
                                    <h4 style="margin:4px 0; color:#388e3c; font-weight:bold;"><?php echo $sprint_total_dates; ?></h4>
                                    <p style="margin:0; font-size:0.8rem; color:#555;">blok tanggal</p>
                                </div>
                            </div>
                        </div>
                        <!-- Data terbaru sprint -->
                        <?php
                            $sprint_recent = mysqli_query($config, "SELECT * FROM tbl_sprint ORDER BY no_surat DESC, id_sprint DESC LIMIT 5");
                            if(mysqli_num_rows($sprint_recent) > 0){
                                echo '<div style="margin-top:15px;">';
                                echo '<p style="font-size:0.85rem; color:#777; margin-bottom:5px;"><strong>5 Data Sprint Terbaru:</strong></p>';
                                echo '<table class="bordered" style="font-size:0.88rem;">';
                                echo '<thead class="teal lighten-4"><tr>';
                                echo '<th>No. Surat</th><th>Tgl Surat</th><th>Perihal</th><th>Asal Surat</th><th>Tujuan Surat</th>';
                                echo '</tr></thead><tbody>';
                                while($sr = mysqli_fetch_array($sprint_recent)){
                                    echo '<tr>';
                                    echo '<td><strong>'.$sr['no_surat'].'</strong></td>';
                                    echo '<td>'.indoDate($sr['tgl_surat']).'</td>';
                                    echo '<td>'.substr($sr['perihal'],0,60).(strlen($sr['perihal'])>60?'...':'').'</td>';
                                    echo '<td>'.$sr['asal_surat'].'</td>';
                                    echo '<td>'.$sr['tujuan_surat'].'</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table></div>';
                            } else {
                                echo '<p style="color:#777; margin-top:10px; font-size:0.9rem;"><em>Belum ada data sprint. <a href="?page=sprint&act=add" class="teal-text">Tambah data pertama</a></em></p>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Sprint Section END -->

        </div>
        <!-- Row END -->
    <?php
        }
    ?>
    </div>
    <!-- container END -->

</main>
<!-- Main END -->

<!-- Include Footer START -->
<?php include('include/footer.php'); ?>
<!-- Include Footer END -->

</body>
<!-- Body END -->

</html>

<?php
    }
?>
