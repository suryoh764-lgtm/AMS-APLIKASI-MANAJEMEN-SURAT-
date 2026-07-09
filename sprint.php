<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['act'])){
            $act = $_REQUEST['act'];
            switch ($act) {
                case 'add':
                    include "tambah_sprint.php";
                    break;
                case 'edit':
                    include "edit_sprint.php";
                    break;
                case 'del':
                    include "hapus_sprint.php";
                    break;
            }
        } else {

            //pagging
            $limit = 10;
            $pg = @$_GET['pg'];
            if(empty($pg)){
                $curr = 0;
                $pg = 1;
            } else {
                $curr = ($pg - 1) * $limit;
            }
?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <div class="z-depth-1">
                        <nav class="secondary-nav">
                            <div class="nav-wrapper blue-grey darken-1">
                                <div class="col m7">
                                    <ul class="left">
                                        <li class="waves-effect waves-light hide-on-small-only"><a href="?page=sprint" class="judul"><i class="material-icons">flash_on</i> Sprint</a></li>
                                        <li class="waves-effect waves-light">
                                            <a href="?page=sprint&act=add"><i class="material-icons md-24">add_circle</i> Tambah Data</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col m5 hide-on-med-and-down">
                                    <form method="post" action="?page=sprint">
                                        <div class="input-field round-in-box">
                                            <input id="search" type="search" name="cari" placeholder="Cari no. surat, perihal, asal, tujuan..." required>
                                            <label for="search"><i class="material-icons md-dark">search</i></label>
                                            <input type="submit" name="submit" class="hidden">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
                if(isset($_SESSION['succAdd'])){
                    $succAdd = $_SESSION['succAdd'];
                    echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card green lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title green-text"><i class="material-icons md-36">done</i> '.$succAdd.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    unset($_SESSION['succAdd']);
                }
                if(isset($_SESSION['succEdit'])){
                    $succEdit = $_SESSION['succEdit'];
                    echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card green lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title green-text"><i class="material-icons md-36">done</i> '.$succEdit.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    unset($_SESSION['succEdit']);
                }
                if(isset($_SESSION['succDel'])){
                    $succDel = $_SESSION['succDel'];
                    echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card green lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title green-text"><i class="material-icons md-36">done</i> '.$succDel.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    unset($_SESSION['succDel']);
                }
            ?>

            <!-- Row form Start -->
            <div class="row jarak-form">

            <?php
                if(isset($_REQUEST['submit'])){
                    // Mode pencarian
                    $cari = mysqli_real_escape_string($config, $_REQUEST['cari']);
                    echo '
                    <div class="col s12" style="margin-top: -18px;">
                        <div class="card blue lighten-5">
                            <div class="card-content">
                            <p class="description">Hasil pencarian untuk kata kunci <strong>"'.stripslashes($cari).'"</strong><span class="right"><a href="?page=sprint"><i class="material-icons md-36" style="color: #333;">clear</i></a></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col m12" id="colres">
                    <table class="bordered" id="tbl">
                        <thead class="teal lighten-4" id="head">
                            <tr>
                                <th width="8%">No. Surat</th>
                                <th width="13%">Tgl Surat</th>
                                <th width="22%">Perihal</th>
                                <th width="15%">Keterangan</th>
                                <th width="17%">Asal Surat</th>
                                <th width="15%">Tujuan Surat</th>
                                <th width="10%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>';

                    $query = mysqli_query($config, "SELECT * FROM tbl_sprint
                        WHERE perihal LIKE '%$cari%'
                           OR asal_surat LIKE '%$cari%'
                           OR tujuan_surat LIKE '%$cari%'
                           OR keterangan LIKE '%$cari%'
                           OR no_surat LIKE '%$cari%'
                        ORDER BY id_sprint DESC LIMIT 15");

                    if(mysqli_num_rows($query) > 0){
                        while($row = mysqli_fetch_array($query)){
                            echo '
                            <tr>
                                <td><strong>'.$row['no_surat'].'</strong></td>
                                <td>'.indoDate($row['tgl_surat']).'</td>
                                <td>'.substr($row['perihal'],0,150).'</td>
                                <td>'.$row['keterangan'].'</td>
                                <td>'.$row['asal_surat'].'</td>
                                <td>'.$row['tujuan_surat'].'</td>
                                <td>
                                    <a class="btn small blue waves-effect waves-light" href="?page=sprint&act=edit&id_sprint='.$row['id_sprint'].'">
                                        <i class="material-icons">edit</i> EDIT</a>
                                    <a class="btn small deep-orange waves-effect waves-light" href="?page=sprint&act=del&id_sprint='.$row['id_sprint'].'"
                                        onclick="return confirm(\'Yakin hapus data no. surat '.$row['no_surat'].'?\')">
                                        <i class="material-icons">delete</i> DEL</a>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7"><center><p class="add">Tidak ada data yang ditemukan</p></center></td></tr>';
                    }

                    echo '</tbody></table><br/><br/>
                    </div>
                    </div>
                    <!-- Row form END -->';

                } else {

                    // Mode tampil semua data
                    echo '
                    <div class="col m12" id="colres">
                        <table class="bordered" id="tbl">
                            <thead class="teal lighten-4" id="head">
                                <tr>
                                    <th width="8%">No. Surat</th>
                                    <th width="13%">Tgl Surat</th>
                                    <th width="22%">Perihal</th>
                                    <th width="15%">Keterangan</th>
                                    <th width="17%">Asal Surat</th>
                                    <th width="15%">Tujuan Surat</th>
                                    <th width="10%">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>';

                    $query = mysqli_query($config, "SELECT * FROM tbl_sprint ORDER BY id_sprint DESC LIMIT $curr, $limit");
                    if(mysqli_num_rows($query) > 0){
                        while($row = mysqli_fetch_array($query)){
                            echo '
                            <tr>
                                <td><strong>'.$row['no_surat'].'</strong></td>
                                <td>'.indoDate($row['tgl_surat']).'</td>
                                <td>'.substr($row['perihal'],0,150).'</td>
                                <td>'.$row['keterangan'].'</td>
                                <td>'.$row['asal_surat'].'</td>
                                <td>'.$row['tujuan_surat'].'</td>
                                <td>
                                    <a class="btn small blue waves-effect waves-light" href="?page=sprint&act=edit&id_sprint='.$row['id_sprint'].'">
                                        <i class="material-icons">edit</i> EDIT</a>
                                    <a class="btn small deep-orange waves-effect waves-light" href="?page=sprint&act=del&id_sprint='.$row['id_sprint'].'"
                                        onclick="return confirm(\'Yakin hapus data no. surat '.$row['no_surat'].'?\')">
                                        <i class="material-icons">delete</i> DEL</a>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7"><center><p class="add">Tidak ada data untuk ditampilkan. <u><a href="?page=sprint&act=add">Tambah data baru</a></u></p></center></td></tr>';
                    }

                    echo '</tbody></table>
                    </div>
                    </div>
                    <!-- Row form END -->';

                    // Pagination
                    $query_total = mysqli_query($config, "SELECT COUNT(*) as total FROM tbl_sprint");
                    $total_row   = mysqli_fetch_assoc($query_total);
                    $cdata       = (int)$total_row['total'];
                    $cpg         = ceil($cdata / $limit);

                    echo '<br/><!-- Pagination START -->
                          <ul class="pagination">';

                    if($cdata > $limit){
                        if($pg > 1){
                            $prev = $pg - 1;
                            echo '<li><a href="?page=sprint&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                                  <li><a href="?page=sprint&pg='.$prev.'"><i class="material-icons md-48">chevron_left</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href="#"><i class="material-icons md-48">first_page</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">chevron_left</i></a></li>';
                        }

                        for($i = 1; $i <= $cpg; $i++){
                            if((($i >= $pg - 3) && ($i <= $pg + 3)) || ($i == 1) || ($i == $cpg)){
                                if($i == $pg) echo '<li class="active waves-effect waves-dark"><a href="?page=sprint&pg='.$i.'"> '.$i.' </a></li>';
                                else echo '<li class="waves-effect waves-dark"><a href="?page=sprint&pg='.$i.'"> '.$i.' </a></li>';
                            }
                        }

                        if($pg < $cpg){
                            $next = $pg + 1;
                            echo '<li><a href="?page=sprint&pg='.$next.'"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li><a href="?page=sprint&pg='.$cpg.'"><i class="material-icons md-48">last_page</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href="#"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">last_page</i></a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '</ul>';
                    }
                }
            ?>

<?php
        }
    }
?>
