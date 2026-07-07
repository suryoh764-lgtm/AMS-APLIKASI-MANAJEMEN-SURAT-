<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        // Baca setting jumlah data per halaman
        $querySett = mysqli_query($config, "SELECT id_sett, disposisi FROM tbl_sett");
        list($id_sett, $disposisi_limit) = mysqli_fetch_array($querySett);

        // Simpan setting jika form modal di-submit
        if(isset($_REQUEST['simpan_disp'])){
            $new_limit = (int)$_REQUEST['disposisi'];
            $id_user   = $_SESSION['id_user'];
            mysqli_query($config, "UPDATE tbl_sett SET disposisi='$new_limit', id_user='$id_user' WHERE id_sett='$id_sett'");
            header("Location: ./admin.php?page=disp");
            die();
        }

        $limit = $disposisi_limit;

        //pagging
        $pg = @$_GET['pg'];
        if(empty($pg)){
            $curr = 0;
            $pg   = 1;
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
                                    <li class="waves-effect waves-light hide-on-small-only">
                                        <a href="?page=disp" class="judul">
                                            <i class="material-icons">description</i> Daftar Disposisi
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col m5 hide-on-med-and-down">
                                <form method="post" action="?page=disp">
                                    <div class="input-field round-in-box">
                                        <input id="search_disp" type="search" name="cari" placeholder="Ketik dan tekan enter untuk mencari..." required>
                                        <label for="search_disp"><i class="material-icons md-dark">search</i></label>
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

        <!-- Row form Start -->
        <div class="row jarak-form">
        <?php
            if(isset($_REQUEST['submit'])){
                /* ===================== MODE PENCARIAN ===================== */
                $cari = mysqli_real_escape_string($config, $_REQUEST['cari']);
                echo '
                <div class="col s12" style="margin-top: -18px;">
                    <div class="card blue lighten-5">
                        <div class="card-content">
                            <p class="description">Hasil pencarian untuk kata kunci
                            <strong>"'.stripslashes($cari).'"</strong>
                            <span class="right">
                                <a href="?page=disp"><i class="material-icons md-36" style="color:#333;">clear</i></a>
                            </span></p>
                        </div>
                    </div>
                </div>

                <div class="col m12" id="colres">
                <table class="bordered" id="tbl">
                    <thead class="yellow darken-3" id="head">
                        <tr>
                            <th width="4%">No</th>
                            <th width="10%">No. Agenda</th>
                            <th width="21%">Perihal Surat</th>
                            <th width="17%">Tujuan Disposisi</th>
                            <th width="17%">Isi Disposisi</th>
                            <th width="10%">Sifat /<br/>Batas Waktu</th>
                            <th width="21%">Tindakan <span class="right"><i class="material-icons" style="color:#333;">settings</i></span></th>
                        </tr>
                    </thead>
                    <tbody>';

                $query = mysqli_query($config,
                    "SELECT tbl_disposisi.*, tbl_surat_masuk.isi, tbl_surat_masuk.no_agenda, tbl_surat_masuk.id_user AS pemilik
                     FROM tbl_disposisi
                     JOIN tbl_surat_masuk ON tbl_disposisi.id_surat = tbl_surat_masuk.id_surat
                     WHERE tbl_disposisi.isi_disposisi LIKE '%$cari%'
                        OR tbl_surat_masuk.isi         LIKE '%$cari%'
                        OR tbl_disposisi.tujuan        LIKE '%$cari%'
                     ORDER BY tbl_disposisi.id_disposisi DESC LIMIT 15");

                if(mysqli_num_rows($query) > 0){
                    $no = 0;
                    while($row = mysqli_fetch_array($query)){
                        $no++;
                        echo '<tr>
                            <td>'.$no.'</td>
                            <td>'.$row['no_agenda'].'</td>
                            <td>'.substr($row['isi'],0,120).'</td>
                            <td>'.$row['tujuan'].'</td>
                            <td>'.$row['isi_disposisi'].'</td>
                            <td>'.$row['sifat'].'<br/>'.indoDate($row['batas_waktu']).'</td>
                            <td>';

                        if($_SESSION['id_user'] != $row['pemilik'] AND $_SESSION['id_user'] != 1){
                            echo '<a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank">
                                    <i class="material-icons">print</i> PRINT</a>';
                        } else {
                            echo '<a class="btn small blue waves-effect waves-light" href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'&sub=edit&id_disposisi='.$row['id_disposisi'].'">
                                    <i class="material-icons">edit</i> EDIT</a>
                                  <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank">
                                    <i class="material-icons">print</i> PRINT</a>
                                  <a class="btn small deep-orange waves-effect waves-light" href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'&sub=del&id_disposisi='.$row['id_disposisi'].'" onclick="return confirm(\'Yakin ingin menghapus disposisi ini?\')">
                                    <i class="material-icons">delete</i> DEL</a>';
                        }

                        echo '</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="7"><center><p class="add">Tidak ada data yang ditemukan</p></center></td></tr>';
                }
                echo '</tbody></table><br/><br/>
                </div>
                </div>';

            } else {
                /* ===================== MODE TAMPIL NORMAL ===================== */
                echo '
                <div class="col m12" id="colres">
                <table class="bordered" id="tbl">
                    <thead class="yellow darken-3" id="head">
                        <tr>
                            <th width="4%">No</th>
                            <th width="10%">No. Agenda</th>
                            <th width="21%">Perihal Surat</th>
                            <th width="17%">Tujuan Disposisi</th>
                            <th width="17%">Isi Disposisi</th>
                            <th width="10%">Sifat /<br/>Batas Waktu</th>
                            <th width="21%">Tindakan
                                <span class="right tooltipped" data-position="left" data-tooltip="Atur jumlah data yang ditampilkan">
                                    <a class="modal-trigger" href="#modal-disp">
                                        <i class="material-icons" style="color:#333;">settings</i>
                                    </a>
                                </span>
                            </th>

                            <!-- Modal Pengaturan -->
                            <div id="modal-disp" class="modal">
                                <div class="modal-content white">
                                    <h5>Jumlah data yang ditampilkan per halaman</h5>';

                                    $querySett2 = mysqli_query($config, "SELECT id_sett, disposisi FROM tbl_sett");
                                    list($id_sett2, $cur_limit) = mysqli_fetch_array($querySett2);

                                    echo '
                                    <div class="row">
                                        <form method="post" action="?page=disp">
                                            <div class="input-field col s12">
                                                <input type="hidden" value="'.$id_sett2.'" name="id_sett">
                                                <div class="input-field col s1" style="float:left;">
                                                    <i class="material-icons prefix md-prefix">looks_one</i>
                                                </div>
                                                <div class="input-field col s11 right" style="margin:-5px 0 20px;">
                                                    <select class="browser-default validate" name="disposisi" required>
                                                        <option value="'.$cur_limit.'">'.$cur_limit.' (saat ini)</option>
                                                        <option value="5">5</option>
                                                        <option value="10">10</option>
                                                        <option value="20">20</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer white">
                                                    <button type="submit" class="modal-action waves-effect waves-green btn-flat" name="simpan_disp">Simpan</button>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Batal</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->

                        </tr>
                    </thead>
                    <tbody>';

                $query = mysqli_query($config,
                    "SELECT tbl_disposisi.*, tbl_surat_masuk.isi, tbl_surat_masuk.no_agenda, tbl_surat_masuk.id_user AS pemilik
                     FROM tbl_disposisi
                     JOIN tbl_surat_masuk ON tbl_disposisi.id_surat = tbl_surat_masuk.id_surat
                     ORDER BY tbl_disposisi.id_disposisi DESC
                     LIMIT $curr, $limit");

                if(mysqli_num_rows($query) > 0){
                    $no = $curr;
                    while($row = mysqli_fetch_array($query)){
                        $no++;
                        echo '<tr>
                            <td>'.$no.'</td>
                            <td>'.$row['no_agenda'].'</td>
                            <td>'.substr($row['isi'],0,120).'</td>
                            <td>'.$row['tujuan'].'</td>
                            <td>'.$row['isi_disposisi'].'</td>
                            <td>'.$row['sifat'].'<br/>'.indoDate($row['batas_waktu']).'</td>
                            <td>';

                        if($_SESSION['id_user'] != $row['pemilik'] AND $_SESSION['id_user'] != 1){
                            echo '<a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank">
                                    <i class="material-icons">print</i> PRINT</a>';
                        } else {
                            echo '<a class="btn small blue waves-effect waves-light" href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'&sub=edit&id_disposisi='.$row['id_disposisi'].'">
                                    <i class="material-icons">edit</i> EDIT</a>
                                  <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_surat='.$row['id_surat'].'" target="_blank">
                                    <i class="material-icons">print</i> PRINT</a>
                                  <a class="btn small deep-orange waves-effect waves-light" href="?page=tsm&act=disp&id_surat='.$row['id_surat'].'&sub=del&id_disposisi='.$row['id_disposisi'].'" onclick="return confirm(\'Yakin ingin menghapus disposisi ini?\')">
                                    <i class="material-icons">delete</i> DEL</a>';
                        }

                        echo '</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="7"><center><p class="add">Tidak ada data disposisi untuk ditampilkan.</p></center></td></tr>';
                }

                echo '</tbody></table>
                </div>
                </div>';

                // Pagination
                $queryAll = mysqli_query($config, "SELECT COUNT(*) as total FROM tbl_disposisi");
                $rowAll   = mysqli_fetch_array($queryAll);
                $cdata    = $rowAll['total'];
                $cpg      = ceil($cdata / $limit);

                echo '<br/><!-- Pagination START -->
                      <ul class="pagination">';

                if($cdata > $limit){
                    if($pg > 1){
                        $prev = $pg - 1;
                        echo '<li><a href="?page=disp&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                              <li><a href="?page=disp&pg='.$prev.'"><i class="material-icons md-48">chevron_left</i></a></li>';
                    } else {
                        echo '<li class="disabled"><a href="#"><i class="material-icons md-48">first_page</i></a></li>
                              <li class="disabled"><a href="#"><i class="material-icons md-48">chevron_left</i></a></li>';
                    }

                    for($i = 1; $i <= $cpg; $i++){
                        if((($i >= $pg-3) && ($i <= $pg+3)) || ($i == 1) || ($i == $cpg)){
                            if($i == $pg)
                                echo '<li class="active waves-effect waves-dark"><a href="?page=disp&pg='.$i.'"> '.$i.' </a></li>';
                            else
                                echo '<li class="waves-effect waves-dark"><a href="?page=disp&pg='.$i.'"> '.$i.' </a></li>';
                        }
                    }

                    if($pg < $cpg){
                        $next = $pg + 1;
                        echo '<li><a href="?page=disp&pg='.$next.'"><i class="material-icons md-48">chevron_right</i></a></li>
                              <li><a href="?page=disp&pg='.$cpg.'"><i class="material-icons md-48">last_page</i></a></li>';
                    } else {
                        echo '<li class="disabled"><a href="#"><i class="material-icons md-48">chevron_right</i></a></li>
                              <li class="disabled"><a href="#"><i class="material-icons md-48">last_page</i></a></li>';
                    }
                    echo '</ul>';
                }
            }
        ?>
        </div>
    <?php
    }
?>
