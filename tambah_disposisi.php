<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['submit'])){

            $id_surat = mysqli_real_escape_string($config, $_REQUEST['id_surat']);

            // Menangkap data baru
            $no_agenda_kepala = $_REQUEST['no_agenda_kepala'];
            $tanggal_diterima_kepala = $_REQUEST['tanggal_diterima_kepala'];

            // Checkbox tujuan
            $tujuan_kabag_umum = isset($_REQUEST['tujuan_kabag_umum']) ? 1 : 0;
            $tujuan_kabid_berantas = isset($_REQUEST['tujuan_kabid_berantas']) ? 1 : 0;
            $tujuan_katim_p2m = isset($_REQUEST['tujuan_katim_p2m']) ? 1 : 0;
            $tujuan_katim_rehab = isset($_REQUEST['tujuan_katim_rehab']) ? 1 : 0;

            // Checkbox tindakan
            $tindakan_file = isset($_REQUEST['tindakan_file']) ? 1 : 0;
            $tindakan_tindak_lanjuti = isset($_REQUEST['tindakan_tindak_lanjuti']) ? 1 : 0;
            $tindakan_pedomani = isset($_REQUEST['tindakan_pedomani']) ? 1 : 0;
            $tindakan_teruskan = isset($_REQUEST['tindakan_teruskan']) ? 1 : 0;
            $tindakan_penuhi = isset($_REQUEST['tindakan_penuhi']) ? 1 : 0;
            $tindakan_jadwalkan = isset($_REQUEST['tindakan_jadwalkan']) ? 1 : 0;
            $tindakan_acc = isset($_REQUEST['tindakan_acc']) ? 1 : 0;
            $tindakan_wakil = isset($_REQUEST['tindakan_wakil']) ? 1 : 0;

            // Field lama
            $tujuan = isset($_REQUEST['tujuan']) ? $_REQUEST['tujuan'] : '';
            $isi_disposisi = isset($_REQUEST['isi_disposisi']) ? $_REQUEST['isi_disposisi'] : '-';
            $sifat = isset($_REQUEST['sifat']) ? $_REQUEST['sifat'] : 'Biasa';
            $batas_waktu = isset($_REQUEST['batas_waktu']) ? $_REQUEST['batas_waktu'] : '0000-00-00';
            $catatan = $_REQUEST['catatan'];
            $id_user = $_SESSION['id_user'];

            $query = mysqli_query($config, "INSERT INTO tbl_disposisi(tujuan, isi_disposisi, sifat, batas_waktu, catatan, id_surat, id_user, no_agenda_kepala, tanggal_diterima_kepala, pukul, tujuan_kabag_umum, tujuan_kabid_berantas, tujuan_katim_p2m, tujuan_katim_rehab, tindakan_file, tindakan_tindak_lanjuti, tindakan_pedomani, tindakan_teruskan, tindakan_penuhi, tindakan_jadwalkan, tindakan_acc, tindakan_wakil)
                VALUES('$tujuan', '$isi_disposisi', '$sifat', '$batas_waktu', '$catatan', '$id_surat', '$id_user', '$no_agenda_kepala', '$tanggal_diterima_kepala', '', '$tujuan_kabag_umum', '$tujuan_kabid_berantas', '$tujuan_katim_p2m', '$tujuan_katim_rehab', '$tindakan_file', '$tindakan_tindak_lanjuti', '$tindakan_pedomani', '$tindakan_teruskan', '$tindakan_penuhi', '$tindakan_jadwalkan', '$tindakan_acc', '$tindakan_wakil')");

            if($query == true){
                $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                header("Location: ./admin.php?page=tsm&act=disp&id_surat=".$id_surat);
                die();
            } else {
                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query: '.mysqli_error($config);
                header("Location: ./admin.php?page=tsm&act=disp&id_surat=".$id_surat."&sub=add");
                die();
            }
        } else {
            $id_surat = $_REQUEST['id_surat'];
            $query_s = mysqli_query($config, "SELECT no_agenda, tgl_diterima FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
            list($no_agenda_masuk, $tgl_diterima_masuk) = mysqli_fetch_array($query_s);
        ?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">description</i> Tambah Disposisi Surat Baru</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
                if(isset($_SESSION['errQ'])){
                    $errQ = $_SESSION['errQ'];
                    echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errQ.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    unset($_SESSION['errQ']);
                }
            ?>

            <!-- Row form Start -->
            <div class="row jarak-form">
                <!-- Form START -->
                <form class="col s12" method="post" action="">
                    <!-- Row in form START -->
                    <div class="row">
                        <h6 class="blue-grey-text" style="font-weight:bold; margin-left:10px;">INFO SURAT KEPALA</h6>
                        <div class="input-field col s4">
                            <i class="material-icons prefix md-prefix">turned_in_not</i>
                            <input id="no_agenda_kepala" type="text" name="no_agenda_kepala" value="<?php echo $no_agenda_masuk; ?>" required>
                            <label for="no_agenda_kepala">No Agenda Kepala</label>
                        </div>
                        <div class="input-field col s4">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="tanggal_diterima_kepala" type="text" name="tanggal_diterima_kepala" class="datepicker" value="<?php echo $tgl_diterima_masuk; ?>" required>
                            <label for="tanggal_diterima_kepala">Tanggal Diterima</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s6">
                            <h6 class="blue-grey-text" style="font-weight:bold;">KEPADA YTH:</h6>
                            <p>
                                <input type="checkbox" id="tujuan_kabag_umum" name="tujuan_kabag_umum" value="1" />
                                <label for="tujuan_kabag_umum">1. Kepala Bagian Umum</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tujuan_kabid_berantas" name="tujuan_kabid_berantas" value="1" />
                                <label for="tujuan_kabid_berantas">2. Kepala Bidang Pemberantasan & Intelijen</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tujuan_katim_p2m" name="tujuan_katim_p2m" value="1" />
                                <label for="tujuan_katim_p2m">3. Katim Kerja P2M</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tujuan_katim_rehab" name="tujuan_katim_rehab" value="1" />
                                <label for="tujuan_katim_rehab">4. Katim Kerja Rehabilitasi</label>
                            </p>
                        </div>
                        <div class="col s6">
                            <h6 class="blue-grey-text" style="font-weight:bold;">DISPOSISI KEPALA BNNP:</h6>
                            <p>
                                <input type="checkbox" id="tindakan_file" name="tindakan_file" value="1" />
                                <label for="tindakan_file">File</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tindakan_tindak_lanjuti" name="tindakan_tindak_lanjuti" value="1" />
                                <label for="tindakan_tindak_lanjuti">Tindak Lanjuti</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tindakan_pedomani" name="tindakan_pedomani" value="1" />
                                <label for="tindakan_pedomani">Pedomani</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tindakan_teruskan" name="tindakan_teruskan" value="1" />
                                <label for="tindakan_teruskan">Teruskan</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tindakan_penuhi" name="tindakan_penuhi" value="1" />
                                <label for="tindakan_penuhi">Penuhi</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tindakan_jadwalkan" name="tindakan_jadwalkan" value="1" />
                                <label for="tindakan_jadwalkan">Jadwalkan</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tindakan_acc" name="tindakan_acc" value="1" />
                                <label for="tindakan_acc">ACC</label>
                            </p>
                            <p>
                                <input type="checkbox" id="tindakan_wakil" name="tindakan_wakil" value="1" />
                                <label for="tindakan_wakil">Wakil</label>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix md-prefix">featured_play_list</i>
                            <input id="catatan" type="text" name="catatan">
                            <label for="catatan">Catatan / Arahan Tambahan</label>
                        </div>
                    </div>
                    <!-- Row in form END -->

                    <div class="row">
                        <div class="col 6">
                            <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                        </div>
                        <div class="col 6">
                            <button type="reset" onclick="window.history.back();" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></button>
                        </div>
                    </div>
                </form>
                <!-- Form END -->
            </div>
            <!-- Row form END -->

<?php
        }
    }
?>
