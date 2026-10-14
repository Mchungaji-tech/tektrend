<?php
/**
 * Blog Controller
 * Public SEO-optimized articles (Insights / Engineering Journal) + admin CMS
 * Publishing fresh, keyword-rich content is the #1 on-page lever to rank higher on Google.
 */
class BlogController extends Controller {

    /**
     * Ensure the blog_posts table exists and seed initial SEO articles
     */
    protected function ensureBlogTable() {
        try {
            $this->db->execute("CREATE TABLE IF NOT EXISTS `blog_posts` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `slug` VARCHAR(255) NOT NULL,
                `category` VARCHAR(100) DEFAULT 'Engineering',
                `excerpt` TEXT DEFAULT NULL,
                `body` MEDIUMTEXT DEFAULT NULL,
                `cover_image` VARCHAR(500) DEFAULT NULL,
                `meta_title` VARCHAR(255) DEFAULT NULL,
                `meta_description` VARCHAR(320) DEFAULT NULL,
                `tags` VARCHAR(255) DEFAULT NULL,
                `author_name` VARCHAR(120) DEFAULT 'Tektrend Softwares',
                `status` ENUM('published','draft') DEFAULT 'draft',
                `is_featured` TINYINT(1) DEFAULT 0,
                `view_count` INT UNSIGNED DEFAULT 0,
                `published_at` DATETIME DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `slug` (`slug`),
                KEY `status` (`status`),
                KEY `category` (`category`),
                KEY `published_at` (`published_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            // Seed only when table is empty
            $count = $this->db->fetchColumn("SELECT COUNT(*) FROM blog_posts");
            if (!$count) {
                foreach ($this->seedPosts() as $p) {
                    $this->db->insert(
                        "INSERT INTO blog_posts (title, slug, category, excerpt, body, cover_image, meta_title, meta_description, tags, status, is_featured, published_at)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, NOW())",
                        [$p['title'], $p['slug'], $p['category'], $p['excerpt'], $p['body'], $p['cover_image'], $p['meta_title'], $p['meta_description'], $p['tags'], $p['is_featured']]
                    );
                }
            }
            return true;
        } catch (\Throwable $e) {
            error_log('ensureBlogTable error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Public blog index (SEO landing / insights hub)
     */
    public function index() {
        $this->ensureBlogTable();
        $posts = [];
        try {
            $posts = $this->db->fetchAll(
                "SELECT * FROM blog_posts WHERE status = 'published' ORDER BY published_at DESC, id DESC"
            );
        } catch (\Throwable $e) {
            $posts = [];
        }

        $settings = $this->getSettings();
        $perPage = 9;
        $page = max(1, (int)($_GET['page'] ?? 1));

        $this->view->setLayout(null);
        $this->render('blog/index', [
            'posts'    => $posts,
            'page'     => $page,
            'perPage'  => $perPage,
            'settings' => $settings,
        ], null);
    }

    /**
     * Public single article
     */
    public function show($slug) {
        $this->ensureBlogTable();
        $post = null;
        try {
            $post = $this->db->fetch(
                "SELECT * FROM blog_posts WHERE slug = ? AND status = 'published' LIMIT 1",
                [$slug]
            );
            if (!$post) {
                $post = $this->db->fetch(
                    "SELECT * FROM blog_posts WHERE id = ? AND status = 'published' LIMIT 1",
                    [(int)$slug]
                );
            }
        } catch (\Throwable $e) {
            $post = null;
        }

        if (!$post) {
            http_response_code(404);
            $this->session->flash('error', 'The article you are looking for could not be found.');
            redirect('/blog');
            return;
        }

        // Increment view count
        try {
            $this->db->execute("UPDATE blog_posts SET view_count = view_count + 1 WHERE id = ?", [$post['id']]);
        } catch (\Throwable $e) {}

        // Related posts (same category, fallback to latest)
        $related = [];
        try {
            $related = $this->db->fetchAll(
                "SELECT title, slug, excerpt, cover_image, category, published_at FROM blog_posts
                 WHERE status = 'published' AND id != ? ORDER BY (category = ?) DESC, published_at DESC LIMIT 3",
                [$post['id'], $post['category']]
            );
        } catch (\Throwable $e) {
            $related = [];
        }

        $settings = $this->getSettings();
        $this->view->setLayout(null);
        $this->render('blog/show', [
            'post'     => $post,
            'related'  => $related,
            'settings' => $settings,
        ], null);
    }

    /* --------------------------------------------------------------------- */
    /* Admin CMS                                                              */
    /* --------------------------------------------------------------------- */

    public function manage() {
        $this->requireAuth();
        $this->ensureBlogTable();
        $posts = $this->db->fetchAll("SELECT * FROM blog_posts ORDER BY published_at DESC, id DESC");
        $this->render('blog/manage', ['posts' => $posts, 'pageTitle' => 'Blog & Insights']);
    }

    public function create() {
        $this->requireAuth();
        $this->render('blog/create', ['pageTitle' => 'New Article']);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->ensureBlogTable();

        $validation = $this->validatePost([
            'title' => ['required' => true, 'label' => 'Title', 'min' => 4],
        ]);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            $this->session->flash('old', $_POST);
            redirect('/blog-manage/create');
            return;
        }

        $cover = $this->resolveCover();
        $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
        $slug = $this->uniqueSlug($_POST['slug'] ?? $_POST['title']);

        $id = $this->db->insert(
            "INSERT INTO blog_posts (title, slug, category, excerpt, body, cover_image, meta_title, meta_description, tags, author_name, status, is_featured, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $_POST['title'],
                $slug,
                $_POST['category'] ?? 'Engineering',
                $_POST['excerpt'] ?? '',
                $_POST['body'] ?? '',
                $cover,
                $_POST['meta_title'] ?? $_POST['title'],
                $_POST['meta_description'] ?? mb_substr(strip_tags($_POST['excerpt'] ?? $_POST['body'] ?? ''), 0, 160),
                $_POST['tags'] ?? '',
                $_POST['author_name'] ?? ($_SESSION['user_name'] ?? 'Tektrend Softwares'),
                $status,
                !empty($_POST['is_featured']) ? 1 : 0,
                $status === 'published' ? date('Y-m-d H:i:s') : null,
            ]
        );

        auditLog('create', 'blog_posts', $id, 'Created blog post: ' . $_POST['title']);
        $this->redirectWithSuccess('/blog-manage', 'Article saved successfully!');
    }

    public function edit($id) {
        $this->requireAuth();
        $this->ensureBlogTable();
        $post = $this->db->fetch("SELECT * FROM blog_posts WHERE id = ?", [(int)$id]);
        if (!$post) {
            $this->session->flash('error', 'Article not found.');
            redirect('/blog-manage');
            return;
        }
        $this->render('blog/edit', ['post' => $post, 'pageTitle' => 'Edit Article']);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int)$id;
        $post = $this->db->fetch("SELECT * FROM blog_posts WHERE id = ?", [$id]);
        if (!$post) {
            $this->session->flash('error', 'Article not found.');
            redirect('/blog-manage');
            return;
        }

        $validation = $this->validatePost(['title' => ['required' => true, 'label' => 'Title', 'min' => 4]]);
        if (!$validation['valid']) {
            $this->session->flash('error', reset($validation['errors']));
            redirect("/blog-manage/$id/edit");
            return;
        }

        $cover = $this->resolveCover($post['cover_image']);
        $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
        $slug = $this->uniqueSlug($_POST['slug'] ?? $_POST['title'], $id);
        $publishedAt = $post['published_at'];
        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        $this->db->execute(
            "UPDATE blog_posts SET title=?, slug=?, category=?, excerpt=?, body=?, cover_image=?, meta_title=?, meta_description=?, tags=?, author_name=?, status=?, is_featured=?, published_at=? WHERE id=?",
            [
                $_POST['title'],
                $slug,
                $_POST['category'] ?? 'Engineering',
                $_POST['excerpt'] ?? '',
                $_POST['body'] ?? '',
                $cover,
                $_POST['meta_title'] ?? $_POST['title'],
                $_POST['meta_description'] ?? mb_substr(strip_tags($_POST['excerpt'] ?? $_POST['body'] ?? ''), 0, 160),
                $_POST['tags'] ?? '',
                $_POST['author_name'] ?? ($post['author_name'] ?: 'Tektrend Softwares'),
                $status,
                !empty($_POST['is_featured']) ? 1 : 0,
                $publishedAt,
                $id,
            ]
        );

        auditLog('update', 'blog_posts', $id, 'Updated blog post: ' . $_POST['title']);
        $this->redirectWithSuccess('/blog-manage', 'Article updated successfully!');
    }

    public function destroy($id) {
        $this->requireAuth();
        $id = (int)$id;
        $post = $this->db->fetch("SELECT * FROM blog_posts WHERE id = ?", [$id]);
        if ($post) {
            $this->db->execute("DELETE FROM blog_posts WHERE id = ?", [$id]);
            auditLog('delete', 'blog_posts', $id, 'Deleted blog post: ' . $post['title']);
            $this->redirectWithSuccess('/blog-manage', 'Article removed.');
        } else {
            $this->session->flash('error', 'Article not found.');
            redirect('/blog-manage');
        }
    }

    /* --------------------------------------------------------------------- */
    /* Helpers                                                               */
    /* --------------------------------------------------------------------- */

    private function resolveCover($existing = '') {
        if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['cover_file'], 'blog');
            if ($upload['success']) {
                return '/uploads/' . $upload['filename'];
            }
        }
        if (!empty($_POST['cover_image'])) {
            return trim($_POST['cover_image']);
        }
        return $existing ?: 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1200&q=85';
    }

    private function uniqueSlug($seed, $ignoreId = 0) {
        $base = slugify($seed ?: 'post');
        if ($base === '-' || $base === '') {
            $base = 'post';
        }
        $slug = $base;
        $i = 2;
        while (true) {
            $exists = $this->db->fetch(
                "SELECT id FROM blog_posts WHERE slug = ? AND id != ? LIMIT 1",
                [$slug, (int)$ignoreId]
            );
            if (!$exists) {
                return $slug;
            }
            $slug = $base . '-' . $i;
            $i++;
        }
    }

    private function getSettings() {
        $settings = [];
        try {
            $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
            foreach ($rows as $row) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (\Throwable $e) {
            $settings = [];
        }
        return $settings;
    }

    /**
     * Seed content — targets high-value search intents for the Tektrend brand
     */
    private function seedPosts() {
        return [
            [
                'title' => 'How Much Does Custom Enterprise Software Cost in Kenya (2026)?',
                'slug' => 'custom-enterprise-software-cost-kenya-2026',
                'category' => 'Software Architecture',
                'excerpt' => 'A transparent pricing guide for custom web systems, cloud dashboards and turnkey platforms in Kenya. Understand what drives cost from KSh 150,000 to KSh 850,000 and how to scope your project.',
                'body' => "<p>One of the most common questions founders and operations managers ask us is: <strong>how much does custom software actually cost?</strong> The honest answer is that it depends on scope, integrations and scale — but here is a practical breakdown so you can budget with confidence.</p>\n\n<h2>What drives the price of a custom system</h2>\n<p>Cost is determined by four levers: the complexity of your business logic, the number of user roles and permissions, third-party integrations (payments, SMS, Google Workspace, ERP), and the quality of the reporting/dashboard layer. A basic CRUD app sits at the low end, while a multi-tenant platform with real-time analytics sits at the high end.</p>\n\n<h2>Typical ranges we deliver</h2>\n<ul>\n  <li><strong>Turnkey templates & prototypes:</strong> from KSh 65,000 — production-ready codebases you can deploy instantly.</li>\n  <li><strong>Custom enterprise web apps:</strong> KSh 150,000 – KSh 850,000 depending on complexity.</li>\n  <li><strong>Consulting retainers:</strong> from KSh 120,000/month for ongoing architecture & support.</li>\n</ul>\n\n<h2>Why custom beats off-the-shelf</h2>\n<p>Subscription SaaS feels cheap until per-seat pricing and feature caps compound. A bespoke PHP 8 MVC system removes monthly licensing, scales on your terms, and becomes a company asset rather than a recurring expense.</p>\n\n<p>Want an accurate number for your project? Book a free 45-minute Zoom architectural scoping session and we will map your scope to a fixed quote.</p>",
                'cover_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=85',
                'meta_title' => 'Custom Enterprise Software Cost in Kenya (2026 Pricing Guide) | Tektrend Softwares',
                'meta_description' => 'Transparent pricing for custom web systems, cloud dashboards & turnkey platforms in Kenya. See real cost ranges from KSh 150K to 850K and how to scope your project.',
                'tags' => 'software cost kenya, custom software development, enterprise software pricing',
                'is_featured' => 1,
            ],
            [
                'title' => 'Why PHP 8 MVC Still Powers High-Performance Enterprise Apps',
                'slug' => 'why-php-8-mvc-powers-enterprise-apps',
                'category' => 'Engineering',
                'excerpt' => 'Modern PHP 8 with a clean MVC architecture delivers speed, security and low hosting cost. Here is why we still choose it for production enterprise systems.',
                'body' => "<p>Despite the constant churn of JavaScript frameworks, <strong>PHP 8 with a disciplined MVC architecture</strong> remains one of the smartest choices for production business systems. Here is why.</p>\n\n<h2>Raw performance</h2>\n<p>PHP 8's JIT compiler, improved OPcache and lower memory footprint mean pages render fast without heavy client-side bundles. For data-driven dashboards and admin portals this translates directly into faster load times and better Core Web Vitals.</p>\n\n<h2>Security by architecture</h2>\n<p>A clean MVC separation keeps validation, prepared statements (PDO) and permission checks in the model layer — not scattered across views. Combined with CSRF tokens and hardened sessions, the attack surface stays small and auditable.</p>\n\n<h2>Cost efficiency</h2>\n<p>PHP runs on the cheapest, most ubiquitous hosting on earth. There is no Node process manager to babysit and no memory-hungry build server. For a company that needs to keep running costs lean, this is decisive.</p>\n\n<p>Every Tektrend system is built this way: zero-bloat, modular REST APIs, and turnkey source code you own outright.</p>",
                'cover_image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1200&q=85',
                'meta_title' => 'Why PHP 8 MVC Still Powers High-Performance Enterprise Apps | Tektrend',
                'meta_description' => 'Performance, security and hosting cost — why modern PHP 8 MVC is still the smart choice for production enterprise web systems in 2026.',
                'tags' => 'php 8, mvc architecture, web development, performance',
                'is_featured' => 0,
            ],
            [
                'title' => 'Google Workspace Automation: Turning Sheets Into a Real ERP',
                'slug' => 'google-workspace-automation-sheets-erp',
                'category' => 'Cloud Automation',
                'excerpt' => 'Google Apps Script can transform Sheets and Docs into automated reporting, invoicing and outreach pipelines — with zero maintenance cloud workflows.',
                'body' => "<p>Many teams already live inside Google Workspace but never unlock its automation potential. With <strong>Google Apps Script</strong> we turn ordinary Sheets into lightweight, zero-maintenance business systems.</p>\n\n<h2>Automated document generation</h2>\n<p>Invoices, offer letters and contracts can be generated from a template and emailed automatically the moment a row is added — no manual copy-paste, no typos.</p>\n\n<h2>Reporting pipelines</h2>\n<p>Script can pull live data, calculate KPIs, and email a formatted PDF report to management every Monday at 8am. Decision-making stops being a bottleneck.</p>\n\n<h2>When to graduate beyond Sheets</h2>\n<p>Sheets-based automation is perfect for validation and small teams. When you need roles, audit logs and high concurrency, we migrate the logic into a robust PHP/MySQL backend — keeping your data intact.</p>",
                'cover_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=85',
                'meta_title' => 'Google Workspace Automation: Turn Sheets Into a Real ERP | Tektrend',
                'meta_description' => 'Use Google Apps Script to automate invoicing, reporting and outreach — building zero-maintenance cloud workflows that scale into real ERP systems.',
                'tags' => 'google apps script, google workspace, automation, erp',
                'is_featured' => 0,
            ],
        ];
    }
}
