<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        $id_sprint = isset($_REQUEST['id_sprint']) ? (int)$_REQUEST['id_sprint'] : 0;

        if($id_sprint <= 0){
            header("Location: ./admin.php?page=sprint");
            die();
        }

        // Ambil data untuk mendapatkan no_surat (ditampilkan di pesan sukses)
        $data_q = mysqli_query($config, "SELECT no_surat FROM tbl_sprint WHERE id_sprint = '$id_sprint'");
        if(mysqli_num_rows($data_q) == 0){
            header("Location: ./admin.php?page=sprint");
            die();
        }
        $data_row = mysqli_fetch_assoc($data_q);
        $no_surat_del = $data_row['no_surat'];

        $query = mysqli_query($config, "DELETE FROM tbl_sprint WHERE id_sprint = '$id_sprint'");

        if($query == true){
            $_SESSION['succDel'] = 'SUKSES! Data Sprint (No. Surat: '.$no_surat_del.') berhasil dihapus';
            header("Location: ./admin.php?page=sprint");
            die();
        } else {
            $_SESSION['errDel'] = 'ERROR! Gagal menghapus data Sprint';
            header("Location: ./admin.php?page=sprint");
            die();
        }
    }
?>
