<?php
include_once "vendor/autoload.php";
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

file_put_contents("barcodes/9780008256791@2398-1.png", $generator->getBarcode(2398, $generator::TYPE_EAN_13, 2, 100));

