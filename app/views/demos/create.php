<?php $pageTitle = 'Link New Project / Domain'; ?>

<div class="card mb-4" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Link New Project / Domain</h3>
            <p class="card-subtitle">Connect any project on any hosting domain to your live showcase portfolio</p>
        </div>
        <a href="<?= eurl('/demos') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Demos</a>
    </div>

    <form action="<?= eurl('/demos/store') ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Project Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Acme Fintech Banking Platform" required>
            </div>
            <div class="form-group">
                <label class="required">Category</label>
                <input type="text" name="category" class="form-control" placeholder="e.g. Fintech SaaS / E-Commerce / Legal" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Demo URL / Destination (External domain or local path)</label>
                <input type="text" name="demo_url" class="form-control" placeholder="https://clientdemo.myhosting.com or /live_demo/church.html" required>
                <small style="color: var(--text-muted); font-size: 0.75rem;">Can be on any cPanel, Vercel, Netlify, AWS, or custom subdomain.</small>
            </div>
            <div class="form-group">
                <label>Hosting Domain Display Label</label>
                <input type="text" name="hosting_domain" class="form-control" placeholder="e.g. app.vanguard.com">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Linkage Type</label>
                <select name="demo_type" class="form-control">
                    <option value="external">External Hosting Domain</option>
                    <option value="custom_domain">Custom Subdomain / Client Site</option>
                    <option value="local">Local Template File (/live_demo/..)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Icon Class (Font Awesome)</label>
                <input type="text" name="icon" class="form-control" value="fas fa-laptop-code" placeholder="fas fa-chart-line">
            </div>
        </div>

        <div class="form-group">
            <label>Short Description</label>
            <textarea name="short_description" class="form-control" rows="3" placeholder="Briefly describe what this site or platform accomplishes..."></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Tech Stack Tags (Comma separated)</label>
                <input type="text" name="tech_stack" class="form-control" value="PHP, MySQL, Vue, Tailwind" placeholder="PHP 8.2, React, AWS">
            </div>
            <div class="form-group">
                <label>Award Badge (Optional)</label>
                <input type="text" name="award_badge" class="form-control" placeholder="e.g. Site of the Day / Featured Client">
            </div>
        </div>

        <!-- Project Preview Image Upload (File Input) -->
        <div class="form-group mb-4">
            <label style="font-weight: 600; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fas fa-image" style="color: var(--primary); margin-right: 0.35rem;"></i> Project Preview Image (Upload)</span>
                <button type="button" id="toggleUrlBtn" onclick="toggleUrlMode()" style="background: none; border: none; color: var(--primary); font-size: 0.78rem; cursor: pointer; text-decoration: underline;">
                    Or specify external image URL
                </button>
            </label>

            <!-- File Upload Dropzone -->
            <div id="fileUploadContainer">
                <div id="dropZone" style="border: 2px dashed var(--border); border-radius: 12px; padding: 1.75rem 1rem; text-align: center; background: var(--bg-card-subtle, rgba(255,255,255,0.03)); cursor: pointer; transition: all 0.25s ease;" onclick="document.getElementById('preview_image_file').click();">
                    <input type="file" name="preview_image_file" id="preview_image_file" accept="image/png, image/jpeg, image/webp, image/gif" style="display: none;" onchange="handleImageSelect(this)">
                    
                    <div id="uploadPrompt">
                        <div style="width: 50px; height: 50px; margin: 0 auto 0.75rem; border-radius: 50%; background: var(--primary-light, rgba(59,130,246,0.1)); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">
                            Click to browse or drag & drop project screenshot
                        </div>
                        <div style="font-size: 0.78rem; color: var(--text-muted);">
                            Recommended: 1200 × 800px · PNG, JPG, WEBP or GIF (Max 5MB)
                        </div>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div id="imagePreviewWrapper" style="display: none; align-items: center; justify-content: center; gap: 1.25rem; text-align: left; flex-wrap: wrap;">
                        <img id="imagePreviewImg" src="" alt="Selected Preview" style="max-height: 140px; max-width: 220px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                        <div>
                            <div id="imagePreviewName" style="font-weight: 700; color: var(--text-main); font-size: 0.9rem; word-break: break-all;"></div>
                            <div id="imagePreviewSize" style="font-size: 0.78rem; color: var(--text-muted); margin: 0.25rem 0 0.6rem;"></div>
                            <button type="button" class="btn btn-outline" style="padding: 0.3rem 0.75rem; font-size: 0.78rem;" onclick="event.stopPropagation(); resetImageUpload();">
                                <i class="fas fa-sync-alt"></i> Change Image
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optional URL Fallback (Hidden by default) -->
            <div id="urlInputContainer" style="display: none; margin-top: 0.75rem;">
                <input type="text" name="preview_image" id="preview_image_url" class="form-control" placeholder="https://images.unsplash.com/photo-..." value="">
                <small style="color: var(--text-muted); font-size: 0.75rem;">Paste direct image URL if you are hosting the screenshot on a CDN or cloud storage.</small>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>
        </div>

        <div class="form-group mb-4">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" checked style="width: 18px; height: 18px;">
                <span style="font-weight: 600; color: var(--text-main);">Publish to Live Public Showcase & Homepage</span>
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/demos') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save & Publish Demo</button>
        </div>
    </form>
</div>

<script>
function handleImageSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        // Validate file type
        var validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (PNG, JPG, WEBP, or GIF).');
            input.value = '';
            return;
        }

        // Validate size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size exceeds 5MB limit. Please choose a smaller image.');
            input.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreviewImg').src = e.target.result;
            document.getElementById('imagePreviewName').textContent = file.name;
            document.getElementById('imagePreviewSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
            
            document.getElementById('uploadPrompt').style.display = 'none';
            document.getElementById('imagePreviewWrapper').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
}

function resetImageUpload() {
    var input = document.getElementById('preview_image_file');
    input.value = '';
    document.getElementById('imagePreviewImg').src = '';
    document.getElementById('uploadPrompt').style.display = 'block';
    document.getElementById('imagePreviewWrapper').style.display = 'none';
}

function toggleUrlMode() {
    var urlBox = document.getElementById('urlInputContainer');
    var toggleBtn = document.getElementById('toggleUrlBtn');
    if (urlBox.style.display === 'none') {
        urlBox.style.display = 'block';
        toggleBtn.textContent = 'Hide external image URL';
    } else {
        urlBox.style.display = 'none';
        toggleBtn.textContent = 'Or specify external image URL';
    }
}

// Drag & drop highlight
var dropZone = document.getElementById('dropZone');
if (dropZone) {
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--primary)';
            dropZone.style.background = 'rgba(59, 130, 246, 0.08)';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'var(--border)';
            dropZone.style.background = 'var(--bg-card-subtle, rgba(255,255,255,0.03))';
        }, false);
    });

    dropZone.addEventListener('drop', function(e) {
        var dt = e.dataTransfer;
        var files = dt.files;
        if (files && files.length) {
            var fileInput = document.getElementById('preview_image_file');
            fileInput.files = files;
            handleImageSelect(fileInput);
        }
    }, false);
}
</script>
