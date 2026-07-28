<?php $pageTitle = 'Create Event'; ?>
<div class="topbar"><div class="greeting"><h1>Create Event</h1><p>Schedule a new event</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Event Information</h3></div>
    <form method="POST" action="/events">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">Title</label><input type="text" name="title" class="form-control" required></div>
            <div class="form-group"><label>Type</label><select name="type" class="form-control">
                <option value="meeting">Meeting</option><option value="conference">Conference</option><option value="deadline">Deadline</option><option value="holiday">Holiday</option>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="required">Start Date</label><input type="datetime-local" name="start_datetime" class="form-control" required></div>
            <div class="form-group"><label class="required">End Date</label><input type="datetime-local" name="end_datetime" class="form-control" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Location</label><input type="text" name="location" class="form-control"></div>
            <div class="form-group"><label>Department</label><select name="department_id" class="form-control">
                <option value="">Select Department</option>
                <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option><?php endforeach; ?>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Priority</label><select name="priority" class="class="form-control">
                <option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="urgent">Urgent</option>
            </select></div>
            <div class="form-group"><label>Color</label><input type="color" name="color" class="form-control" value="#3b82f6"></div>
        </div>
        <div class="form-group"><label>All Day</label><input type="checkbox" name="all_day" value="1"></div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <div class="form-group"><label>Attendees</label>
            <select name="attendees[]" class="form-control" multiple style="height: 100px;">
                <?php foreach ($users as $u): ?><option value="<?= $u['id'] ?>"><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Event</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
