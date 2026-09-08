<?php $pageTitle = 'Link New Project / Domain'; ?>

<div class="card mb-4" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Link New Project / Domain</h3>
            <p class="card-subtitle">Connect any project on any hosting domain to your live showcase portfolio</p>
        </div>
        <a href="<?= eurl('/demos') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Demos</a>
    </div>

    <form action="<?= eurl('/demos/store') ?>" method="POST">
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

        <div style="display: grid; grid-template-columns: 1.5fr 0.5fr; gap: 1rem;">
            <div class="form-group">
                <label>Preview Image URL</label>
                <input type="text" name="preview_image" class="form-control" placeholder="https://images.unsplash.com/photo-..." value="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80">
            </div>
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
