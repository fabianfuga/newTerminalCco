<?php
/* Demonstration of available options on the pdf417Code() command*/
require __DIR__ . '/../autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$connector = new NetworkPrintConnector("printserver1.ddns.net", 9100);
$printer = new Printer($connector);

// Most simple example

$printer -> text("Most simple example\n");
$printer -> feed();



// Cut & close
$printer -> cut();
$printer -> close();



/*$fp = fsockopen("186.78.8.245", 9100, $errno, $errstr, 5);

if (!$fp) {

    echo "$errstr ($errno)<br />\n";

} else {

    $out = "GET / HTTP/1.1\r\n";

    $out .= "Host: www.example.com\r\n";

    $out .= "Connection: OK\r\n\r\n";

    fwrite($fp, $out);

    while (!feof($fp)) {

        echo fgets($fp, 128);

    }

    fclose($fp);

}*/

?>
