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
    $last_c = 0;
    $q = "SELECT SUM(quantity) FROM record WHERE timestamp < (SELECT timestamp FROM record where record_isbn LIKE '$id' and c_key LIKE '$key') AND record_isbn like '$id' ";
    $r = $connection->query($q);
    $last_c = $r->fetch_array()[0];

    $last_c = $last_c + 1;

    $i = 1;
    try {
        while ($i <= $count) {
            file_put_contents("../barcodes/$id@$x-$last_c.png", $generator->getBarcode($x, $generator::TYPE_EAN_13, 2, 100));
            $x = $x + 1;

            $last_c++;
            $i++;
        }
        header("Location: printout?rid=" . $id . "&as=" . $ac_start . "&ae=" . $ac_stop . "&c=" . $class . "&key=" . $key . "");

    } catch (\Picqer\Barcode\Exceptions\BarcodeException $e) {
        echo "error";
    }


}