DROP TABLE tbl_disposisi;

CREATE TABLE `tbl_disposisi` (
  `id_disposisi` int(10) NOT NULL AUTO_INCREMENT,
  `tujuan` varchar(250) NOT NULL,
  `isi_disposisi` mediumtext NOT NULL,
  `sifat` varchar(100) NOT NULL,
  `batas_waktu` date NOT NULL,
  `catatan` varchar(250) NOT NULL,
  `id_surat` int(10) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  `no_agenda_kepala` varchar(50) NOT NULL,
  `tanggal_diterima_kepala` date DEFAULT NULL,
  `pukul` varchar(20) NOT NULL,
  `tujuan_kabag_umum` tinyint(1) NOT NULL DEFAULT 0,
  `tujuan_kabid_berantas` tinyint(1) NOT NULL DEFAULT 0,
  `tujuan_katim_p2m` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_file` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_tindak_lanjuti` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_pedomani` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_acc` tinyint(1) NOT NULL DEFAULT 0,
  `tujuan_katim_rehab` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_teruskan` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_penuhi` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_jadwalkan` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_wakil` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_disposisi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbl_disposisi VALUES("1","","-","Biasa","0000-00-00","akldklad","1","1","1","2026-07-08","","1","0","0","0","0","0","0","0","0","1","0","0");



DROP TABLE tbl_instansi;

CREATE TABLE `tbl_instansi` (
  `id_instansi` tinyint(1) NOT NULL,
  `institusi` varchar(150) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `status` varchar(150) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `kepsek` varchar(50) NOT NULL,
  `nip` varchar(25) NOT NULL,
  `website` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `logo` varchar(250) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_instansi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbl_instansi VALUES("1","Badan Narkotika Nasional","BADAN NARKOTIKA NASIONAL PROVINSI LAMPUNG","Terakreditasi A","Jalan Ikan Bawal No. 92 Teluk Betung - Bandar Lampung Kode Pos 35221","Rudi","-","https://masrud.com","email@masrud.com","Logo_BNN.png","1");



DROP TABLE tbl_klasifikasi;

CREATE TABLE `tbl_klasifikasi` (
  `id_klasifikasi` int(5) NOT NULL AUTO_INCREMENT,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `uraian` mediumtext NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_klasifikasi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




DROP TABLE tbl_sett;

CREATE TABLE `tbl_sett` (
  `id_sett` tinyint(1) NOT NULL,
  `surat_masuk` tinyint(2) NOT NULL,
  `surat_keluar` tinyint(2) NOT NULL,
  `referensi` tinyint(2) NOT NULL,
  `disposisi` tinyint(2) NOT NULL DEFAULT 10,
  `id_user` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_sett`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbl_sett VALUES("1","10","10","10","10","1");



DROP TABLE tbl_sprint;

CREATE TABLE `tbl_sprint` (
  `id_sprint` int(10) NOT NULL AUTO_INCREMENT,
  `no_surat` int(10) NOT NULL,
  `tgl_surat` date NOT NULL,
  `perihal` mediumtext NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `asal_surat` varchar(250) NOT NULL,
  `tujuan_surat` varchar(250) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_sprint`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbl_sprint VALUES("9","1","2026-07-10","jdjdj","kajda","dkajdka","dakdja","1");
INSERT INTO tbl_sprint VALUES("10","1","2026-07-10","djaskjd","djkajdk","dakkdjadj","djkajd","1");
INSERT INTO tbl_sprint VALUES("11","21","2026-07-11","kdlakd","lakdl","lakdal","daldk","1");
INSERT INTO tbl_sprint VALUES("12","41","2026-07-13","awikwok","awikwok","awikwokoakowk","koawkdoaokdaoda","1");



DROP TABLE tbl_surat_keluar;

CREATE TABLE `tbl_surat_keluar` (
  `id_surat` int(10) NOT NULL AUTO_INCREMENT,
  `no_agenda` int(10) NOT NULL,
  `tujuan` varchar(250) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `isi` mediumtext NOT NULL,
  `kode` varchar(30) NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_catat` date NOT NULL,
  `file` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




DROP TABLE tbl_surat_masuk;

CREATE TABLE `tbl_surat_masuk` (
  `id_surat` int(10) NOT NULL AUTO_INCREMENT,
  `no_agenda` int(10) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `asal_surat` varchar(250) NOT NULL,
  `isi` mediumtext NOT NULL,
  `kode` varchar(30) NOT NULL,
  `indeks` varchar(30) NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_diterima` date NOT NULL,
  `pukul` varchar(20) NOT NULL,
  `file` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_surat`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbl_surat_masuk VALUES("1","1","123","SA","weqeqe","123","313","2026-07-08","2026-07-08","","","qeqeqe","1");



DROP TABLE tbl_user;

CREATE TABLE `tbl_user` (
  `id_user` tinyint(2) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(35) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nip` varchar(25) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO tbl_user VALUES("1","masrud","7d05dc02abe9cda729d0c798c886db47","Admin","-","1");
INSERT INTO tbl_user VALUES("2","kolonel","77946eaac8106422afece81f973a9509","kolonel","23312199","2");



