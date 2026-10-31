<?php
/**
 * QR Code Controller
 * Backend QR generator (share link / PNG / PDF / SVG) + business card studio.
 * Rendering uses the dependency-free QrCode + Pdf core classes.
 */

require_once APP_PATH . '/core/QrCode.php';
require_once APP_PATH . '/core/Pdf.php';

class QrCodeController extends Controller {

    /**
     * QR Studio (staff) - generator + business card entry point.
     */
    public function index() {
        $this->requireAuth();
        $data = isset($_GET['data']) ? trim((string)$_GET['data']) : '';
        $ec = $this->safeEcl($_GET['ec'] ?? 'M');
        $scale = $this->safeInt($_GET['scale'] ?? 8, 2, 24, 8);
        $margin = $this->safeInt($_GET['margin'] ?? 4, 0, 12, 4);
        $label = isset($_GET['label']) ? mb_substr(trim((string)$_GET['label']), 0, 60) : '';

        $this->render('qr/index', [
            'pageTitle'   => 'QR Code Studio',
            'currentPage' => 'qr',
            'qr_text'     => $data,
            'ec'          => $ec,
            'scale'       => $scale,
            'margin'      => $margin,
            'label'       => $label,
        ]);
    }

    /**
     * Public standalone "share as link" page for a QR code.
     */
    public function view() {
        $data = isset($_GET['data']) ? trim((string)$_GET['data']) : '';
        $ec = $this->safeEcl($_GET['ec'] ?? 'M');
        $title = isset($_GET['title']) ? mb_substr(trim((string)$_GET['title']), 0, 80) : 'QR Code';
        if ($data === '') {
            http_response_code(400);
            echo 'No data provided for QR code.';
            return;
        }
        $this->view->setLayout(null);
        $this->render('qr/view', [
            'qr_text' => $data,
            'ec'      => $ec,
            'title'   => $title,
        ], null);
    }

    /**
     * Output a QR code as PNG.
     */
    public function png() {
        [$qr, $opts, $err] = $this->buildFromRequest();
        if ($err) { $this->fail($err); return; }
        $bin = $qr->toPng($opts['scale'], $opts['margin']);
        $this->sendBinary($bin, 'image/png', $opts['download'] ? $this->filename($opts, 'png') : null);
    }

    /**
     * Output a QR code as SVG.
     */
    public function svg() {
        [$qr, $opts, $err] = $this->buildFromRequest();
        if ($err) { $this->fail($err); return; }
        $bin = $qr->toSvg($opts['scale'], $opts['margin']);
        $this->sendBinary($bin, 'image/svg+xml', $opts['download'] ? $this->filename($opts, 'svg') : null);
    }

    /**
     * Output a QR code as a vector PDF download.
     */
    public function pdf() {
        [$qr, $opts, $err] = $this->buildFromRequest();
        if ($err) { $this->fail($err); return; }
        $bin = $qr->toPdf(max(4, (int)round($opts['scale'] * 1.2)), $opts['margin'], $opts['label']);
        $this->sendBinary($bin, 'application/pdf', $this->filename($opts, 'pdf'));
    }

    // ------------------------------------------------------------------
    // Business card studio
    // ------------------------------------------------------------------

    /**
     * Business card studio form + preview (staff).
     */
    public function card() {
        $this->requireAuth();
        $user = $this->auth->user();
        $settings = $this->getSettings();

        $defaults = [
            'name'    => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
            'title'   => $user['position'] ?? 'Founder & Lead Developer',
            'email'   => $user['email'] ?? ($settings['company_email'] ?? ''),
            'phone'   => $user['phone'] ?? ($settings['company_phone'] ?? ''),
            'company' => $settings['company_name'] ?? 'Tek Trend Innovations',
            'tagline' => $settings['company_tagline'] ?? '',
            'address' => $settings['company_address'] ?? '',
            'whatsapp'=> $settings['company_whatsapp'] ?? '',
            'website' => parse_url(APP_URL, PHP_URL_HOST) ?: 'tektrend.online',
            'logo'    => '',
            'qrmode'  => 'website',
        ];
        $card = [];
        foreach ($defaults as $k => $v) {
            $card[$k] = isset($_GET[$k]) ? trim((string)$_GET[$k]) : $v;
        }
        if ($card['name'] === '') $card['name'] = $defaults['name'];

        $this->render('qr/card', [
            'pageTitle'   => 'Business Card Studio',
            'currentPage' => 'qr',
            'card'        => $card,
        ]);
    }

    /**
     * Render the business card as a PNG download/preview.
     */
    public function cardPng() {
        $this->requireAuth();
        $card = $this->cardFields();
        $img = $this->renderCardImage($card);
        ob_start();
        imagepng($img, null, 9);
        $bin = ob_get_clean();
        imagedestroy($img);
        $dl = !empty($_GET['download']);
        $this->sendBinary($bin, 'image/png', $dl ? 'business-card.png' : null);
    }

    /**
     * Render the business card as a PDF download (card-sized page).
     */
    public function cardPdf() {
        $this->requireAuth();
        $card = $this->cardFields();
        $img = $this->renderCardImage($card);
        ob_start();
        imagejpeg($img, null, 92);
        $jpeg = ob_get_clean();
        imagedestroy($img);
        // 3.5in x ~2.07in page in points
        $pdfDoc = new Pdf();
        $pdfDoc->addImagePage(252, 149, $jpeg);
        $this->sendBinary($pdfDoc->output(), 'application/pdf', 'business-card.pdf');
    }

    /**
     * Upload a logo for the business card (staff). Returns JSON with the path.
     */
    public function uploadLogo() {
        $this->requireAuth();
        $this->verifyCsrf();
        if (empty($_FILES['logo']['name'])) {
            $this->json(['success' => false, 'error' => 'No file selected.'], 400);
        }
        $res = uploadFile($_FILES['logo'], 'branding', ['png', 'jpg', 'jpeg', 'gif', 'webp']);
        if (!empty($res['success'])) {
            $this->json(['success' => true, 'path' => $res['filename']]);
        }
        $this->json(['success' => false, 'error' => $res['error'] ?? 'Upload failed.'], 400);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function buildFromRequest() {
        $data = isset($_GET['data']) ? trim((string)$_GET['data']) : '';
        if ($data === '' || strlen($data) > 2000) {
            return [null, null, 'Invalid or empty QR data.'];
        }
        $ec = $this->safeEcl($_GET['ec'] ?? 'M');
        $scale = $this->safeInt($_GET['scale'] ?? 8, 2, 24, 8);
        $margin = $this->safeInt($_GET['margin'] ?? 4, 0, 12, 4);
        $label = isset($_GET['label']) ? mb_substr(trim((string)$_GET['label']), 0, 60) : '';
        $download = !empty($_GET['download']);
        try {
            $qr = QrCode::encode($data, $ec);
        } catch (\Throwable $e) {
            return [null, null, 'Could not encode QR: ' . $e->getMessage()];
        }
        return [$qr, ['data' => $data, 'ec' => $ec, 'scale' => $scale, 'margin' => $margin, 'label' => $label, 'download' => $download], null];
    }

    private function cardFields() {
        $g = function ($k, $max = 120) {
            return mb_substr(trim((string)($_GET[$k] ?? '')), 0, $max);
        };
        return [
            'name'     => $g('name', 80),
            'title'    => $g('title', 80),
            'company'  => $g('company', 80),
            'tagline'  => $g('tagline', 100),
            'email'    => $g('email', 100),
            'phone'    => $g('phone', 40),
            'whatsapp' => $g('whatsapp', 40),
            'address'  => $g('address', 120),
            'website'  => $g('website', 100),
            'logo'     => $g('logo', 200),
            'qrmode'   => in_array($g('qrmode', 20), ['website', 'vcard', 'link'], true) ? $g('qrmode', 20) : 'website',
        ];
    }

    private function renderCardImage($card) {
        $W = 1050; $H = 620; // ~300dpi 3.5in x 2.07in
        $img = imagecreatetruecolor($W, $H);
        imageantialias($img, true);

        // ---- Palette: deep slate ink + brand blue + champagne ----
        $white  = imagecolorallocate($img, 255, 255, 255);
        $ink    = $this->hexColor($img, '#0F172A');
        $slate  = $this->hexColor($img, '#334155');
        $muted  = $this->hexColor($img, '#8A94A6');
        $accent = $this->hexColor($img, '#2563EB');
        $gold   = $this->hexColor($img, '#C7A65B');
        $shadow = $this->hexColor($img, '#0B1220', 70);

        imagefilledrectangle($img, 0, 0, $W, $H, $white);

        // ---- Right brand panel with a vertical blue gradient + champagne edge ----
        $panelX = 742;
        $this->vGradient($img, $panelX, 0, $W, $H, '#1E3A8A', '#2563EB');
        imagefilledrectangle($img, $panelX - 4, 0, $panelX, $H, $gold);

        // ---- Fonts (multiple weights when available) ----
        $fRegular = $this->findFont('regular');
        $fBold    = $this->findFont('bold');
        $fSemi    = $this->findFont('semibold') ?: $fBold;
        $fItalic  = $this->findFont('italic');

        $left  = 62;
        $maxW  = ($panelX - 40) - $left; // right limit of the content column

        // ---- Brand mark: logo, else accent dot + spaced uppercase name ----
        $logoDrawn = false;
        if (!empty($card['logo'])) {
            $logoPath = $this->resolveLogoPath($card['logo']);
            if ($logoPath) {
                $li = $this->loadImage($logoPath);
                if ($li) { $this->fitImage($img, $li, $left, 52, 260, 80); $logoDrawn = true; }
            }
        }
        if (!$logoDrawn) {
            $brand = strtoupper($card['company'] !== '' ? $card['company'] : 'YOUR COMPANY');
            $this->roundRect($img, $left, 64, 16, 16, 4, $accent);
            list($bs, $brand) = $this->fitSpaced($fSemi, 20, $brand, $maxW - 30, 9, 2.2);
            $this->spacedText($img, $left + 28, 79, $bs, $fSemi, $slate, $brand, 2.2);
        }

        // ---- Name (dark ink, large) ----
        $name = $card['name'] !== '' ? $card['name'] : 'Your Name';
        list($ns, $name) = $this->fitText($fSemi, 47, $name, $maxW, 18);
        $this->text($img, $left, 252, $ns, $fSemi, $ink, $name);

        // ---- Title (accent) ----
        if ($card['title'] !== '') {
            list($ts, $ttl) = $this->fitText($fRegular, 21, $card['title'], $maxW, 12);
            $this->text($img, $left, 290, $ts, $fRegular, $accent, $ttl);
        }

        // ---- Champagne rule ----
        imagefilledrectangle($img, $left, 314, $left + (int)min($maxW, 128), 315, $gold);

        // ---- Optional tagline ----
        $y = 314;
        if ($card['tagline'] !== '') {
            $y += 32;
            list($gs, $tag) = $this->fitText($fItalic ?: $fRegular, 15, $card['tagline'], $maxW, 10);
            $this->text($img, $left, $y, $gs, $fItalic ?: $fRegular, $muted, $tag);
        }

        // ---- Contact rows with accent bullets ----
        $rows = [];
        if ($card['email'])    $rows[] = $card['email'];
        if ($card['phone'])    $rows[] = $card['phone'];
        if ($card['whatsapp']) $rows[] = '+' . ltrim($card['whatsapp'], '+');
        if ($card['website'])  $rows[] = $card['website'];
        if ($card['address'])  $rows[] = $card['address'];
        $y += 22;
        foreach (array_slice($rows, 0, 5) as $val) {
            $y += 38;
            $this->roundRect($img, $left, $y - 12, 9, 9, 4, $accent);
            list($vs, $val) = $this->fitText($fRegular, 18, $val, $maxW - 22, 10);
            $this->text($img, $left + 22, $y, $vs, $fRegular, $slate, $val);
        }

        // ---- QR as a floating white card on the panel ----
        $qrData = $this->cardQrPayload($card);
        $box = 232;
        $cx  = (int)round($panelX + (($W - $panelX) - $box) / 2);
        $cy  = 138;
        $this->roundRect($img, $cx + 3, $cy + 6, $box, $box, 18, $shadow);
        $this->roundRect($img, $cx, $cy, $box, $box, 18, $white);
        try {
            $qr = QrCode::encode($qrData, 'M');
            $qr->drawOnImage($img, $cx + 18, $cy + 18, $box - 36, 1);
        } catch (\Throwable $e) { /* QR optional */ }

        // ---- Caption under the QR ----
        $cap = $card['website'] !== '' ? $card['website'] : (parse_url(APP_URL, PHP_URL_HOST) ?: 'tektrend.online');
        $capMax = ($W - $panelX) - 24;
        list($cs, $cap) = $this->fitText($fRegular, 15, $cap, $capMax, 8);
        $capColor = $this->hexColor($img, '#EAF0FB');
        $tw = $this->textWidth($fRegular, $cs, $cap);
        $this->text($img, (int)round($panelX + (($W - $panelX) - $tw) / 2), $cy + $box + 44, $cs, $fRegular, $capColor, $cap);

        return $img;
    }

    private function cardQrPayload($card) {
        if ($card['qrmode'] === 'link' && !empty($card['website'])) {
            $u = $card['website'];
            return preg_match('#^https?://#i', $u) ? $u : 'https://' . $u;
        }
        if ($card['qrmode'] === 'vcard') {
            $name = $card['name'];
            $np = preg_split('/\s+/', trim($name));
            $last = count($np) > 1 ? array_pop($np) : '';
            $first = implode(' ', $np);
            $v = "BEGIN:VCARD\nVERSION:3.0\n";
            $v .= 'N:' . $last . ';' . $first . "\n";
            if ($name) $v .= 'FN:' . $name . "\n";
            if ($card['company']) $v .= 'ORG:' . $card['company'] . "\n";
            if ($card['title']) $v .= 'TITLE:' . $card['title'] . "\n";
            if ($card['phone']) $v .= 'TEL;TYPE=WORK,VOICE:' . $card['phone'] . "\n";
            if ($card['email']) $v .= 'EMAIL;TYPE=PREF,INTERNET:' . $card['email'] . "\n";
            if ($card['website']) $v .= 'URL:' . $card['website'] . "\n";
            if ($card['address']) $v .= 'ADR;TYPE=WORK:;;' . $card['address'] . "\n";
            $v .= 'END:VCARD';
            return $v;
        }
        // default website
        $u = $card['website'] ?: (parse_url(APP_URL, PHP_URL_HOST) ?: 'tektrend.online');
        return preg_match('#^https?://#i', $u) ? $u : 'https://' . $u;
    }

    private function resolveLogoPath($logo) {
        // Only allow local uploads / public assets - never fetch remote URLs.
        $logo = ltrim(str_replace('\\', '/', $logo), '/');
        $candidates = [
            UPLOAD_PATH . '/' . $logo,
            PUBLIC_PATH . '/' . $logo,
            BASE_PATH . '/' . $logo,
        ];
        foreach ($candidates as $c) {
            if (is_file($c) && strpos(realpath($c), realpath(BASE_PATH)) === 0) {
                return $c;
            }
        }
        return null;
    }

    private function loadImage($path) {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'png':  return @imagecreatefrompng($path);
            case 'jpg':
            case 'jpeg': return @imagecreatefromjpeg($path);
            case 'gif':  return @imagecreatefromgif($path);
            case 'webp': return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false;
        }
        return false;
    }

    private function fitImage($dst, $src, $x, $y, $boxW, $boxH) {
        $sw = imagesx($src); $sh = imagesy($src);
        if ($sw < 1 || $sh < 1) return;
        $ratio = min($boxW / $sw, $boxH / $sh);
        $dw = (int)round($sw * $ratio);
        $dh = (int)round($sh * $ratio);
        $dx = $x + (int)(($boxW - $dw) / 2);
        $dy = $y + (int)(($boxH - $dh) / 2);
        imagecopyresampled($dst, $src, $dx, $dy, 0, 0, $dw, $dh, $sw, $sh);
        imagedestroy($src);
    }

    /**
     * Locate a TrueType font for the requested weight. Returns an absolute
     * path or null (callers then fall back to GD's built-in bitmap font).
     */
    private function findFont($weight = 'regular') {
        static $cache = [];
        if (array_key_exists($weight, $cache)) return $cache[$weight];
        $groups = [
            'regular'  => ['Inter-Regular', 'DejaVuSans', 'LiberationSans-Regular', 'segoeui', 'arial'],
            'bold'     => ['Inter-Bold', 'DejaVuSans-Bold', 'LiberationSans-Bold', 'segoeuib', 'arialbd'],
            'semibold' => ['Inter-SemiBold', 'seguisb', 'DejaVuSans-Bold', 'segoeuib', 'arialbd'],
            'light'    => ['Inter-Light', 'segoeuil', 'DejaVuSans-Light', 'arial'],
            'italic'   => ['Inter-Italic', 'segoeuii', 'DejaVuSans-Oblique', 'ariali'],
        ];
        $dirs = [
            BASE_PATH . '/assets/fonts/',
            'C:/Windows/Fonts/',
            '/usr/share/fonts/truetype/dejavu/',
            '/usr/share/fonts/truetype/liberation/',
            '/usr/share/fonts/dejavu/',
            '/usr/share/fonts/TTF/',
        ];
        $found = null;
        foreach (($groups[$weight] ?? $groups['regular']) as $stem) {
            foreach ($dirs as $d) {
                foreach (['ttf', 'otf'] as $e) {
                    $p = $d . $stem . '.' . $e;
                    if (is_file($p)) { $found = $p; break 3; }
                }
            }
        }
        $cache[$weight] = $found;
        return $found;
    }

    private function hexColor($img, $hex, $alpha = 0) {
        $c = $this->hexRgb($hex);
        return imagecolorallocatealpha($img, $c[0], $c[1], $c[2], $alpha);
    }

    private function hexRgb($hex) {
        $hex = ltrim((string)$hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return [
            (int)hexdec(substr($hex, 0, 2)),
            (int)hexdec(substr($hex, 2, 2)),
            (int)hexdec(substr($hex, 4, 2)),
        ];
    }

    /** Vertical linear gradient fill between two hex colours. */
    private function vGradient($img, $x0, $y0, $x1, $y1, $hexTop, $hexBot) {
        $t = $this->hexRgb($hexTop);
        $b = $this->hexRgb($hexBot);
        $span = max(1, $y1 - $y0 - 1);
        for ($y = $y0; $y < $y1; $y++) {
            $f = ($y - $y0) / $span;
            $col = imagecolorallocate($img,
                (int)round($t[0] + ($b[0] - $t[0]) * $f),
                (int)round($t[1] + ($b[1] - $t[1]) * $f),
                (int)round($t[2] + ($b[2] - $t[2]) * $f));
            imagefilledrectangle($img, $x0, $y, $x1, $y, $col);
        }
    }

    /** Filled rounded rectangle. */
    private function roundRect($img, $x, $y, $w, $h, $r, $color) {
        $x = (int)$x; $y = (int)$y; $w = (int)$w; $h = (int)$h;
        $r = (int)min($r, min($w, $h) / 2);
        imageantialias($img, true);
        imagefilledrectangle($img, $x + $r, $y, $x + $w - $r - 1, $y + $h - 1, $color);
        imagefilledrectangle($img, $x, $y + $r, $x + $w - 1, $y + $h - $r - 1, $color);
        imagefilledellipse($img, $x + $r, $y + $r, 2 * $r, 2 * $r, $color);
        imagefilledellipse($img, $x + $w - $r - 1, $y + $r, 2 * $r, 2 * $r, $color);
        imagefilledellipse($img, $x + $r, $y + $h - $r - 1, 2 * $r, 2 * $r, $color);
        imagefilledellipse($img, $x + $w - $r - 1, $y + $h - $r - 1, 2 * $r, 2 * $r, $color);
    }

    /** Render a line of text; $y is the baseline when a TTF is available. */
    private function text($img, $x, $y, $size, $font, $color, $str) {
        $str = (string)$str;
        if ($str === '') return;
        if ($font && function_exists('imagettftext')) {
            imagettftext($img, $size, 0, (int)$x, (int)$y, $color, $font, $str);
        } else {
            imagestring($img, 5, (int)$x, (int)($y - 12), $str, $color);
        }
    }

    /** Uppercase-style text with letter spacing, drawn glyph by glyph. */
    private function spacedText($img, $x, $y, $size, $font, $color, $str, $spacing = 2) {
        $str = (string)$str;
        if ($font && function_exists('imagettftext')) {
            $cx = $x;
            for ($i = 0, $len = mb_strlen($str); $i < $len; $i++) {
                $ch = mb_substr($str, $i, 1);
                imagettftext($img, $size, 0, (int)$cx, (int)$y, $color, $font, $ch);
                $cx += $this->textWidth($font, $size, $ch) + $spacing;
            }
        } else {
            imagestring($img, 5, (int)$x, (int)($y - 12), $str, $color);
        }
    }

    /** Pixel width of a string for a given TTF size (0 if no TTF). */
    private function textWidth($font, $size, $str) {
        $str = (string)$str;
        if ($font && function_exists('imagettfbbox')) {
            $bb = imagettfbbox($size, 0, $font, $str);
            return $bb ? max(0, $bb[2] - $bb[0]) : 0;
        }
        return strlen($str) * 8;
    }

    /**
     * Largest whole point size <= $size that fits $str within $maxW. If even
     * $min is too wide, the string is truncated with an ellipsis so text can
     * never bleed across the layout. Returns [size, text].
     */
    private function fitText($font, $size, $str, $maxW, $min = 8) {
        $str = (string)$str;
        if (!$font || !function_exists('imagettfbbox') || $maxW <= 0) return [$size, $str];
        while ($size > $min && $this->textWidth($font, $size, $str) > $maxW) {
            $size--;
        }
        if ($this->textWidth($font, $size, $str) > $maxW) {
            while ($str !== '' && $this->textWidth($font, $size, $str . '…') > $maxW) {
                $str = mb_substr($str, 0, mb_strlen($str) - 1);
            }
            $str .= '…';
        }
        return [$size, $str];
    }

    /** Total advance width of letter-spaced text. */
    private function spacedWidth($font, $size, $str, $spacing) {
        $str = (string)$str;
        $w = 0;
        for ($i = 0, $len = mb_strlen($str); $i < $len; $i++) {
            $w += $this->textWidth($font, $size, mb_substr($str, $i, 1));
        }
        return $w + $spacing * max(0, mb_strlen($str) - 1);
    }

    /** fitText() but accounting for per-glyph letter spacing. Returns [size, text]. */
    private function fitSpaced($font, $size, $str, $maxW, $min, $spacing) {
        $str = (string)$str;
        if (!$font || !function_exists('imagettfbbox') || $maxW <= 0) return [$size, $str];
        while ($size > $min && $this->spacedWidth($font, $size, $str, $spacing) > $maxW) {
            $size--;
        }
        if ($this->spacedWidth($font, $size, $str, $spacing) > $maxW) {
            while ($str !== '' && $this->spacedWidth($font, $size, $str . '…', $spacing) > $maxW) {
                $str = mb_substr($str, 0, mb_strlen($str) - 1);
            }
            $str .= '…';
        }
        return [$size, $str];
    }

    private function getSettings() {
        $settings = [];
        try {
            $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
            foreach ($rows as $row) { $settings[$row['key']] = $row['value']; }
        } catch (\Throwable $e) { $settings = []; }
        return $settings;
    }

    private function safeEcl($ec) {
        $ec = strtoupper((string)$ec);
        return isset(QrCode::ECC_FORMAT_BITS[$ec]) ? $ec : 'M';
    }

    private function safeInt($v, $min, $max, $default) {
        $v = (int)$v;
        if ($v < $min || $v > $max) return $default;
        return $v;
    }

    private function filename($opts, $ext) {
        $base = $opts['data'];
        if (preg_match('#^(https?)://([^/]+)#i', $base, $m)) {
            $name = preg_replace('/[^a-z0-9]+/i', '-', strtolower($m[2]));
        } else {
            $name = preg_replace('/[^a-z0-9]+/i', '-', strtolower(substr($base, 0, 30)));
        }
        return trim($name, '-') . '.' . $ext;
    }

    private function sendBinary($bin, $mime, $filename = null) {
        while (ob_get_level() > 0) { ob_end_clean(); }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . strlen($bin));
        header('Cache-Control: public, max-age=3600');
        if ($filename !== null) {
            header('Content-Disposition: attachment; filename="' . addslashes($filename) . '"');
        } else {
            header('Content-Disposition: inline');
        }
        echo $bin;
        exit;
    }

    private function fail($msg) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $msg;
        exit;
    }
}
