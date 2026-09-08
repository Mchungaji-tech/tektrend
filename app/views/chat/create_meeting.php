<?php $pageTitle = 'Create Video Meeting'; ?>
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-video" style="color: var(--primary); margin-right: 0.4rem;"></i> Schedule Video Meeting</h3>
            <p class="card-subtitle">Setup internal conference or client teleconference</p>
        </div>
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Meetings</a>
    </div>

    <form method="POST" action="<?= eurl('/meetings') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Meeting Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Architecture Strategy & Sprint Kickoff" required>
        </div>

        <div class="form-group">
            <label>Meeting Purpose / Agenda</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Key topics to discuss..."></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Date & Start Time</label>
                <input type="datetime-local" name="start_time" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" required>
            </div>
            <div class="form-group">
                <label>Expected Duration (Minutes)</label>
                <input type="number" name="duration" class="form-control" value="45">
            </div>
        </div>

        <div class="form-group">
            <label>Meeting Platform Link</label>
            <input type="url" name="meeting_url" class="form-control" value="https://zoom.us/j/9924883102?pwd=tektrend_consult">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/meetings') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-video"></i> Create Video Room</button>
        </div>
    </form>
</div>
