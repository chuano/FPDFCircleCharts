# FPDFCircleCharts
Library that extends FPDF with circle percentage charts

![Screenshot](http://chuano.github.com/FPDFCircleCharts/screenshot/screenshot.jpg)

## Installation
```bash
$ composer require chuano/fpdf-circle-charts ~1.0
```

## Usage
```php
$pdf = new FPDFCircleCharts();
$pdf->AddPage();

// Chart width and height
$width = 190;
// Padding inside chart box
$padding = 10;
// Percentage completed
$percentage = 75;
// Draw the chart
$pdf->CircleChart($width, $padding, $percentage);

// Output
$pdf->Output();
```