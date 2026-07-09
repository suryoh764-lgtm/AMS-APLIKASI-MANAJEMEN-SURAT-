<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        // Ambil id_sprint dari request
        $id_sprint = isset($_REQUEST['id_sprint']) ? (int)$_REQUEST['id_sprint'] : 0;

        if($id_sprint <= 0){
            header("Location: ./admin.php?page=sprint");
            die();
        }

        if(isset($_REQUEST['submit'])){

            // Validasi form kosong
            if($_REQUEST['tgl_surat'] == "" || $_REQUEST['perihal'] == "" ||
               $_REQUEST['keterangan'] == "" || $_REQUEST['asal_surat'] == "" ||
               $_REQUEST['tujuan_surat'] == ""){
                $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                $tgl_surat    = mysqli_real_escape_string($config, $_REQUEST['tgl_surat']);
                $perihal      = mysqli_real_escape_string($config, $_REQUEST['perihal']);
                $keterangan   = mysqli_real_escape_string($config, $_REQUEST['keterangan']);
                $asal_surat   = mysqli_real_escape_string($config, $_REQUEST['asal_surat']);
                $tujuan_surat = mysqli_real_escape_string($config, $_REQUEST['tujuan_surat']);
                $no_surat_lama= (int)$_REQUEST['no_surat_lama']; // tetap pakai no. surat lama

                $query = mysqli_query($config,
                    "UPDATE tbl_sprint SET
                        tgl_surat    = '$tgl_surat',
                        perihal      = '$perihal',
                        keterangan   = '$keterangan',
                        asal_surat   = '$asal_surat',
                        tujuan_surat = '$tujuan_surat'
                     WHERE id_sprint = '$id_sprint'"
                );

                if($query == true){
                    $_SESSION['succEdit'] = 'SUKSES! Data Sprint (No. Surat: '.$no_surat_lama.') berhasil diperbarui';
                    header("Location: ./admin.php?page=sprint");
                    die();
                } else {
                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query database';
                    echo '<script language="javascript">window.history.back();</script>';
                }
            }

        } else {

            // Ambil data yang akan diedit
            $query = mysqli_query($config, "SELECT * FROM tbl_sprint WHERE id_sprint = '$id_sprint'");
            if(mysqli_num_rows($query) == 0){
                header("Location: ./admin.php?page=sprint");
                die();
            }
            $data = mysqli_fetch_array($query);
?>

            <!-- Row Start -->
            <div class="row">
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light">
                                    <a href="?page=sprint&act=edit&id_sprint=<?php echo $id_sprint; ?>" class="judul">
                                        <i class="material-icons">flash_on</i> Edit Data Sprint
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <?php
                $errorKeys = ['errQ','errEmpty'];
                foreach($errorKeys as $ekey){
                    if(isset($_SESSION[$ekey])){
                        $msg = $_SESSION[$ekey];
                        echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card red lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$msg.'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        unset($_SESSION[$ekey]);
                    }
                }
            ?>

            <!-- Keterangan edit: no_surat tidak berubah -->
            <div class="row" style="margin-bottom: 0;">
                <div class="col s12">
                    <div class="card orange lighten-5" style="border-left: 4px solid #ff9800; margin-bottom: 0;">
                        <div class="card-content" style="padding: 10px 20px;">
                            <p style="font-size:0.95rem; color:#e65100; margin:0;">
                                <i class="material-icons" style="vertical-align:middle; font-size:1.1rem;">warning</i>
                                <strong>Perhatian:</strong> Nomor surat <strong>#<?php echo $data['no_surat']; ?></strong>
                                tidak dapat diubah saat mengedit. Hanya data lainnya yang dapat diperbarui.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row form Start -->
            <div class="row jarak-form">
                <form class="col s12" method="POST" action="?page=sprint&act=edit&id_sprint=<?php echo $id_sprint; ?>">

                    <input type="hidden" name="no_surat_lama" value="<?php echo $data['no_surat']; ?>">

                    <div class="row">

                        <!-- Tanggal Surat -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="tgl_surat" type="date" name="tgl_surat" class="validate"
                                   value="<?php echo $data['tgl_surat']; ?>" required
                                   style="padding-left: 3rem;">
                            <label for="tgl_surat" class="active" style="margin-left:3rem;">Tanggal Surat</label>
                        </div>

                        <!-- No. Surat (readonly - tidak berubah saat edit) -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">tag</i>
                            <input id="no_surat_display" type="number" class="validate"
                                   value="<?php echo $data['no_surat']; ?>" readonly
                                   style="background:#f5f5f5; cursor:not-allowed; font-weight:bold; color:#009688;">
                            <label for="no_surat_display" class="active">No. Surat (Tidak dapat diubah)</label>
                        </div>

                        <!-- Perihal -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">description</i>
                            <textarea id="perihal" class="materialize-textarea validate" name="perihal" required><?php echo htmlspecialchars($data['perihal']); ?></textarea>
                            <label for="perihal" class="active">Perihal</label>
                        </div>

                        <!-- Keterangan -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i>
                            <input id="keterangan" type="text" class="validate" name="keterangan"
                                   value="<?php echo htmlspecialchars($data['keterangan']); ?>" required>
                            <label for="keterangan" class="active">Keterangan</label>
                        </div>

                        <!-- Asal Surat -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">place</i>
                            <input id="asal_surat" type="text" class="validate" name="asal_surat"
                                   value="<?php echo htmlspecialchars($data['asal_surat']); ?>" required>
                            <label for="asal_surat" class="active">Asal Surat</label>
                        </div>

                        <!-- Tujuan Surat -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">send</i>
                            <input id="tujuan_surat" type="text" class="validate" name="tujuan_surat"
                                   value="<?php echo htmlspecialchars($data['tujuan_surat']); ?>" required>
                            <label for="tujuan_surat" class="active">Tujuan Surat</label>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col s12" style="margin-top:10px;">
                            <button type="submit" name="submit" class="btn-large teal waves-effect waves-light">
                                SIMPAN PERUBAHAN <i class="material-icons">done</i>
                            </button>
                            &nbsp;
                            <a href="?page=sprint" class="btn-large deep-orange waves-effect waves-light">
                                BATAL <i class="material-icons">clear</i>
                            </a>
                        </div>
                    </div>

                </form>
            </div>
            <!-- Row form END -->

<?php
        }
    }
?>
