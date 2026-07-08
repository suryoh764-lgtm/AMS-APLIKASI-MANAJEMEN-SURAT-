<?php
$config = mysqli_connect("localhost", "root", "", "ams_native");
if (!$config) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($config, "SHOW COLUMNS FROM tbl_disposisi");
if (!$result) {
    echo 'Could not run query: ' . mysqli_error($config);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . " - " . $row['Type'] . "<br>\n";
    }
}
?>
