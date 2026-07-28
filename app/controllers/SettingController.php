<?php
/**
 * Setting Controller
 * Manage system settings
 */

class SettingController extends Controller {

    public function index() {
        $this->requireAuth();
        $settings = $this->db->fetchAll("SELECT * FROM settings ORDER BY `group`, `key`");
        $grouped = [];
        foreach ($settings as $s) {
            $grouped[$s['group']][] = $s;
        }
        $this->render('settings/index', ['settings' => $grouped]);
    }

    public function update() {
        $this->requireAuth();
        $this->verifyCsrf();
        if (isset($_POST['settings']) && is_array($_POST['settings'])) {
            foreach ($_POST['settings'] as $key => $value) {
                $this->db->execute("UPDATE settings SET value = ? WHERE `key` = ?", [$value, $key]);
            }
        }
        auditLog('update', 'settings', null, 'Updated system settings');
        $this->redirectWithSuccess('/settings', 'Settings updated successfully!');
    }
}
