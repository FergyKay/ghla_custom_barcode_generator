<?php
session_start();
include "dbcon.php";
include_once "../vendor/autoload.php";
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
} else {
    $count = $_GET['count'];
    $la = $_GET['la'];
    $rid = $_GET['rid'];
    $last_c = $_GET['last_copy_num'];

    $x = $_GET['la'] - $count;
    $i = 1;
    $x = $x + 1;
    try {
        while ($i <= $count) {
            file_put_contents("../barcodes/$rid@$x-$last_c.png", $generator->getBarcode($x, $generator::TYPE_EAN_13, 2, 100));
            $x = $x + 1;
            $last_c = $last_c + 1;
            $i++;
        }

        $stmet = "INSERT INTO accession_control (accession_last_value) VALUES ('$la')";

        if ($result = $connection->query($stmet) == true) {
            header("Location: index?success");
        };

    } catch (\Picqer\Barcode\Exceptions\BarcodeException $e) {
    }
}