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
        $white = imagecolorallocate($img, 255, 255, 255);
        $ink = imagecolorallocate($img, 20, 24, 34);
        $muted = imagecolorallocate($img, 110, 118, 132);
        $accent = imagecolorallocate($img, 37, 99, 235);
        $accent2 = imagecolorallocate($img, 214, 194, 157);

        imagefilledrectangle($img, 0, 0, $W, $H, $white);
        // Left accent bar + top hairline
        imagefilledrectangle($img, 0, 0, 22, $H, $accent);
        imagefilledrectangle($img, 22, 0, 26, $H, $accent2);

        $font = $this->findFont();
        $bigFont = $this->findFont(40);
        $smallFont = $this->findFont(18);

        // Logo (top area) or company name fallback
        $logoBox = ['x' => 70, 'y' => 60, 'w' => 360, 'h' => 120];
        $logoDrawn = false;
        if (!empty($card['logo'])) {
            $logoPath = $this->resolveLogoPath($card['logo']);
            if ($logoPath) {
                $li = $this->loadImage($logoPath);
                if ($li) {
                    $this->fitImage($img, $li, $logoBox['x'], $logoBox['y'], $logoBox['w'], $logoBox['h']);
                    $logoDrawn = true;
                }
            }
        }
        if (!$logoDrawn && $card['company'] !== '') {
            $this->drawText($img, $logoBox['x'], $logoBox['y'] + 60, $card['company'], $accent, $bigFont ?: 5, $font);
        }

        // Name + title
        $this->drawText($img, 70, 300, $card['name'] !== '' ? $card['name'] : 'Your Name', $ink, ($font ? 44 : 5), $font, true);
        if ($card['title'] !== '') {
            $this->drawText($img, 70, 356, $card['title'], $muted, ($font ? 22 : 3), $font);
        }

        // Contact block
        $lines = [];
        if ($card['email'])    $lines[] = 'Email: ' . $card['email'];
        if ($card['phone'])    $lines[] = 'Phone: ' . $card['phone'];
        if ($card['whatsapp']) $lines[] = 'WhatsApp: +' . ltrim($card['whatsapp'], '+');
        if ($card['address'])  $lines[] = $card['address'];
        if ($card['website'])  $lines[] = $card['website'];
        $y = 410;
        foreach (array_slice($lines, 0, 5) as $ln) {
            $this->drawText($img, 70, $y, $ln, $ink, ($font ? 19 : 3), $font);
            $y += 34;
        }

        // QR (right side)
        $qrData = $this->cardQrPayload($card);
        try {
            $qr = QrCode::encode($qrData, 'M');
            $qr->drawOnImage($img, $W - 250, $H - 250, 200, 2);
        } catch (\Throwable $e) { /* QR optional */ }

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

    private function findFont($size = 24) {
        static $cache = [];
        $key = (int)$size;
        if (isset($cache[$key])) return $cache[$key];
        $paths = [
            BASE_PATH . '/assets/fonts/Inter-Regular.ttf',
            BASE_PATH . '/assets/fonts/DejaVuSans.ttf',
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/segoeui.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
        ];
        $found = null;
        foreach ($paths as $p) {
            if (is_file($p)) { $found = $p; break; }
        }
        $cache[$key] = $found;
        return $found;
    }

    private function drawText($img, $x, $y, $text, $color, $size, $fontFile, $bold = false) {
        $text = (string)$text;
        if ($fontFile && function_exists('imagettftext')) {
            // $size is a pt-ish value when a TTF is available
            imagettftext($img, $size, 0, $x, $y, $color, $fontFile, $text);
        } else {
            $sf = is_int($size) && $size <= 5 ? $size : 4;
            // built-in bitmap font is small; emulate bold by drawing twice with 1px offset
            imagestring($img, $sf, $x, $y - ($size > 20 ? 12 : 0), $text, $color);
            if ($bold) imagestring($img, $sf, $x + 1, $y - ($size > 20 ? 12 : 0), $text, $color);
        }
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
