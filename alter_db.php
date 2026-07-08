<?php
$config = mysqli_connect("localhost", "root", "", "ams_native");
if (!$config) {
    die("Connection failed: " . mysqli_connect_error());
}

$queries = [
    "ALTER TABLE tbl_disposisi ADD COLUMN tujuan_katim_rehab tinyint(1) NOT NULL DEFAULT 0",
    "ALTER TABLE tbl_disposisi ADD COLUMN tindakan_teruskan tinyint(1) NOT NULL DEFAULT 0",
    "ALTER TABLE tbl_disposisi ADD COLUMN tindakan_penuhi tinyint(1) NOT NULL DEFAULT 0",
    "ALTER TABLE tbl_disposisi ADD COLUMN tindakan_jadwalkan tinyint(1) NOT NULL DEFAULT 0",
    "ALTER TABLE tbl_disposisi ADD COLUMN tindakan_wakil tinyint(1) NOT NULL DEFAULT 0"
];

foreach($queries as $q) {
    mysqli_query($config, $q);
    echo mysqli_error($config) . "\n";
}
echo "Done";
?>
