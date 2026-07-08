<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
        header("Location: ./");
        die();
    } else {
        $id_surat = mysqli_real_escape_string($config, $_REQUEST['id_surat']);
        $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE id_surat='$id_surat'");
        
        if(mysqli_num_rows($query) > 0){
            $row_surat = mysqli_fetch_array($query);
            
            $query2 = mysqli_query($config, "SELECT * FROM tbl_disposisi WHERE id_surat='$id_surat'");
            if(mysqli_num_rows($query2) > 0){
                $row_disp = mysqli_fetch_array($query2);
            } else {
                // Default empty values if no disposisi yet
                $row_disp = array(
                    'no_agenda_kepala' => '',
                    'tanggal_diterima_kepala' => '',
                    'pukul' => '',
                    'tujuan_kabag_umum' => 0,
                    'tujuan_kabid_berantas' => 0,
                    'tujuan_katim_p2m' => 0,
                    'tujuan_katim_rehab' => 0,
                    'tindakan_file' => 0,
                    'tindakan_tindak_lanjuti' => 0,
                    'tindakan_pedomani' => 0,
                    'tindakan_teruskan' => 0,
                    'tindakan_penuhi' => 0,
                    'tindakan_jadwalkan' => 0,
                    'tindakan_acc' => 0,
                    'tindakan_wakil' => 0
                );
            }
?>
    <style>
        @page {
            size: A4;
            margin: 20mm 18mm;
        }
        * {
            box-sizing: border-box;
        }
        #lembar-disposisi {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px 30px;
            color: #000;
            background: #fff;
        }
        #lembar-disposisi .header-title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11.5pt;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: left;
        }
        #lembar-disposisi .main-title {
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16.5pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        #lembar-disposisi table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            color: #000;
        }
        #lembar-disposisi td, #lembar-disposisi th {
            border: 1px solid #000 !important;
            padding: 6px 8px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            vertical-align: top;
        }

        /* ===== Bagian 1: Info Surat + Perihal ===== */
        #lembar-disposisi .info-section {
            width: 58%;
            padding: 0;
            border-right: 1px solid #000 !important;
            vertical-align: top;
        }
        #lembar-disposisi .perihal-section {
            width: 42%;
            padding: 6px 8px;
            vertical-align: top;
            font-weight: bold;
        }

        #lembar-disposisi .info-inner-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        #lembar-disposisi .info-inner-table td {
            border: none !important;
            border-bottom: 1px solid #000 !important;
            padding: 5px 8px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            vertical-align: middle;
        }
        #lembar-disposisi .info-inner-table tr:last-child td {
            border-bottom: none !important;
        }
        #lembar-disposisi .info-label-col {
            width: 38%;
            text-align: left;
            white-space: nowrap;
        }
        #lembar-disposisi .info-colon-col {
            width: 4%;
            text-align: center;
        }
        #lembar-disposisi .info-value-col {
            width: 58%;
        }

        #lembar-disposisi .checkbox-box {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 8px;
            text-align: center;
            line-height: 14px;
            font-size: 14px;
            font-weight: bold;
        }

        /* ===== Bagian 2: KEPADA YTH. ===== */
        #lembar-disposisi .box {
            border: 1px solid #000;
            border-top: none;
            margin: 0;
            padding: 0;
        }
        #lembar-disposisi .box-header {
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding: 6px 8px;
        }
        #lembar-disposisi .box-main {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        #lembar-disposisi .box-left {
            display: table-cell;
            width: 58%;
            border-right: 1px solid #000;
            padding: 4px 8px;
            vertical-align: top;
        }
        #lembar-disposisi .box-right {
            display: table-cell;
            width: 42%;
            padding: 4px 8px;
            vertical-align: top;
        }
        #lembar-disposisi .check-row {
            display: flex;
            align-items: flex-start;
            padding: 5px 0;
            line-height: 1.3;
        }
        #lembar-disposisi .check-row span.checkbox-box {
            flex: 0 0 auto;
            margin-top: 2px;
        }
        #lembar-disposisi .check-row-text {
            flex: 1;
            margin-left: 4px;
            font-weight: bold;
        }

        /* ===== Bagian 3: DISPOSISI KEPALA BNNP ===== */
        #lembar-disposisi .disposisi-table {
            border-top: none;
        }
        #lembar-disposisi .disposisi-header {
            text-align: center;
            font-weight: bold;
        }
        #lembar-disposisi .disposisi-left {
            width: 58%;
            border-right: 1px solid #000 !important;
            padding: 0;
            margin: 0;
            vertical-align: top;
        }
        #lembar-disposisi .disposisi-row {
            display: flex;
            align-items: center;
            padding: 10px 8px;
            line-height: 1.3;
            border-bottom: 1px solid #000;
        }
        #lembar-disposisi .disposisi-row:last-child {
            border-bottom: none;
        }
        #lembar-disposisi .disposisi-num {
            width: 8%;
            text-align: left;
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
        }
        #lembar-disposisi .disposisi-label {
            flex: 1;
            padding-left: 6px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
        }
        #lembar-disposisi .disposisi-check {
            width: 30px;
            text-align: center;
            margin-left: 8px;
        }
        #lembar-disposisi .empty-cell {
            width: 42%;
            vertical-align: top;
        }

        @media print{
            body { background: #fff; }
            nav, header, footer, .button-collapse, .side-nav { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; }
            .container { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .bg::before { display: none !important; }
        }
    </style>

    <div id="lembar-disposisi">
        <div class="header-title">BNNP LAMPUNG</div>
        <div class="main-title">LEMBAR DISPOSISI</div>

        <!-- 1. Bagian Info Surat + Perihal -->
        <table>
            <tr>
                <td class="info-section">
                    <table class="info-inner-table">
                        <tr>
                            <td class="info-label-col">NO AGENDA SURAT</td>
                            <td class="info-colon-col">:</td>
                            <td class="info-value-col"><?php echo $row_surat['no_agenda']; ?></td>
                        </tr>
                        <tr>
                            <td class="info-label-col">NO AGENDA KEPALA</td>
                            <td class="info-colon-col">:</td>
                            <td class="info-value-col"><?php echo $row_disp['no_agenda_kepala']; ?></td>
                        </tr>
                        <tr>
                            <td class="info-label-col">DITERIMA TANGGAL</td>
                            <td class="info-colon-col">:</td>
                            <td class="info-value-col">
                                <?php echo !empty($row_surat['tgl_diterima']) ? indoDate($row_surat['tgl_diterima']) : ''; ?> 
                                <?php echo !empty($row_surat['pukul']) ? '&nbsp; PUKUL: ' . $row_surat['pukul'] : ''; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label-col">SURAT DARI</td>
                            <td class="info-colon-col">:</td>
                            <td class="info-value-col"><?php echo $row_surat['asal_surat']; ?></td>
                        </tr>
                        <tr>
                            <td class="info-label-col">NOMOR SURAT</td>
                            <td class="info-colon-col">:</td>
                            <td class="info-value-col"><?php echo $row_surat['no_surat']; ?></td>
                        </tr>
                        <tr>
                            <td class="info-label-col">TANGGAL SURAT</td>
                            <td class="info-colon-col">:</td>
                            <td class="info-value-col"><?php echo indoDate($row_surat['tgl_surat']); ?></td>
                        </tr>
                    </table>
                </td>
                <td class="perihal-section">PERIHAL :<br/><br/><span style="font-weight:normal;"><?php echo $row_surat['isi']; ?></span></td>
            </tr>
        </table>

        <!-- 2. Bagian KEPADA YTH -->
        <div class="box">
            <div class="box-header">KEPADA YTH.</div>
            <div class="box-main">
                <div class="box-left">
                    <div class="check-row">
                        <span class="checkbox-box"><?php echo ($row_disp['tujuan_kabag_umum'] == 1) ? '&#10004;' : ''; ?></span>
                        <span class="check-row-text">KEPALA BAGIAN UMUM</span>
                    </div>
                    <div class="check-row">
                        <span class="checkbox-box"><?php echo ($row_disp['tujuan_kabid_berantas'] == 1) ? '&#10004;' : ''; ?></span>
                        <span class="check-row-text">KEPALA BIDANG PEMBERATASAN &amp; INTELIJEN</span>
                    </div>
                    <div class="check-row">
                        <span class="checkbox-box"><?php echo ($row_disp['tujuan_katim_p2m'] == 1) ? '&#10004;' : ''; ?></span>
                        <span class="check-row-text">KATIM KERJA P2M</span>
                    </div>
                    <div class="check-row">
                        <span class="checkbox-box"><?php echo ($row_disp['tujuan_katim_rehab'] == 1) ? '&#10004;' : ''; ?></span>
                        <span class="check-row-text">KATIM KERJA REHABILITASI</span>
                    </div>
                </div>
                <div class="box-right"></div>
            </div>
        </div>

        <!-- 3. Bagian DISPOSISI KEPALA BNNP -->
        <table class="disposisi-table">
            <tr>
                <td class="disposisi-header" colspan="2">DISPOSISI KEPALA BNNP</td>
            </tr>
            <tr>
                <td class="disposisi-left">
                    <div class="disposisi-row">
                        <span class="disposisi-num">1</span>
                        <span class="disposisi-label">FILE</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_file'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                    <div class="disposisi-row">
                        <span class="disposisi-num">2</span>
                        <span class="disposisi-label">TINDAK LANJUTI</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_tindak_lanjuti'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                    <div class="disposisi-row">
                        <span class="disposisi-num">3</span>
                        <span class="disposisi-label">PEDOMANI</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_pedomani'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                    <div class="disposisi-row">
                        <span class="disposisi-num">4</span>
                        <span class="disposisi-label">TERUSKAN</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_teruskan'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                    <div class="disposisi-row">
                        <span class="disposisi-num">5</span>
                        <span class="disposisi-label">PENUHI</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_penuhi'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                    <div class="disposisi-row">
                        <span class="disposisi-num">6</span>
                        <span class="disposisi-label">JADWALKAN</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_jadwalkan'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                    <div class="disposisi-row">
                        <span class="disposisi-num">7</span>
                        <span class="disposisi-label">ACC</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_acc'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                    <div class="disposisi-row">
                        <span class="disposisi-num">8</span>
                        <span class="disposisi-label">WAKIL</span>
                        <span class="disposisi-check"><span class="checkbox-box" style="margin-right:0;"><?php echo ($row_disp['tindakan_wakil'] == 1) ? '&#10004;' : ''; ?></span></span>
                    </div>
                </td>
                <td class="empty-cell"></td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script>
<?php
        }
    }
?>
