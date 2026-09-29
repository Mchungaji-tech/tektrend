<?php $pageTitle = 'Edit Design Item'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Design Listing: <?= sanitize($item['title']) ?></h3>
            <p class="card-subtitle">Update pricing, award badge, or live auction status</p>
        </div>
        <a href="<?= eurl('/marketplace') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Marketplace</a>
    </div>

    <form method="POST" action="<?= eurl('/marketplace/' . $item['id']) ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label>Design Title *</label>
            <input type="text" name="title" class="form-control" value="<?= sanitize($item['title']) ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Category *</label>
                <input type="text" name="category" class="form-control" value="<?= sanitize($item['category']) ?>" required>
            </div>
            <div class="form-group">
                <label>Award / Recognition Badge</label>
                <select name="award_badge" class="form-control">
                    <option value="Site of the Day" <?= $item['award_badge'] === 'Site of the Day' ? 'selected' : '' ?>>Site of the Day</option>
                    <option value="Developer Award" <?= $item['award_badge'] === 'Developer Award' ? 'selected' : '' ?>>Developer Award</option>
                    <option value="Site of the Month" <?= $item['award_badge'] === 'Site of the Month' ? 'selected' : '' ?>>Site of the Month</option>
                    <option value="Honorable Mention" <?= $item['award_badge'] === 'Honorable Mention' ? 'selected' : '' ?>>Honorable Mention</option>
                    <option value="Studio Featured" <?= $item['award_badge'] === 'Studio Featured' ? 'selected' : '' ?>>Studio Featured</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Starting Bid (KSh)</label>
                <input type="number" step="500" name="starting_bid" class="form-control" value="<?= $item['starting_bid'] ?>" required>
            </div>
            <div class="form-group">
                <label>Current Bid (KSh)</label>
                <input type="number" step="500" name="current_bid" class="form-control" value="<?= $item['current_bid'] ?>" required>
            </div>
            <div class="form-group">
                <label>Buy Now Price (KSh)</label>
                <input type="number" step="500" name="buy_now_price" class="form-control" value="<?= $item['buy_now_price'] ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Listing Status</label>
            <select name="status" class="form-control">
                <option value="active" <?= $item['status'] === 'active' ? 'selected' : '' ?>>Active (Open for Bids)</option>
                <option value="sold" <?= $item['status'] === 'sold' ? 'selected' : '' ?>>Sold / Acquired</option>
                <option value="ended" <?= $item['status'] === 'ended' ? 'selected' : '' ?>>Bidding Closed</option>
                <option value="draft" <?= $item['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>

        <!-- Cover Preview Image Upload -->
        <div class="form-group mb-3">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-image" style="color: var(--primary); margin-right: 0.35rem;"></i> Cover Preview Image (Upload)</span>
                <button type="button" id="toggleMarketUrlBtn" onclick="toggleMarketUrl()" style="background: none; border: none; color: var(--primary); font-size: 0.78rem; cursor: pointer; text-decoration: underline;">
                    Or specify image URL
                </button>
            </label>

            <!-- Current Image Thumbnail -->
            <?php if (!empty($item['image'])): ?>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.85rem; padding: 0.75rem 1rem; border-radius: 10px; background: var(--bg-card-subtle, rgba(255,255,255,0.03)); border: 1px solid var(--border);">
                    <img src="<?= eurl($item['image']) ?>" alt="Current Cover" style="width: 80px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);" onerror="this.style.display='none';">
                    <div style="overflow: hidden;">
                        <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-main);">Current Cover Image</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); word-break: break-all;"><?= sanitize($item['image']) ?></div>
                        <div style="font-size: 0.72rem; color: var(--accent); margin-top: 0.2rem;"><i class="fas fa-info-circle"></i> Upload a new file below to replace this image, or leave blank to keep it.</div>
                    </div>
                </div>
            <?php endif; ?>

            <div id="dropZoneMarket" style="border: 2px dashed var(--border); border-radius: 12px; padding: 1.5rem 1rem; text-align: center; background: var(--bg-card-subtle, rgba(255,255,255,0.03)); cursor: pointer; transition: all 0.25s ease;" onclick="document.getElementById('image_file').click();">
                <input type="file" name="image_file" id="image_file" accept="image/png, image/jpeg, image/webp, image/gif" style="display: none;" onchange="handleMarketImageSelect(this)">
                
                <div id="marketUploadPrompt">
                    <div style="width: 46px; height: 46px; margin: 0 auto 0.5rem; border-radius: 50%; background: var(--primary-light, rgba(59,130,246,0.1)); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">
                        <?= !empty($item['image']) ? 'Click or drag new image to replace current screenshot' : 'Click to browse or drag & drop design cover screenshot' ?>
                    </div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                        PNG, JPG, WEBP or GIF (Max 5MB)
                    </div>
                </div>

                <div id="marketPreviewWrapper" style="display: none; align-items: center; justify-content: center; gap: 1.25rem; text-align: left; flex-wrap: wrap;">
                    <img id="marketPreviewImg" src="" alt="Selected Preview" style="max-height: 140px; max-width: 220px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                    <div>
                        <span class="badge" style="background: var(--primary); color: #fff; font-size: 0.7rem; margin-bottom: 0.35rem; display: inline-block;">New Replacement Selected</span>
                        <div id="marketPreviewName" style="font-weight: 700; color: var(--text-main); font-size: 0.9rem; word-break: break-all;"></div>
                        <div id="marketPreviewSize" style="font-size: 0.78rem; color: var(--text-muted); margin: 0.25rem 0 0.6rem;"></div>
                        <button type="button" class="btn btn-outline" style="padding: 0.3rem 0.75rem; font-size: 0.78rem;" onclick="event.stopPropagation(); resetMarketImage();">
                            <i class="fas fa-times"></i> Cancel New Image
                        </button>
                    </div>
                </div>
            </div>

            <div id="marketUrlContainer" style="display: none; margin-top: 0.75rem;">
                <input type="url" name="image" id="market_image_url" class="form-control" value="<?= sanitize($item['image']) ?>" placeholder="https://images.unsplash.com/...">
                <small style="color: var(--text-muted); font-size: 0.75rem;">Direct image URL if hosted on a CDN.</small>
            </div>
        </div>

        <div class="form-group">
            <label>Live Demo URL</label>
            <input type="url" name="demo_url" class="form-control" value="<?= sanitize($item['demo_url']) ?>">
        </div>

        <div class="form-group">
            <label>Short Summary</label>
            <input type="text" name="short_description" class="form-control" value="<?= sanitize($item['short_description']) ?>">
        </div>

        <div class="form-group">
            <label>Full Description</label>
            <textarea name="description" class="form-control"><?= sanitize($item['description']) ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/marketplace') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
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
