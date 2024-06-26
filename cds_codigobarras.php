<?php
// Including all required classes
require('class/BCGFont.php');
require('class/BCGColor.php');
require('class/BCGDrawing.php'); 

// Including the barcode technology
include('class/BCGean13.barcode.php'); 

// Loading Font
$font = new BCGFont('./class/font/Arial.ttf',12);

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255); 
$claveunicaver=$_GET["claveunica"];
$code = new BCGean13();
$code->setScale(2); // Resolution
$code->setThickness(20); // Thickness
$code->setForegroundColor($color_black); // Color of bars
$code->setBackgroundColor($color_white); // Color of spaces
//$code->setFont($font); // Font (or 0)
$code->parse($claveunicaver); // Text


/* Here is the list of the arguments
1 - Filename (empty : display on screen)
2 - Background color */
$drawing = new BCGDrawing('', $color_white);
$drawing->setBarcode($code);
$drawing->draw();

// Header that says it is an image (remove it if you save the barcode to a file)
header('Content-Type: image/png');

// Draw (or save) the image into PNG format.
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
?>