<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_host = "sql110.infinityfree.com";            // e.g. sql123.epizy.com
$db_user = "if0_40582300";            // e.g. epiz_12345678
$db_pass = "uj0krRpEXI";            // the one you set for the DB
$db_name = "if0_40582300_LandsOfLogic";                // e.g. epiz_12345678_landsoflogic

echo "Trying to connect...<br>";

$conn = mysqli_connect($db_host, $db_user, $db_pass);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected OK.<br>";

if (!mysqli_select_db($conn, $db_name)) {
    die("Database select failed: " . mysqli_error($conn));
}
echo "Database selected OK.<br>";

$result = mysqli_query($conn, "SHOW TABLES");
if (!$result) {
    die("SHOW TABLES failed: " . mysqli_error($conn));
}

echo "Tables:<br>";
while ($row = mysqli_fetch_row($result)) {
    echo "- " . htmlspecialchars($row[0]) . "<br>";
}

echo "<br>Done"
 ?>