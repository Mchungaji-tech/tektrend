<?php
/**
 * QrCode - Dependency-free QR code generator (byte mode, versions 1-40, EC L/M/Q/H)
 *
 * Ported from the well-known reference algorithm (Nayuki, public domain).
 * Produces a module matrix and can render to SVG, PNG (via GD) and vector PDF.
 * No external libraries or network calls are used, satisfying the project's
 * "dependency-free PHP hosting" constraint.
 */

class QrCode {
    // Error correction levels -> format bits (per QR spec)
    const ECC_FORMAT_BITS = ['L' => 1, 'M' => 0, 'Q' => 3, 'H' => 2];

    // Number of error correction codewords per block, indexed [ecl][version] (ecl: 0=L,1=M,2=Q,3=H)
    private static $ECC_CODEWORDS_PER_BLOCK = [
        // Version: 0(padding)  1   2   3   4   5   6   7   8   9  10  11  12  13  14  15  16  17  18  19  20  21  22  23  24  25  26  27  28  29  30  31  32  33  34  35  36  37  38  39  40
        [-1,  7, 10, 15, 20, 26, 18, 20, 24, 30, 18, 20, 24, 26, 30, 22, 24, 28, 30, 28, 28, 28, 28, 30, 30, 26, 28, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30], // Low
        [-1, 10, 16, 26, 18, 24, 16, 18, 22, 22, 26, 30, 22, 22, 24, 24, 28, 28, 26, 26, 26, 26, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28], // Medium
        [-1, 13, 22, 18, 26, 18, 24, 18, 22, 20, 24, 28, 26, 24, 20, 30, 24, 28, 28, 26, 30, 28, 30, 30, 30, 30, 28, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30], // Quartile
        [-1, 17, 28, 22, 16, 22, 28, 26, 26, 24, 28, 24, 28, 22, 24, 24, 30, 28, 28, 26, 28, 30, 24, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30], // High
    ];

    // Number of error correction blocks, indexed [ecl][version]
    private static $NUM_ERROR_CORRECTION_BLOCKS = [
        // Version: 0  1  2  3  4  5  6  7  8  9 10  11  12  13  14  15  16  17  18  19  20  21  22  23  24  25  26  27  28  29  30  31  32  33  34  35  36  37  38  39  40
        [-1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 4,  4,  4,  4,  4,  6,  6,  6,  6,  7,  8,  8,  9,  9, 10, 12, 12, 12, 13, 14, 15, 16, 17, 18, 19, 19, 20, 21, 22, 24, 25], // Low
        [-1, 1, 1, 1, 2, 2, 4, 4, 4, 5, 5,  5,  8,  9,  9, 10, 10, 11, 13, 14, 16, 17, 17, 18, 20, 21, 23, 25, 26, 28, 29, 31, 33, 35, 37, 38, 40, 43, 45, 47, 49], // Medium
        [-1, 1, 1, 2, 2, 4, 4, 6, 6, 8, 8,  8, 10, 12, 16, 12, 17, 16, 18, 21, 20, 23, 23, 25, 27, 29, 34, 34, 35, 38, 40, 43, 45, 48, 51, 53, 56, 59, 62, 65, 68], // Quartile
        [-1, 1, 1, 2, 4, 4, 4, 5, 6, 8, 8, 11, 11, 16, 16, 18, 16, 19, 21, 25, 25, 25, 34, 30, 32, 35, 37, 40, 42, 45, 48, 51, 54, 57, 60, 63, 66, 70, 74, 77, 81], // High
    ];

    private static $PENALTY_N1 = 3;
    private static $PENALTY_N2 = 3;
    private static $PENALTY_N3 = 40;
    private static $PENALTY_N4 = 10;

    private $version;
    private $size;
    private $ecl;
    private $modules = [];   // bool[y][x] true = dark
    private $isFunction = []; // bool[y][x]

    private function __construct($version, $ecl, $dataCodewords, $mask) {
        $this->version = $version;
        $this->ecl = $ecl;
        $this->size = $version * 4 + 17;
        for ($y = 0; $y < $this->size; $y++) {
            $this->modules[$y] = array_fill(0, $this->size, false);
            $this->isFunction[$y] = array_fill(0, $this->size, false);
        }
        $this->drawFunctionPatterns();
        $codewords = $this->addEccAndInterleave($dataCodewords);
        $this->drawCodewords($codewords);
        $this->applyMask($mask);
        $this->drawFormatBits($mask);
    }

    /**
     * Encode arbitrary text/URL bytes into a QrCode using byte mode.
     */
    public static function encode($text, $ecl = 'M') {
        if (!isset(self::ECC_FORMAT_BITS[$ecl])) {
            $ecl = 'M';
        }
        $eclIdx = array_search($ecl, ['L', 'M', 'Q', 'H'], true);
        $data = $text;
        $dataLen = strlen($data);

        // Select the smallest version that fits
        $version = 0;
        for ($ver = 1; $ver <= 40; $ver++) {
            $capacityBits = self::getNumDataCodewords($ver, $eclIdx) * 8;
            $ccBits = ($ver <= 9) ? 8 : 16;
            $needed = 4 + $ccBits + 8 * $dataLen;
            if ($needed <= $capacityBits) {
                $version = $ver;
                break;
            }
        }
        if ($version === 0) {
            throw new Exception('Data too long for a QR code (max ~1273 bytes at level M).');
        }

        // Build the bit buffer
        $bits = [];
        self::appendBits(4, 4, $bits);                       // byte mode indicator 0100
        self::appendBits($dataLen, ($version <= 9) ? 8 : 16, $bits);
        for ($i = 0; $i < $dataLen; $i++) {
            self::appendBits(ord($data[$i]), 8, $bits);
        }

        // Terminator (up to 4 zero bits)
        $capacityBits = self::getNumDataCodewords($version, $eclIdx) * 8;
        $terminator = min(4, $capacityBits - count($bits));
        self::appendBits(0, $terminator, $bits);

        // Pad to byte boundary
        self::appendBits(0, (8 - count($bits) % 8) % 8, $bits);

        // Pad bytes 0xEC / 0x11 alternating
        for ($pad = 0xEC; count($bits) < $capacityBits; $pad ^= 0xEC ^ 0x11) {
            self::appendBits($pad, 8, $bits);
        }

        // Bits -> codewords
        $dataCodewords = [];
        for ($i = 0; $i * 8 < count($bits); $i++) {
            $byte = 0;
            for ($j = 0; $j < 8; $j++) {
                $byte = ($byte << 1) | ($bits[$i * 8 + $j] & 1);
            }
            $dataCodewords[] = $byte;
        }

        // Pick the best mask by building each candidate and scoring penalties
        $bestMask = 0;
        $bestScore = PHP_INT_MAX;
        $bestCode = null;
        for ($m = 0; $m <= 7; $m++) {
            $code = new QrCode($version, $eclIdx, $dataCodewords, $m);
            $score = $code->getPenaltyScore();
            if ($score < $bestScore) {
                $bestScore = $score;
                $bestMask = $m;
                $bestCode = $code;
            }
        }
        return $bestCode;
    }

    public function getMatrix() {
        return $this->modules;
    }

    public function getModuleCount() {
        return $this->size;
    }

    public function getVersion() {
        return $this->version;
    }

    // ---------------------------------------------------------------------
    // Rendering
    // ---------------------------------------------------------------------

    /**
     * Render as a standalone SVG document.
     */
    public function toSvg($scale = 8, $margin = 4, $dark = '#000000', $light = '#ffffff') {
        $n = $this->size;
        $dim = ($n + 2 * $margin) * $scale;
        $parts = [];
        $parts[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $parts[] = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="' . $dim . '" height="' . $dim . '" viewBox="0 0 ' . $dim . ' ' . $dim . '" shape-rendering="crispEdges">';
        $parts[] = '<rect width="100%" height="100%" fill="' . $light . '"/>';
        // Build a single path for all dark modules (efficient)
        $d = '';
        for ($y = 0; $y < $n; $y++) {
            for ($x = 0; $x < $n; $x++) {
                if ($this->modules[$y][$x]) {
                    $px = ($x + $margin) * $scale;
                    $py = ($y + $margin) * $scale;
                    $d .= 'M' . $px . ',' . $py . 'h' . $scale . 'v' . $scale . 'h-' . $scale . 'z';
                }
            }
        }
        $parts[] = '<path d="' . $d . '" fill="' . $dark . '"/>';
        $parts[] = '</svg>';
        return implode("\n", $parts);
    }

    /**
     * Render as PNG binary using GD (falls back to exception if GD is missing).
     */
    public function toPng($scale = 8, $margin = 4) {
        if (!function_exists('imagecreatetruecolor')) {
            throw new Exception('PNG output requires the PHP GD extension.');
        }
        $n = $this->size;
        $dim = ($n + 2 * $margin) * $scale;
        $img = imagecreatetruecolor($dim, $dim);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefilledrectangle($img, 0, 0, $dim, $dim, $white);
        for ($y = 0; $y < $n; $y++) {
            for ($x = 0; $x < $n; $x++) {
                if ($this->modules[$y][$x]) {
                    $px = ($x + $margin) * $scale;
                    $py = ($y + $margin) * $scale;
                    imagefilledrectangle($img, $px, $py, $px + $scale - 1, $py + $scale - 1, $black);
                }
            }
        }
        ob_start();
        imagepng($img);
        $bin = ob_get_clean();
        imagedestroy($img);
        return $bin;
    }

    /**
     * Draw the QR directly onto an existing GD image as filled rectangles.
     * Used to compose the QR into the business card raster.
     */
    public function drawOnImage($img, $leftX, $topY, $boxPx, $margin = 2) {
        $n = $this->size;
        $eff = $n + 2 * $margin;
        $scale = (int)floor($boxPx / $eff);
        if ($scale < 1) $scale = 1;
        $realSize = $scale * $eff;
        $offsetX = $leftX + (int)(($boxPx - $realSize) / 2);
        $offsetY = $topY + (int)(($boxPx - $realSize) / 2);
        $black = imagecolorallocate($img, 0, 0, 0);
        for ($y = 0; $y < $n; $y++) {
            for ($x = 0; $x < $n; $x++) {
                if ($this->modules[$y][$x]) {
                    $px = $offsetX + ($x + $margin) * $scale;
                    $py = $offsetY + ($y + $margin) * $scale;
                    imagefilledrectangle($img, $px, $py, $px + $scale - 1, $py + $scale - 1, $black);
                }
            }
        }
    }

    /**
     * Render as a vector PDF (crisp black rectangles). Returns PDF binary.
     */
    public function toPdf($moduleSize = 8, $margin = 4, $label = '') {
        $n = $this->size;
        $dimModules = $n + 2 * $margin;
        $page = (int)ceil($dimModules * $moduleSize) + ($label ? 40 : 0);
        $W = (int)ceil($dimModules * $moduleSize);
        $H = $page;

        $content = "q\n1 1 1 rg\n0 0 {$W} {$H} re f\n0 0 0 rg\n";
        $topOffset = $label ? 40 : 0; // leave room at the top for a label
        for ($y = 0; $y < $n; $y++) {
            for ($x = 0; $x < $n; $x++) {
                if ($this->modules[$y][$x]) {
                    $px = ($x + $margin) * $moduleSize;
                    // flip y so module row 0 sits at top of the QR block
                    $py = $H - $topOffset - (($y + $margin) + 1) * $moduleSize;
                    $pw = $moduleSize;
                    $content .= sprintf('%F %F %F %F re f', $px, $py, $pw, $pw) . "\n";
                }
            }
        }
        if ($label !== '') {
            $safe = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $label);
            $content .= "BT\n/F1 14 Tf\n20 " . ($H - 24) . " Td\n(" . $safe . ") Tj\nET\n";
        }
        $content .= "Q\n";

        $pdf = new Pdf();
        $pdf->addVectorPage($W, $H, $content, !empty($label));
        return $pdf->output();
    }

    // ---------------------------------------------------------------------
    // Encoding internals
    // ---------------------------------------------------------------------

    private static function appendBits($val, $len, &$bits) {
        for ($i = $len - 1; $i >= 0; $i--) {
            $bits[] = ($val >> $i) & 1;
        }
    }

    private static function getNumRawDataModules($ver) {
        $result = (16 * $ver + 128) * $ver + 64;
        if ($ver >= 2) {
            $numAlign = (int)($ver / 7) + 2;
            $result -= (25 * $numAlign - 10) * $numAlign - 55;
            if ($ver >= 7) {
                $result -= 36;
            }
        }
        return $result;
    }

    private static function getNumDataCodewords($ver, $eclIdx) {
        return intdiv(self::getNumRawDataModules($ver), 8)
            - self::$ECC_CODEWORDS_PER_BLOCK[$eclIdx][$ver]
            * self::$NUM_ERROR_CORRECTION_BLOCKS[$eclIdx][$ver];
    }

    private function addEccAndInterleave($data) {
        $ver = $this->version;
        $eclIdx = $this->ecl;
        $numBlocks = self::$NUM_ERROR_CORRECTION_BLOCKS[$eclIdx][$ver];
        $blockEccLen = self::$ECC_CODEWORDS_PER_BLOCK[$eclIdx][$ver];
        $rawCodewords = intdiv(self::getNumRawDataModules($ver), 8);
        $numShortBlocks = $numBlocks - $rawCodewords % $numBlocks;
        $shortBlockLen = intdiv($rawCodewords, $numBlocks);

        $blocks = [];
        $rsDiv = self::reedSolomonComputeDivisor($blockEccLen);
        $k = 0;
        for ($i = 0; $i < $numBlocks; $i++) {
            $datLen = $shortBlockLen - $blockEccLen + ($i < $numShortBlocks ? 0 : 1);
            $dat = array_slice($data, $k, $datLen);
            $k += $datLen;
            // block = shortBlockLen + 1 slots: [data][optional pad zero][ecc]
            $block = array_fill(0, $shortBlockLen + 1, 0);
            for ($t = 0; $t < count($dat); $t++) {
                $block[$t] = $dat[$t];
            }
            $ecc = self::reedSolomonComputeRemainder($dat, $rsDiv);
            for ($t = 0; $t < $blockEccLen; $t++) {
                $block[$shortBlockLen + 1 - $blockEccLen + $t] = $ecc[$t];
            }
            $blocks[] = $block;
        }

        // Interleave: column-major over each block, skipping the pad byte of short blocks
        $result = [];
        for ($i = 0; $i < $shortBlockLen + 1; $i++) {
            for ($j = 0; $j < $numBlocks; $j++) {
                if ($i != $shortBlockLen - $blockEccLen || $j >= $numShortBlocks) {
                    $result[] = $blocks[$j][$i];
                }
            }
        }
        return $result;
    }

    private function drawCodewords($data) {
        $i = 0;
        $totalBits = count($data) * 8;
        for ($right = $this->size - 1; $right >= 1; $right -= 2) {
            if ($right == 6) $right = 5;
            for ($vert = 0; $vert < $this->size; $vert++) {
                for ($j = 0; $j < 2; $j++) {
                    $x = $right - $j;
                    $upward = ((($right + 1) & 2) == 0);
                    $y = $upward ? $this->size - 1 - $vert : $vert;
                    if (!$this->isFunction[$y][$x] && $i < $totalBits) {
                        $byte = $data[intdiv($i, 8)];
                        $bit = ($byte >> (7 - ($i & 7))) & 1;
                        $this->modules[$y][$x] = $bit === 1;
                        $i++;
                    }
                }
            }
        }
    }

    private function applyMask($msk) {
        for ($y = 0; $y < $this->size; $y++) {
            for ($x = 0; $x < $this->size; $x++) {
                if ($this->isFunction[$y][$x]) continue;
                $invert = false;
                switch ($msk) {
                    case 0: $invert = (($x + $y) % 2) == 0; break;
                    case 1: $invert = ($y % 2) == 0; break;
                    case 2: $invert = ($x % 3) == 0; break;
                    case 3: $invert = (($x + $y) % 3) == 0; break;
                    case 4: $invert = ((intdiv($x, 3) + intdiv($y, 2)) % 2) == 0; break;
                    case 5: $invert = (($x * $y) % 2 + ($x * $y) % 3) == 0; break;
                    case 6: $invert = (((($x * $y) % 2 + ($x * $y) % 3)) % 2) == 0; break;
                    case 7: $invert = ((((($x + $y) % 2) + ($x * $y) % 3)) % 2) == 0; break;
                }
                if ($invert) {
                    $this->modules[$y][$x] = !$this->modules[$y][$x];
                }
            }
        }
    }

    private function drawFunctionPatterns() {
        for ($i = 0; $i < $this->size; $i++) {
            $this->setFunctionModule(6, $i, $i % 2 == 0);
            $this->setFunctionModule($i, 6, $i % 2 == 0);
        }
        $this->drawFinderPattern(3, 3);
        $this->drawFinderPattern($this->size - 4, 3);
        $this->drawFinderPattern(3, $this->size - 4);

        $alignPatPos = $this->getAlignmentPatternPositions();
        $numAlign = count($alignPatPos);
        for ($i = 0; $i < $numAlign; $i++) {
            for ($j = 0; $j < $numAlign; $j++) {
                if (!(($i == 0 && $j == 0) || ($i == 0 && $j == $numAlign - 1) || ($i == $numAlign - 1 && $j == 0))) {
                    $this->drawAlignmentPattern($alignPatPos[$i], $alignPatPos[$j]);
                }
            }
        }

        $this->drawFormatBits(0);
        $this->drawVersion();
    }

    private function drawFormatBits($msk) {
        $eclLetter = ['L', 'M', 'Q', 'H'][$this->ecl];
        $data = self::ECC_FORMAT_BITS[$eclLetter] << 3 | $msk;
        $rem = $data;
        for ($i = 0; $i < 10; $i++) {
            $rem = ($rem << 1) ^ (($rem >> 9) * 0x537);
        }
        $bits = (($data << 10 | $rem) ^ 0x5412) & 0x7FFF;

        for ($i = 0; $i <= 5; $i++) $this->setFunctionModule(8, $i, (($bits >> $i) & 1) != 0);
        $this->setFunctionModule(8, 7, (($bits >> 6) & 1) != 0);
        $this->setFunctionModule(8, 8, (($bits >> 7) & 1) != 0);
        $this->setFunctionModule(7, 8, (($bits >> 8) & 1) != 0);
        for ($i = 9; $i < 15; $i++) $this->setFunctionModule(14 - $i, 8, (($bits >> $i) & 1) != 0);

        for ($i = 0; $i < 8; $i++) $this->setFunctionModule($this->size - 1 - $i, 8, (($bits >> $i) & 1) != 0);
        for ($i = 8; $i < 15; $i++) $this->setFunctionModule(8, $this->size - 15 + $i, (($bits >> $i) & 1) != 0);
        $this->setFunctionModule(8, $this->size - 8, true);
    }

    private function drawVersion() {
        if ($this->version < 7) return;
        $rem = $this->version;
        for ($i = 0; $i < 12; $i++) {
            $rem = ($rem << 1) ^ (($rem >> 11) * 0x1F25);
        }
        $bits = $this->version << 12 | $rem;
        for ($i = 0; $i < 18; $i++) {
            $color = (($bits >> $i) & 1) != 0;
            $a = $this->size - 11 + $i % 3;
            $b = intdiv($i, 3);
            $this->setFunctionModule($a, $b, $color);
            $this->setFunctionModule($b, $a, $color);
        }
    }

    private function drawFinderPattern($x, $y) {
        for ($dy = -4; $dy <= 4; $dy++) {
            for ($dx = -4; $dx <= 4; $dx++) {
                $dist = max(abs($dx), abs($dy));
                $xx = $x + $dx;
                $yy = $y + $dy;
                if ($xx >= 0 && $xx < $this->size && $yy >= 0 && $yy < $this->size) {
                    $this->setFunctionModule($xx, $yy, $dist != 2 && $dist != 4);
                }
            }
        }
    }

    private function drawAlignmentPattern($x, $y) {
        for ($dy = -2; $dy <= 2; $dy++) {
            for ($dx = -2; $dx <= 2; $dx++) {
                $this->setFunctionModule($x + $dx, $y + $dy, max(abs($dx), abs($dy)) != 1);
            }
        }
    }

    private function getAlignmentPatternPositions() {
        if ($this->version == 1) return [];
        $numAlign = intdiv($this->version, 7) + 2;
        // Spacing between alignment centres, rounded up to an even module count.
        // Version 32 is the one defined exception (fixed step of 26).
        $step = ($this->version == 32) ? 26
            : intdiv($this->version * 4 + $numAlign * 2 + 1, $numAlign * 2 - 2) * 2;
        // Coordinates are anchored at the LAST pattern (size - 7 = 4*version + 10)
        // and stepped backwards; the first is always 6. Building forward from 6
        // misplaces every pattern and makes the code unscannable.
        $result = array_fill(0, $numAlign, 0);
        for ($i = $numAlign - 1, $pos = $this->version * 4 + 10; $i >= 1; $i--, $pos -= $step) {
            $result[$i] = $pos;
        }
        $result[0] = 6;
        return $result;
    }

    private function setFunctionModule($x, $y, $isDark) {
        $this->modules[$y][$x] = $isDark;
        $this->isFunction[$y][$x] = true;
    }

    private static function reedSolomonComputeDivisor($degree) {
        $result = array_fill(0, $degree, 0);
        $result[$degree - 1] = 1;
        $root = 1;
        for ($i = 0; $i < $degree; $i++) {
            for ($j = 0; $j < $degree; $j++) {
                $result[$j] = self::reedSolomonMultiply($result[$j], $root);
                if ($j + 1 < $degree) {
                    $result[$j] ^= $result[$j + 1];
                }
            }
            $root = self::reedSolomonMultiply($root, 0x02);
        }
        return $result;
    }

    private static function reedSolomonComputeRemainder($data, $divisor) {
        $result = array_fill(0, count($divisor), 0);
        foreach ($data as $b) {
            $factor = ($b ^ $result[0]) & 0xff;
            array_shift($result);
            $result[] = 0;
            for ($i = 0; $i < count($result); $i++) {
                $result[$i] ^= self::reedSolomonMultiply($divisor[$i], $factor);
            }
        }
        return $result;
    }

    private static function reedSolomonMultiply($x, $y) {
        $z = 0;
        for ($i = 7; $i >= 0; $i--) {
            $z = ($z << 1) ^ ((($z >> 7) & 1) * 0x11D);
            $z ^= ((($y >> $i) & 1) * $x);
        }
        return $z & 0xff;
    }

    private function getPenaltyScore() {
        $result = 0;
        $size = $this->size;

        for ($y = 0; $y < $size; $y++) {
            $runColor = false;
            $runX = 0;
            $runHistory = array_fill(0, 7, 0);
            for ($x = 0; $x < $size; $x++) {
                if ($this->modules[$y][$x] == $runColor) {
                    $runX++;
                    if ($runX == 5) $result += self::$PENALTY_N1;
                    elseif ($runX > 5) $result++;
                } else {
                    $this->finderPenaltyAddHistory($runX, $runHistory);
                    if (!$runColor) $result += $this->finderPenaltyCountPatterns($runHistory) * self::$PENALTY_N3;
                    $runColor = $this->modules[$y][$x];
                    $runX = 1;
                }
            }
            $result += $this->finderPenaltyTerminateAndCount($runColor, $runX, $runHistory) * self::$PENALTY_N3;
        }

        for ($x = 0; $x < $size; $x++) {
            $runColor = false;
            $runY = 0;
            $runHistory = array_fill(0, 7, 0);
            for ($y = 0; $y < $size; $y++) {
                if ($this->modules[$y][$x] == $runColor) {
                    $runY++;
                    if ($runY == 5) $result += self::$PENALTY_N1;
                    elseif ($runY > 5) $result++;
                } else {
                    $this->finderPenaltyAddHistory($runY, $runHistory);
                    if (!$runColor) $result += $this->finderPenaltyCountPatterns($runHistory) * self::$PENALTY_N3;
                    $runColor = $this->modules[$y][$x];
                    $runY = 1;
                }
            }
            $result += $this->finderPenaltyTerminateAndCount($runColor, $runY, $runHistory) * self::$PENALTY_N3;
        }

        for ($y = 0; $y < $size - 1; $y++) {
            for ($x = 0; $x < $size - 1; $x++) {
                $color = $this->modules[$y][$x];
                if ($color == $this->modules[$y][$x + 1]
                    && $color == $this->modules[$y + 1][$x]
                    && $color == $this->modules[$y + 1][$x + 1]) {
                    $result += self::$PENALTY_N2;
                }
            }
        }

        $dark = 0;
        foreach ($this->modules as $row) {
            foreach ($row as $color) {
                if ($color) $dark++;
            }
        }
        $total = $size * $size;
        $k = intdiv(abs($dark * 20 - $total * 10) + $total * 5 - 1, $total * 5) - 1;
        $result += $k * self::$PENALTY_N4;

        return $result;
    }

    private function finderPenaltyCountPatterns($runHistory) {
        $n = $runHistory[1];
        $core = $n > 0 && $runHistory[2] == $n && $runHistory[3] == $n * 3 && $runHistory[4] == $n && $runHistory[5] == $n;
        return (($core && $runHistory[0] >= $n * 4 && $runHistory[6] >= $n) ? 1 : 0)
             + (($core && $runHistory[6] >= $n * 4 && $runHistory[0] >= $n) ? 1 : 0);
    }

    private function finderPenaltyTerminateAndCount($currentRunColor, $currentRunLength, $runHistory) {
        if ($currentRunColor) {
            $this->finderPenaltyAddHistory($currentRunLength, $runHistory);
            $currentRunLength = 0;
        }
        $currentRunLength += $this->size;
        $this->finderPenaltyAddHistory($currentRunLength, $runHistory);
        return $this->finderPenaltyCountPatterns($runHistory);
    }

    private function finderPenaltyAddHistory($currentRunLength, &$runHistory) {
        if ($runHistory[0] == 0) {
            $currentRunLength += $this->size;
        }
        array_pop($runHistory);
        array_unshift($runHistory, $currentRunLength);
    }
}
