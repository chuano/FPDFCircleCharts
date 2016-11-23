<?php
namespace chuano;


use FPDF;

/**
 * FPDF Extension with charts
 */
class FPDFCircleCharts extends FPDF
{
    /**
     * Prints a circle chart with completed percent inked
     *
     * @param integer $width
     * @param integer $margin
     * @param float   $percent
     * @param string  $title
     * @param array   $color
     */
    function CircleChart($width, $padding, $percent, $color = array(0, 155, 0))
    {
        // Get state and deactivate autopage break temporary
        $oldStateAutoPage = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false);


        // Round percent
        $percent = round($percent);

        // Calc size and position for circle chart
        $x = $this->GetX();
        $y = $this->GetY();
        $radius = floor(($width - $padding) / 2);
        $XDiag = $x + $radius + ($padding / 2);
        $YDiag = $y + $radius + ($padding / 2);

        // Print empty gray circle
        $this->SetLineWidth(0.0);
        $this->SetFillColor(155, 155, 155);
        $this->SetDrawColor(155, 155, 155);
        $this->Sector($XDiag, $YDiag, $radius, 0, 360);

        // Prints completed percent pie
        $this->SetLineWidth(0.0);
        $this->SetFillColor($color[0], $color[1], $color[2]);
        $this->SetDrawColor($color[0], $color[1], $color[2]);
        $angle = (($percent * 360) / doubleval(100));
        $angleStart = 0;
        if ($angle != 0) {
            $angleEnd = $angleStart + $angle;
            $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
        }

        // Prints an empty white circle over the pies for place the completed percent
        $this->SetXY($x, $y);
        $this->SetLineWidth(0.0);
        $this->SetFillColor(255, 255, 255);
        $this->SetDrawColor(255, 255, 255);
        $this->Sector($XDiag, $YDiag, $radius - (($radius * 15) / 100), 0, 360);

        // Prints the completed percent number
        $this->SetXY($x + ($padding / 2), $y + ($padding / 2));
        $this->SetDrawColor(0, 0, 0);
        $this->SetFont('Arial', '', $width / 1.6); // Use the circle with as reference to calc the font size
        $this->Cell($width - $padding, $width - $padding, $percent . "%", 0, 1, 'C');

        // Reactivate autopagebreak if it was setted
        $this->SetAutoPageBreak($oldStateAutoPage);
    }

    /**
     * Prints a circle sector
     * FPDF Sector from http://www.fpdf.org/en/script/script19.php
     * Author: Maxime Delorme
     * License: FPDF
     *
     * @param           $xc
     * @param           $yc
     * @param           $r
     * @param           $a
     * @param           $b
     * @param string    $style
     * @param bool|true $cw
     * @param int       $o
     */
    public function Sector($xc, $yc, $r, $a, $b, $style = 'FD', $cw = true, $o = 90)
    {
        $d0 = $a - $b;
        if ($cw) {
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        } else {
            $b += $o;
            $a += $o;
        }
        while ($a < 0)
            $a += 360;
        while ($a > 360)
            $a -= 360;
        while ($b < 0)
            $b += 360;
        while ($b > 360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $b / 360 * 2 * M_PI;
        $a = $a / 360 * 2 * M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0)
            $d = 2 * M_PI;
        $k = $this->k;
        $hp = $this->h;
        if (sin($d / 2))
            $MyArc = 4 / 3 * (1 - cos($d / 2)) / sin($d / 2) * $r;
        else
            $MyArc = 0;
        //first put the center
        $this->_out(sprintf('%.2F %.2F m', ($xc) * $k, ($hp - $yc) * $k));
        //put the first point
        $this->_out(sprintf('%.2F %.2F l', ($xc + $r * cos($a)) * $k, (($hp - ($yc - $r * sin($a))) * $k)));
        //draw the arc
        if ($d < M_PI / 2) {
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
        } else {
            $b = $a + $d / 4;
            $MyArc = 4 / 3 * (1 - cos($d / 8)) / sin($d / 8) * $r;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
        }
        //terminate drawing
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'b';
        else
            $op = 's';
        $this->_out($op);
    }

    /**
     * FPDF Sector from http://www.fpdf.org/en/script/script19.php
     * Author: Maxime Delorme
     * License: FPDF
     *
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $x3
     * @param $y3
     */
    public function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k));
    }
}