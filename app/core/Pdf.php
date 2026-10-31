<?php
/**
 * Pdf - Minimal dependency-free PDF writer
 *
 * Supports pages whose content is either a vector drawing command stream or a
 * full-bleed JPEG image (embedded with DCTDecode). Enough to export QR codes as
 * crisp vector PDFs and business cards as image PDFs, with no external libraries.
 *
 * Page sizes are in PostScript points (1pt = 1/72").
 */

class Pdf {
    private $pages = []; // each: ['w','h','stream','image'(jpeg),'imgW','imgH','font']

    /**
     * Add a page with a vector content stream.
     */
    public function addVectorPage($w, $h, $content, $withFont = false) {
        $this->pages[] = ['w' => $w, 'h' => $h, 'stream' => $content, 'image' => null, 'font' => $withFont];
    }

    /**
     * Add a page that displays a JPEG image scaled to fill the page.
     */
    public function addImagePage($w, $h, $jpeg) {
        $this->pages[] = ['w' => $w, 'h' => $h, 'stream' => null, 'image' => $jpeg, 'font' => false];
    }

    public function output() {
        $numPages = count($this->pages);
        if ($numPages === 0) {
            $this->addVectorPage(595, 842, 'q Q');
            $numPages = 1;
        }

        // Object layout:
        // 1 = Catalog, 2 = Pages, then for each page: Page obj, Content obj, [Image obj], then Font obj at end (shared)
        $objects = [];       // id => raw string (without "N 0 obj" wrapper) except streams handled specially
        $objectCount = 2 + $numPages * 2;
        $imageObjIds = [];
        $pageObjIds = [];

        // Pre-assign ids: pageObj at 3 + i*2, contentObj at 4 + i*2
        for ($i = 0; $i < $numPages; $i++) {
            $pageObjIds[$i] = 3 + $i * 2;
        }

        // Extra objects appended after pages (images then optional font)
        $nextId = 3 + $numPages * 2;
        $fontId = null;
        $hasFont = false;
        foreach ($this->pages as $p) {
            if (!empty($p['font'])) $hasFont = true;
        }
        if ($hasFont) {
            $fontId = $nextId++;
        }
        foreach ($this->pages as $idx => $p) {
            if ($p['image'] !== null) {
                $imageObjIds[$idx] = $nextId++;
            }
        }

        $objs = []; // id => string body (already full "obj ... endobj" content assembled below)

        // Catalog
        $kids = implode(' ', array_map(function ($id) { return $id . ' 0 R'; }, $pageObjIds));
        $objs[1] = "<< /Type /Catalog /Pages 2 0 R >>";
        $objs[2] = "<< /Type /Pages /Kids [" . $kids . "] /Count " . $numPages . " >>";

        foreach ($this->pages as $idx => $p) {
            $pageId = $pageObjIds[$idx];
            $contentId = $pageId + 1;
            $res = '<<';
            if (!empty($p['font'])) {
                $res .= ' /Font << /F1 ' . $fontId . ' 0 R >>';
            }
            if (isset($imageObjIds[$idx])) {
                $res .= ' /XObject << /Im0 ' . $imageObjIds[$idx] . ' 0 R >>';
            }
            $res .= ' >>';
            $objs[$pageId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$p['w']} {$p['h']}] /Resources {$res} /Contents {$contentId} 0 R >>";

            if ($p['image'] !== null) {
                $w = $p['w'];
                $h = $p['h'];
                $contentStream = "q\n{$w} 0 0 {$h} 0 0 cm\n/Im0 Do\nQ\n";
                $objs[$contentId] = $contentStream;
            } else {
                $objs[$contentId] = $p['stream'];
            }
        }

        if ($fontId !== null) {
            $objs[$fontId] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>";
        }
        foreach ($imageObjIds as $idx => $id) {
            $p = $this->pages[$idx];
            $imgInfo = @getimagesizefromstring($p['image']);
            $wpx = $imgInfo ? $imgInfo[0] : 1;
            $hpx = $imgInfo ? $imgInfo[1] : 1;
            $objs[$id] = "IMAGE:" . base64_encode($p['image']) . "|{$wpx}|{$hpx}";
        }

        // Serialize
        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];
        $maxId = max(array_keys($objs));
        for ($id = 1; $id <= $maxId; $id++) {
            if (!isset($objs[$id])) continue;
            $offsets[$id] = strlen($pdf);
            $body = $objs[$id];
            if (isset($contentId) && $this->isStreamObject($id, $objs)) {
                // handled below generically
            }
            if (is_string($body) && strpos($body, 'IMAGE:') === 0) {
                $parts = explode('|', substr($body, 6), 2);
                $imgBin = base64_decode($parts[0]);
                list($wpx, $hpx) = explode('|', $parts[1]);
                $dict = "<< /Type /XObject /Subtype /Image /Width {$wpx} /Height {$hpx} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length " . strlen($imgBin) . " >>";
                $pdf .= "{$id} 0 obj\n{$dict}\nstream\n" . $imgBin . "\nendstream\nendobj\n";
            } elseif ($this->isContentStream($id, $idxMap = $this->contentIds())) {
                $pdf .= "{$id} 0 obj\n<< /Length " . strlen($body) . " >>\nstream\n{$body}\nendstream\nendobj\n";
            } else {
                $pdf .= "{$id} 0 obj\n{$body}\nendobj\n";
            }
        }

        $xrefStart = strlen($pdf);
        $count = $maxId + 1;
        $pdf .= "xref\n0 {$count}\n0000000000 65535 f \n";
        for ($id = 1; $id <= $maxId; $id++) {
            if (isset($offsets[$id])) {
                $pdf .= sprintf("%010d 00000 n \n", $offsets[$id]);
            } else {
                $pdf .= "0000000000 65535 f \n";
            }
        }
        $pdf .= "trailer\n<< /Size {$count} /Root 1 0 R >>\nstartxref\n{$xrefStart}\n%%EOF\n";

        return $pdf;
    }

    private function contentIds() {
        $ids = [];
        $numPages = count($this->pages);
        for ($i = 0; $i < $numPages; $i++) {
            $ids[] = 3 + $i * 2 + 1; // content object id = pageId + 1
        }
        return $ids;
    }

    private function isContentStream($id, $ids) {
        return in_array($id, $ids, true);
    }

    private function isStreamObject($id, $objs) {
        return false;
    }
}
