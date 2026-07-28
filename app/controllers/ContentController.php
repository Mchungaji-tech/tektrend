<?php
/**
 * Content Controller
 * Manage site content (CMS)
 */

class ContentController extends Controller {

    public function index() {
        $this->requireAuth();
        $page = $_GET['page'] ?? 'home';
        $contents = $this->db->fetchAll("SELECT * FROM content WHERE page = ? ORDER BY section, created_at DESC", [$page]);
        $pages = $this->db->fetchAll("SELECT DISTINCT page FROM content ORDER BY page");
        $this->render('content/index', ['contents' => $contents, 'pages' => $pages, 'currentPage' => $page]);
    }

    public function edit($key) {
        $this->requireAuth();
        $content = $this->db->fetch("SELECT * FROM content WHERE `key` = ?", [$key]);
        if (!$content) { $this->session->flash('error', 'Content not found.'); redirect('/content'); }
        $this->render('content/edit', ['content' => $content]);
    }

    public function update($key) {
        $this->requireAuth();
        $this->verifyCsrf();
        $content = $this->db->fetch("SELECT * FROM content WHERE `key` = ?", [$key]);
        if (!$content) { $this->session->flash('error', 'Content not found.'); redirect('/content'); }
        // Save to history
        $this->db->insert("INSERT INTO content_history (content_id, content, changed_by) VALUES (?, ?, ?)", [$content['id'], $content['content'], $this->auth->id()]);
        $this->db->execute(
            "UPDATE content SET title = ?, content = ?, type = ?, is_active = ?, updated_at = NOW() WHERE `key` = ?",
            [$_POST['title'] ?? null, $_POST['content'] ?? null, $_POST['type'] ?? 'html', $_POST['is_active'] ?? 1, $key]
        );
        auditLog('update', 'content', $content['id'], 'Updated content: ' . $key);
        $this->redirectWithSuccess('/content', 'Content updated successfully!');
    }
}
