<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['submit'])){

            // Validasi form kosong
            if( $_REQUEST['no_surat'] == "" || $_REQUEST['tgl_surat'] == "" ||
                $_REQUEST['perihal'] == "" || $_REQUEST['keterangan'] == "" ||
                $_REQUEST['asal_surat'] == "" || $_REQUEST['tujuan_surat'] == "" ){
                $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                $no_surat     = (int)$_REQUEST['no_surat'];
                $tgl_surat    = mysqli_real_escape_string($config, $_REQUEST['tgl_surat']);
                $perihal      = mysqli_real_escape_string($config, $_REQUEST['perihal']);
                $keterangan   = mysqli_real_escape_string($config, $_REQUEST['keterangan']);
                $asal_surat   = mysqli_real_escape_string($config, $_REQUEST['asal_surat']);
                $tujuan_surat = mysqli_real_escape_string($config, $_REQUEST['tujuan_surat']);
                $id_user      = $_SESSION['id_user'];

                // Validasi format tanggal
                if(!preg_match("/^[0-9\-]*$/", $tgl_surat)){
                    $_SESSION['tgl_surat'] = 'Form Tanggal Surat hanya boleh mengandung angka dan minus(-)';
                    echo '<script language="javascript">window.history.back();</script>';
                }
                // Validasi no_surat positif
                else if($no_surat <= 0){
                    $_SESSION['errEmpty'] = 'No. Surat tidak valid, silakan pilih tanggal ulang!';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    // ============================================================
                    // Recalculate no_surat server-side (keamanan, tidak bergantung
                    // pada input hidden dari client)
                    // ============================================================
                    $tgl_safe = mysqli_real_escape_string($config, $tgl_surat);

                    $q_cek = mysqli_query($config, "SELECT no_surat FROM tbl_sprint WHERE tgl_surat = '$tgl_safe' LIMIT 1");
                    if(mysqli_num_rows($q_cek) > 0){
                        $r_cek = mysqli_fetch_assoc($q_cek);
                        $final_no = $r_cek['no_surat'];
                    } else {
                        $q_max_before = mysqli_query($config, "SELECT MAX(no_surat) as max_no FROM tbl_sprint WHERE tgl_surat < '$tgl_safe'");
                        $r_max_before = mysqli_fetch_assoc($q_max_before);
                        $max_before = (int)$r_max_before['max_no'];
                        
                        $q_min_after = mysqli_query($config, "SELECT MIN(no_surat) as min_no FROM tbl_sprint WHERE tgl_surat > '$tgl_safe'");
                        $r_min_after = mysqli_fetch_assoc($q_min_after);
                        $min_after = $r_min_after['min_no'];
                        
                        if ($min_after === null) {
                            // Tanggal ini adalah tanggal paling baru (bukan backdate)
                            if ($max_before == 0) {
                                $final_no = 1;
                            } else {
                                $final_no = $max_before + 20; // Kasih jarak 20 untuk antisipasi backdate
                            }
                        } else {
                            // Tanggal backdate (mundur)
                            if ($max_before == 0) {
                                $final_no = 1;
                            } else {
                                $final_no = $max_before + 1; // Ambil 1 angka setelah tanggal sebelumnya
                            }
                        }
                    }

                    // Insert ke database
                    $query = mysqli_query($config,
                        "INSERT INTO tbl_sprint
                            (no_surat, tgl_surat, perihal, keterangan, asal_surat, tujuan_surat, id_user)
                         VALUES
                            ('$final_no','$tgl_safe','$perihal','$keterangan','$asal_surat','$tujuan_surat','$id_user')"
                    );

                    if($query == true){
                        $_SESSION['succAdd'] = 'SUKSES! Data Sprint berhasil ditambahkan (No. Surat: '.$final_no.')';
                        header("Location: ./admin.php?page=sprint");
                        die();
                    } else {
                        $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query database';
                        echo '<script language="javascript">window.history.back();</script>';
                    }
                }
            }

        } else { ?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light"><a href="?page=sprint&act=add" class="judul">Tambah Data Sprint</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
                // Tampilkan pesan error
                $errorKeys = ['errQ','errEmpty','tgl_surat'];
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

            <!-- Panduan Penomoran -->
            <div class="row" style="margin-bottom: 0;">
                <div class="col s12">
                    <div class="card teal lighten-5" style="border-left: 4px solid #009688; margin-bottom: 0;">
                        <div class="card-content" style="padding: 10px 20px;">
                            <p style="font-size:0.95rem; color:#00695c; margin:0;">
                                <i class="material-icons" style="vertical-align:middle; font-size:1.1rem;">info</i>
                                <strong>Info Penomoran Surat:</strong>
                                Nomor surat diisi otomatis berdasarkan tanggal yang dipilih.
                                Jika di tanggal yang sama, no. surat akan <strong>sama</strong>.
                                Setiap tanggal baru mendapat blok nomor urut berikutnya (+20).
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row form Start -->
            <div class="row jarak-form">

                <!-- Form START -->
                <form class="col s12" method="POST" action="?page=sprint&act=add" id="form-sprint">

                    <!-- Row in form START -->
                    <div class="row">

                        <!-- Tanggal Surat (trigger utama) -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="tgl_surat" type="text" name="tgl_surat" class="validate" required>
                            <label for="tgl_surat">Tanggal Surat</label>
                        </div>
                        <div class="col s6" style="margin-top: -15px; margin-bottom: 15px; float: left; width: 50%;">
                            <small class="teal-text">
                                *Pilih tanggal terlebih dahulu, nomor surat akan terisi otomatis
                            </small>
                        </div>

                        <!-- No. Surat (readonly, auto-fill via AJAX) -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">tag</i>
                            <input id="no_surat" type="number" name="no_surat" class="validate"
                                   readonly placeholder="— Pilih tanggal dulu —"
                                   style="background:#f5f5f5; cursor:not-allowed; font-weight:bold; color:#009688;">
                            <label for="no_surat" class="active">No. Surat (Otomatis)</label>
                            <div id="no_surat_info" style="font-size:0.8rem; color:#777; margin-top:2px; margin-left:3rem;"></div>
                        </div>

                        <!-- Perihal -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">description</i>
                            <textarea id="perihal" class="materialize-textarea validate" name="perihal" required></textarea>
                            <label for="perihal">Perihal</label>
                        </div>

                        <!-- Keterangan -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">featured_play_list</i>
                            <input id="keterangan" type="text" class="validate" name="keterangan" required>
                            <label for="keterangan">Keterangan</label>
                        </div>

                        <!-- Asal Surat -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">place</i>
                            <input id="asal_surat" type="text" class="validate" name="asal_surat" required>
                            <label for="asal_surat">Asal Surat</label>
                        </div>

                        <!-- Tujuan Surat -->
                        <div class="input-field col s6">
                            <i class="material-icons prefix md-prefix">send</i>
                            <input id="tujuan_surat" type="text" class="validate" name="tujuan_surat" required>
                            <label for="tujuan_surat">Tujuan Surat</label>
                        </div>

                    </div>
                    <!-- Row in form END -->

                    <div class="row">
                        <div class="col s12" style="margin-top:10px;">
                            <button type="submit" name="submit" id="btn-simpan"
                                    class="btn-large teal waves-effect waves-light" disabled>
                                SIMPAN <i class="material-icons">done</i>
                            </button>
                            &nbsp;
                            <a href="?page=sprint" class="btn-large deep-orange waves-effect waves-light">
                                BATAL <i class="material-icons">clear</i>
                            </a>
                        </div>
                    </div>

                </form>
                <!-- Form END -->

            </div>
            <!-- Row form END -->

            <!-- SCRIPT: AJAX auto-fill no. surat (kompatibel dengan Materialize pickadate) -->
            <script>
            function fetchNoSuratSprint(tgl){
                if(!tgl){ return; }

                var noSuratInput = document.getElementById('no_surat');
                var infoDiv      = document.getElementById('no_surat_info');
                var btnSimpan    = document.getElementById('btn-simpan');

                // Tampilkan loading
                noSuratInput.value = '';
                infoDiv.innerHTML  = '<span style="color:#ff9800;"><i class="material-icons" style="font-size:0.9rem;vertical-align:middle;">hourglass_empty</i> Menghitung nomor surat...</span>';
                btnSimpan.disabled = true;

                // Gunakan XMLHttpRequest agar kompatibel dengan semua browser di localhost
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_no_surat_sprint.php?tgl=' + encodeURIComponent(tgl), true);
                xhr.onreadystatechange = function(){
                    if(xhr.readyState === 4){
                        if(xhr.status === 200){
                            try {
                                var data = JSON.parse(xhr.responseText);
                                if(data.error){
                                    infoDiv.innerHTML = '<span style="color:red;">Error: ' + data.error + '</span>';
                                    return;
                                }
                                noSuratInput.value = data.no_surat;
                                var ns         = parseInt(data.no_surat);
                                infoDiv.innerHTML = '<span style="color:#009688;">'
                                    + '<i class="material-icons" style="font-size:0.9rem;vertical-align:middle;">check_circle</i> '
                                    + 'No. Surat: <strong>' + ns + '</strong> '
                                    + '</span>';
                                btnSimpan.disabled = false;
                            } catch(e) {
                                infoDiv.innerHTML = '<span style="color:red;">Gagal membaca respon server: ' + xhr.responseText + '</span>';
                            }
                        } else {
                            infoDiv.innerHTML = '<span style="color:red;">Koneksi gagal (Status: ' + xhr.status + ')</span>';
                        }
                    }
                };
                xhr.send();
            }

            // Tunggu jQuery siap
            (function checkJQueryReady() {
                if (window.jQuery) {
                    var tglInput = $('#tgl_surat');
                    var lastVal  = tglInput.val();
                    setInterval(function(){
                        var currentVal = tglInput.val();
                        if(currentVal !== lastVal){
                            lastVal = currentVal;
                            if(currentVal){
                                fetchNoSuratSprint(currentVal);
                            }
                        }
                    }, 300);
                    if (lastVal) fetchNoSuratSprint(lastVal);
                } else {
                    setTimeout(checkJQueryReady, 50);
                }
            })();
            </script>

<?php
        }
    }
?>
