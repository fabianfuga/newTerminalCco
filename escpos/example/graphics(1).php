<?php
/* Print-outs using the newer graphics print command */

require __DIR__ . '/../autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$connector = new NetworkPrintConnector("printserver1.ddns.net", 9100);
$printer = new Printer($connector);

try {
	$tux = EscposImage::load("resources/p.png", false);
    $printer -> graphics($tux, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);


} catch (Exception $e) {

    $printer -> text($e -> getMessage() . "\n");
}

$printer -> close();
