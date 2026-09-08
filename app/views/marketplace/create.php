<?php $pageTitle = 'Add Design Item'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Publish New Design for Bidding</h3>
            <p class="card-subtitle">List an award-winning web design template or custom portal</p>
        </div>
        <a href="<?= eurl('/marketplace') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Marketplace</a>
    </div>

    <form method="POST" action="<?= eurl('/marketplace') ?>">
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

        <div class="form-group">
            <label>Cover Preview Image URL</label>
            <input type="url" name="image" class="form-control" value="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80" placeholder="https://...">
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
