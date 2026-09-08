<?php $pageTitle = 'Edit Design Item'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Design Listing: <?= sanitize($item['title']) ?></h3>
            <p class="card-subtitle">Update pricing, award badge, or live auction status</p>
        </div>
        <a href="<?= eurl('/marketplace') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Marketplace</a>
    </div>

    <form method="POST" action="<?= eurl('/marketplace/' . $item['id']) ?>">
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

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Cover Preview Image URL</label>
                <input type="url" name="image" class="form-control" value="<?= sanitize($item['image']) ?>">
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
