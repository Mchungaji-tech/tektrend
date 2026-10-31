<?php $pageTitle = 'QR Code Studio'; ?>

<div class="card mb-4">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-qrcode" style="color: var(--primary); margin-right: 0.5rem;"></i>QR Code Studio</h3>
            <p class="card-subtitle">Generate QR codes for any link or text &mdash; share as a link, or download as PNG / PDF / SVG</p>
        </div>
        <a href="<?= eurl('/qr/card') ?>" class="btn btn-secondary"><i class="fas fa-id-card"></i> Business Card Studio</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 1.5rem; align-items: start;">
    <!-- Controls -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Configure</h3></div>
        <form id="qrForm" onsubmit="return false;">
            <div class="form-group">
                <label class="required">Link or Text to encode</label>
                <input type="text" id="qrData" class="form-control" placeholder="https://tektrend.online/live_demo/agency.html" value="<?= sanitize($qr_text) ?>">
                <small style="color: var(--text-muted); font-size: 0.75rem;">Paste a demo link, page URL, or any text (max ~2000 chars).</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Error Correction</label>
                    <select id="qrEc" class="form-control">
                        <option value="L" <?= $ec==='L'?'selected':'' ?>>L &middot; Low (7%)</option>
                        <option value="M" <?= $ec==='M'?'selected':'' ?>>M &middot; Medium (15%)</option>
                        <option value="Q" <?= $ec==='Q'?'selected':'' ?>>Q &middot; Quartile (25%)</option>
                        <option value="H" <?= $ec==='H'?'selected':'' ?>>H &middot; High (30%)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Module Size (px)</label>
                    <input type="number" id="qrScale" class="form-control" min="2" max="24" value="<?= (int)$scale ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Quiet Margin</label>
                    <input type="number" id="qrMargin" class="form-control" min="0" max="12" value="<?= (int)$margin ?>">
                </div>
                <div class="form-group">
                    <label>PDF Title (optional)</label>
                    <input type="text" id="qrLabel" class="form-control" maxlength="60" placeholder="e.g. Portfolio Demo" value="<?= sanitize($label) ?>">
                </div>
            </div>
        </form>

        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
            <button type="button" class="btn btn-outline" onclick="copyShareLink()"><i class="fas fa-link"></i> Copy Share Link</button>
            <a id="dlPng" href="#" class="btn btn-primary" download><i class="fas fa-file-image"></i> Download PNG</a>
            <a id="dlPdf" href="#" class="btn btn-secondary" download><i class="fas fa-file-pdf"></i> Download PDF</a>
            <a id="dlSvg" href="#" class="btn btn-secondary" download><i class="fas fa-file-code"></i> Download SVG</a>
        </div>
    </div>

    <!-- Preview -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Live Preview</h3></div>
        <div style="text-align: center; padding: 1rem;">
            <img id="qrPreview" src="" alt="QR preview" style="max-width: 100%; height: auto; image-rendering: pixelated; border: 1px solid var(--border); border-radius: 12px; background: #fff; padding: 8px;">
            <p id="qrEmpty" style="color: var(--text-muted); font-size: 0.85rem; margin-top: 1rem;">Enter a link or text to generate a QR code.</p>
            <div id="qrShareWrap" style="display:none; margin-top: 1rem;">
                <label style="font-size:0.75rem; color: var(--text-muted); display:block; text-align:left; margin-bottom:0.25rem;">Shareable link</label>
                <input type="text" id="qrShareLink" class="form-control" readonly onclick="this.select();">
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var base = '<?= eurl('/') ?>';
    function q(s) { return encodeURIComponent(s); }
    function params(download) {
        var data = document.getElementById('qrData').value.trim();
        if (!data) return null;
        var p = '?data=' + q(data)
            + '&ec=' + q(document.getElementById('qrEc').value)
            + '&scale=' + q(document.getElementById('qrScale').value)
            + '&margin=' + q(document.getElementById('qrMargin').value)
            + '&label=' + q(document.getElementById('qrLabel').value);
        if (download) p += '&download=1';
        return p;
    }
    function refresh() {
        var p = params(false);
        var img = document.getElementById('qrPreview');
        var empty = document.getElementById('qrEmpty');
        var share = document.getElementById('qrShareWrap');
        if (!p) {
            img.removeAttribute('src');
            empty.style.display = 'block';
            share.style.display = 'none';
            ['dlPng','dlPdf','dlSvg'].forEach(function(id){ document.getElementById(id).setAttribute('href','#'); });
            return;
        }
        empty.style.display = 'none';
        share.style.display = 'block';
        img.src = base + '/qr/png' + p + '#' + Math.random();
        document.getElementById('dlPng').href = base + '/qr/png' + params(true);
        document.getElementById('dlPdf').href = base + '/qr/pdf' + p;
        document.getElementById('dlSvg').href = base + '/qr/svg' + p;
        var view = base + '/qr/view' + p.replace('label=', 'title=');
        document.getElementById('qrShareLink').value = view;
    }
    window.copyShareLink = function () {
        var link = document.getElementById('qrShareLink').value;
        if (!link) { alert('Enter data first.'); return; }
        var inp = document.createElement('input');
        inp.value = link; document.body.appendChild(inp); inp.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(inp);
        alert('Share link copied:\n' + link);
    };
    ['qrData','qrEc','qrScale','qrMargin','qrLabel'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', refresh);
        document.getElementById(id).addEventListener('change', refresh);
    });
    refresh();
})();
</script>
