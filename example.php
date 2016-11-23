<?php
/**
 * User: chuano
 * Date: 22/11/2016
 * Time: 20:31
 */
require __DIR__ . '/vendor/autoload.php';
use chuano\FPDFCircleCharts;

$pdf = new FPDFCircleCharts();
$pdf->AddPage();

// Default color is green
$x = 10;
$y = 10;
$padding = 0;
$percentage = 35;
$circleWidth = 190;
$pdf->SetXY($x, $y);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 16);
$pdf->CircleChart($circleWidth, $padding, $percentage);

$x = 10;
$y = 200;
$color = array(255, 0, 0); // Red
$padding = 10;
$percentage = 65;
$circleWidth = 90;
$pdf->SetXY($x, $y);
$pdf->CircleChart($circleWidth, $padding, $percentage, $color);

$x = 110;
$y = 200;
$color = array(0, 0, 255); // Blue
$padding = 10;
$percentage = 75;
$circleWidth = 30;
$pdf->SetXY($x, $y);
$pdf->CircleChart($circleWidth, $padding, $percentage, $color);

$pdf->Output();