<?php
session_start();
include "dbcon.php";
include_once "../vendor/autoload.php";
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
} else {

    $ac_start = $_POST['as'];
    $ac_stop = $_POST['ae'];
    $id = $_POST['isbn'];
    $class = $_POST['class'];
    $key = $_POST['key'];
    $x = $ac_start;
    $count = $ac_stop - $ac_start + 1;

    $q = "SELECT sum(accession_stop - accession_start) FROM acession WHERE timestamp < (SELECT timestamp FROM acession WHERE c_key LIKE 'afbc87ce7ce34b98ff36a767dc641d326c578fbd') AND record_isbn like '9780007494682'";
    $r = $connection->query($q);
    $last_c = $r->fetch_array()[0];

    //  echo $last_c;

    $i = 1;
    try {
        while ($i <= $count) {
            file_put_contents("../barcodes/$id@$x-$i.png", $generator->getBarcode($x, $generator::TYPE_EAN_13, 2, 100));
            $x = $x + 1;
            $i++;
        }
        header("Location: printout?rid=" . $id . "&as=" . $ac_start . "&ae=" . $ac_stop . "&c=" . $class . "&key=" . $key . "");

    } catch (\Picqer\Barcode\Exceptions\BarcodeException $e) {
        echo "error";
    }


}