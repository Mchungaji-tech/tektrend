<?php $pageTitle = 'Add Design Item'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Publish New Design for Bidding</h3>
            <p class="card-subtitle">List an award-winning web design template or custom portal</p>
        </div>
        <a href="<?= eurl('/marketplace') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Marketplace</a>
    </div>

    <form method="POST" action="<?= eurl('/marketplace') ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label>Design Title *</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Apex Luxury Real Estate Portal" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Category *</label>
                <select name="category" class="form-control" required>
                    <option value="Real Estate & Architecture">Real Estate & Architecture</option>
                    <option value="Fintech & SaaS">Fintech & SaaS</option>
                    <option value="Creative & Agency">Creative & Agency</option>
                    <option value="Logistics & Supply Chain">Logistics & Supply Chain</option>
                    <option value="E-Commerce Luxury">E-Commerce Luxury</option>
                    <option value="Healthcare & Enterprise">Healthcare & Enterprise</option>
                </select>
            </div>
            <div class="form-group">
                <label>Award / Recognition Badge</label>
                <select name="award_badge" class="form-control">
                    <option value="Site of the Day">Site of the Day</option>
                    <option value="Developer Award">Developer Award</option>
                    <option value="Site of the Month">Site of the Month</option>
                    <option value="Honorable Mention">Honorable Mention</option>
                    <option value="Studio Featured">Studio Featured</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Starting Reserve Bid (KSh) *</label>
                <input type="number" step="500" name="starting_bid" class="form-control" value="85000" required>
            </div>
            <div class="form-group">
                <label>Instant Buy-Now Price (KSh) *</label>
                <input type="number" step="500" name="buy_now_price" class="form-control" value="250000" required>
            </div>
        </div>

        <!-- Cover Preview Image Upload -->
        <div class="form-group mb-3">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-image" style="color: var(--primary); margin-right: 0.35rem;"></i> Cover Preview Image (Upload)</span>
                <button type="button" id="toggleMarketUrlBtn" onclick="toggleMarketUrl()" style="background: none; border: none; color: var(--primary); font-size: 0.78rem; cursor: pointer; text-decoration: underline;">
                    Or specify image URL
                </button>
            </label>

            <div id="dropZoneMarket" style="border: 2px dashed var(--border); border-radius: 12px; padding: 1.5rem 1rem; text-align: center; background: var(--bg-card-subtle, rgba(255,255,255,0.03)); cursor: pointer; transition: all 0.25s ease;" onclick="document.getElementById('image_file').click();">
                <input type="file" name="image_file" id="image_file" accept="image/png, image/jpeg, image/webp, image/gif" style="display: none;" onchange="handleMarketImageSelect(this)">
                
                <div id="marketUploadPrompt">
                    <div style="width: 46px; height: 46px; margin: 0 auto 0.5rem; border-radius: 50%; background: var(--primary-light, rgba(59,130,246,0.1)); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">
                        Click to browse or drag & drop design cover screenshot
                    </div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                        PNG, JPG, WEBP or GIF (Max 5MB)
                    </div>
                </div>

                <div id="marketPreviewWrapper" style="display: none; align-items: center; justify-content: center; gap: 1.25rem; text-align: left; flex-wrap: wrap;">
                    <img id="marketPreviewImg" src="" alt="Selected Preview" style="max-height: 140px; max-width: 220px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                    <div>
                        <div id="marketPreviewName" style="font-weight: 700; color: var(--text-main); font-size: 0.9rem; word-break: break-all;"></div>
                        <div id="marketPreviewSize" style="font-size: 0.78rem; color: var(--text-muted); margin: 0.25rem 0 0.6rem;"></div>
                        <button type="button" class="btn btn-outline" style="padding: 0.3rem 0.75rem; font-size: 0.78rem;" onclick="event.stopPropagation(); resetMarketImage();">
                            <i class="fas fa-sync-alt"></i> Change Image
                        </button>
                    </div>
                </div>
            </div>

            <div id="marketUrlContainer" style="display: none; margin-top: 0.75rem;">
                <input type="url" name="image" id="market_image_url" class="form-control" value="" placeholder="https://images.unsplash.com/...">
                <small style="color: var(--text-muted); font-size: 0.75rem;">Direct image URL if hosted on a CDN.</small>
            </div>
        </div>

        <div class="form-group">
            <label>Live Demo URL</label>
            <input type="url" name="demo_url" class="form-control" placeholder="https://tektrend.com/demos/...">
        </div>

        <div class="form-group">
            <label>Short Summary (for Card Display)</label>
            <input type="text" name="short_description" class="form-control" placeholder="Luxury property showcase with 3D virtual tour viewer...">
        </div>

        <div class="form-group">
            <label>Full Technical Description & Features</label>
            <textarea name="description" class="form-control" placeholder="Describe frameworks, components, included source files, database schema..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/marketplace') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Publish Design Item</button>
        </div>
    </form>
</div>

<script>
function handleMarketImageSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (PNG, JPG, WEBP, or GIF).');
            input.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('File size exceeds 5MB limit.');
            input.value = '';
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('marketPreviewImg').src = e.target.result;
            document.getElementById('marketPreviewName').textContent = file.name;
            document.getElementById('marketPreviewSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
            document.getElementById('marketUploadPrompt').style.display = 'none';
            document.getElementById('marketPreviewWrapper').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
}

function resetMarketImage() {
    var input = document.getElementById('image_file');
    input.value = '';
    document.getElementById('marketPreviewImg').src = '';
    document.getElementById('marketUploadPrompt').style.display = 'block';
    document.getElementById('marketPreviewWrapper').style.display = 'none';
}

function toggleMarketUrl() {
    var box = document.getElementById('marketUrlContainer');
    var btn = document.getElementById('toggleMarketUrlBtn');
    if (box.style.display === 'none') {
        box.style.display = 'block';
        btn.textContent = 'Hide image URL';
    } else {
        box.style.display = 'none';
        btn.textContent = 'Or specify image URL';
    }
}
</script>
