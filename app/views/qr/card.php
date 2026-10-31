<?php
/** @var array $card */
$pageTitle = 'Business Card Studio';
$card = $card ?? [];
$cv = function ($k) use ($card) { return isset($card[$k]) ? $card[$k] : ''; };
?>

<div class="card mb-4">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-id-card" style="color: var(--primary); margin-right: 0.5rem;"></i>Business Card Studio</h3>
            <p class="card-subtitle">Design a personal card with your logo &amp; details and a scannable QR (vCard / website). Download as PNG or PDF.</p>
        </div>
        <a href="<?= eurl('/qr-studio') ?>" class="btn btn-outline"><i class="fas fa-qrcode"></i> QR Studio</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: start;">
    <!-- Form -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Your Details</h3></div>
        <form id="cardForm" onsubmit="return false;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group"><label>Full Name</label><input class="form-control" data-f="name" value="<?= sanitize($cv('name')) ?>" placeholder="Jane Mwangi"></div>
                <div class="form-group"><label>Job Title</label><input class="form-control" data-f="title" value="<?= sanitize($cv('title')) ?>" placeholder="Founder & Lead Developer"></div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group"><label>Company</label><input class="form-control" data-f="company" value="<?= sanitize($cv('company')) ?>" placeholder="Tek Trend Innovations"></div>
                <div class="form-group"><label>Website</label><input class="form-control" data-f="website" value="<?= sanitize($cv('website')) ?>" placeholder="tektrend.online"></div>
            </div>
            <div class="form-group"><label>Tagline</label><input class="form-control" data-f="tagline" value="<?= sanitize($cv('tagline')) ?>" placeholder="World-class software architecture"></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group"><label>Email</label><input class="form-control" data-f="email" value="<?= sanitize($cv('email')) ?>" placeholder="you@company.com"></div>
                <div class="form-group"><label>Phone</label><input class="form-control" data-f="phone" value="<?= sanitize($cv('phone')) ?>" placeholder="0700 000 000"></div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group"><label>WhatsApp (int'l, no +)</label><input class="form-control" data-f="whatsapp" value="<?= sanitize($cv('whatsapp')) ?>" placeholder="254700000000"></div>
                <div class="form-group"><label>Address</label><input class="form-control" data-f="address" value="<?= sanitize($cv('address')) ?>" placeholder="Eldoret, Kenya"></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>QR encodes</label>
                    <select class="form-control" data-f="qrmode">
                        <option value="website" <?= $cv('qrmode')==='website'?'selected':'' ?>>Website</option>
                        <option value="vcard" <?= $cv('qrmode')==='vcard'?'selected':'' ?>>Contact (vCard)</option>
                        <option value="link" <?= $cv('qrmode')==='link'?'selected':'' ?>>Direct Link</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Logo</label>
                    <input type="file" id="logoFile" accept="image/png, image/jpeg, image/webp, image/gif" style="font-size:.8rem;">
                </div>
            </div>
            <input type="hidden" id="logoPath" data-f="logo" value="<?= sanitize($cv('logo')) ?>">
            <div id="logoNote" style="font-size:.75rem; color: var(--text-muted); margin:-0.5rem 0 1rem;">
                <?= $cv('logo') ? 'Logo set.' : 'No logo uploaded &mdash; the company name is used as a text logo.' ?>
            </div>
        </form>

        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
            <a id="dlCardPng" href="#" class="btn btn-primary" download><i class="fas fa-file-image"></i> Download PNG</a>
            <a id="dlCardPdf" href="#" class="btn btn-secondary" download><i class="fas fa-file-pdf"></i> Download PDF</a>
        </div>
    </div>

    <!-- Preview -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Live Preview</h3></div>
        <div style="padding: 1rem; text-align:center;">
            <img id="cardPreview" src="" alt="Business card preview" style="max-width:100%; height:auto; border:1px solid var(--border); border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,.12);">
            <p style="font-size:.75rem; color:var(--text-muted); margin-top:.75rem;">Preview is rendered server-side (1050&times;620px, ~300&nbsp;DPI print).</p>
        </div>
    </div>
</div>

<script>
(function () {
    var base = '<?= eurl('/') ?>';
    var csrf = '<?= csrfToken() ?>';
    function fields() {
        var o = {};
        document.querySelectorAll('#cardForm [data-f]').forEach(function (el) { o[el.getAttribute('data-f')] = el.value; });
        return o;
    }
    function qs() {
        var f = fields(), p = [];
        for (var k in f) { p.push(k + '=' + encodeURIComponent(f[k])); }
        return '?' + p.join('&');
    }
    function refresh() {
        var s = qs();
        document.getElementById('cardPreview').src = base + '/qr/card/png' + s + '&t=' + Date.now();
        document.getElementById('dlCardPng').href = base + '/qr/card/png' + s + '&download=1';
        document.getElementById('dlCardPdf').href = base + '/qr/card/pdf' + s;
    }
    document.querySelectorAll('#cardForm [data-f]').forEach(function (el) {
        el.addEventListener('input', refresh);
        el.addEventListener('change', refresh);
    });
    document.getElementById('logoFile').addEventListener('change', function (e) {
        if (!e.target.files || !e.target.files[0]) return;
        var fd = new FormData();
        fd.append('logo', e.target.files[0]);
        fd.append('csrf_token', csrf);
        fetch(base + '/qr/upload-logo', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (j) {
                if (j && j.success) {
                    document.getElementById('logoPath').value = j.path;
                    document.getElementById('logoNote').textContent = 'Logo uploaded.';
                    refresh();
                } else {
                    alert((j && j.error) || 'Upload failed.');
                }
            })
            .catch(function () { alert('Upload failed.'); });
    });
    refresh();
})();
</script>
