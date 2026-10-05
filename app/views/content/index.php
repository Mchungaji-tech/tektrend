<?php
$pageTitle = 'Front-End Visual CMS & Media Studio';
$activeTab = $activeTab ?? 'hero';

// Helper to retrieve current value from contentMap or fallback to defaultContent
function getCmsVal($key, $contentMap, $defaultContent) {
    if (isset($contentMap[$key]['content']) && $contentMap[$key]['content'] !== '') {
        return $contentMap[$key]['content'];
    }
    return $defaultContent[$key]['content'] ?? '';
}
?>

<!-- Studio Top Header -->
<div class="card mb-4" style="background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-card-subtle) 100%); border-left: 5px solid var(--primary);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                <span class="badge" style="background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border);">
                    <i class="fas fa-magic"></i> Live Front-End Studio
                </span>
                <span style="font-size: 0.75rem; color: var(--text-light);">Instant Sync with Homepage</span>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem;">
                Front-End Visual CMS & Media Control
            </h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Edit every headline, marketing word, trust metric, contact channel, and upload or link every photo on the public site.
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="<?= eurl('/') ?>" target="_blank" class="btn btn-outline" style="border-color: var(--primary-border);">
                <i class="fas fa-external-link-alt" style="color: var(--primary);"></i> View Live Front-End
            </a>
            <button type="submit" form="cmsStudioForm" class="btn btn-primary" style="box-shadow: 0 4px 14px rgba(79,70,229,0.35);">
                <i class="fas fa-save"></i> Save All Front-End Changes
            </button>
        </div>
    </div>
</div>

<!-- Studio Main Container with Sidebar Tabs & Content Panels -->
<div style="display: grid; grid-template-columns: 260px 1fr; gap: 1.5rem; align-items: start;">
    
    <!-- Tab Navigation -->
    <div class="card" style="padding: 1rem; position: sticky; top: 1.5rem;">
        <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 0.75rem; padding-left: 0.5rem;">
            CMS Sections & Media
        </div>
        <div class="cms-nav-tabs" style="display: flex; flex-direction: column; gap: 0.35rem;">
            <a href="?tab=hero" class="cms-tab-link <?= $activeTab === 'hero' ? 'active' : '' ?>">
                <i class="fas fa-heading"></i> Hero & Identity
            </a>
            <a href="?tab=services" class="cms-tab-link <?= $activeTab === 'services' ? 'active' : '' ?>">
                <i class="fas fa-cubes"></i> 4 Core Capabilities
            </a>
            <a href="?tab=photos" class="cms-tab-link <?= $activeTab === 'photos' ? 'active' : '' ?>">
                <i class="fas fa-images"></i> All Site Photos Studio
            </a>
            <a href="?tab=metrics" class="cms-tab-link <?= $activeTab === 'metrics' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Trust Metrics & Stats
            </a>
            <a href="?tab=showcase" class="cms-tab-link <?= $activeTab === 'showcase' ? 'active' : '' ?>">
                <i class="fas fa-laptop-code"></i> Featured Works (Demos)
            </a>
            <a href="?tab=consultation" class="cms-tab-link <?= $activeTab === 'consultation' ? 'active' : '' ?>">
                <i class="fas fa-video"></i> Zoom Scoping & Booking
            </a>
            <a href="?tab=investor" class="cms-tab-link <?= $activeTab === 'investor' ? 'active' : '' ?>">
                <i class="fas fa-gem"></i> Investor Relations Modal
            </a>
            <a href="?tab=footer" class="cms-tab-link <?= $activeTab === 'footer' ? 'active' : '' ?>">
                <i class="fas fa-shoe-prints"></i> Footer, Contacts & Brand
            </a>
            <a href="?tab=seo" class="cms-tab-link <?= $activeTab === 'seo' ? 'active' : '' ?>">
                <i class="fas fa-search"></i> SEO, Meta & OpenGraph
            </a>
        </div>

        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border); font-size: 0.78rem; color: var(--text-muted); text-align: center;">
            <i class="fas fa-shield-alt" style="color: var(--success); margin-right: 0.25rem;"></i> Automatic Revision History
        </div>
    </div>

    <!-- Active Form Panel -->
    <form id="cmsStudioForm" method="POST" action="<?= eurl('/content/bulk-update') ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="tab" value="<?= sanitize($activeTab) ?>">

        <!-- TAB 1: HERO & IDENTITY -->
        <?php if ($activeTab === 'hero'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-heading" style="color: var(--primary); margin-right: 0.5rem;"></i> Hero Section & Main Typography</h3>
                        <p class="card-subtitle">Manage the monumental landing page headline, lettermark, status pill, and disciplines</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Hero</button>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Eyebrow Tag (Upper Bracket)</label>
                        <input type="text" name="content[hero_eyebrow]" class="form-control" value="<?= sanitize(getCmsVal('hero_eyebrow', $contentMap, $defaultContent)) ?>">
                        <small style="color: var(--text-muted);">Displays top bracket e.g. [ SOFTWARE ARCHITECTURE & DIGITAL INNOVATION ]</small>
                    </div>
                    <div class="form-group">
                        <label>Live Commission Status Badge</label>
                        <input type="text" name="content[hero_tag_live]" class="form-control" value="<?= sanitize(getCmsVal('hero_tag_live', $contentMap, $defaultContent)) ?>">
                        <small style="color: var(--text-muted);">Right tag pill e.g. ACCEPTING Q4 / 2026 COMMISSIONS</small>
                    </div>
                </div>

                <div class="form-group">
                    <label>Monumental Brand Mark Letters (Hero Background Stage)</label>
                    <input type="text" name="content[hero_brand_letters]" class="form-control" value="<?= sanitize(getCmsVal('hero_brand_letters', $contentMap, $defaultContent)) ?>" style="font-family: monospace; font-size: 1.1rem; letter-spacing: 0.15em; font-weight: 800;">
                    <small style="color: var(--text-muted);">The giant typographic kinetic letters displayed on the hero (default: TEKTREND)</small>
                </div>

                <div class="form-group">
                    <label class="required">Hero Main Headline & Paragraph</label>
                    <textarea name="content[hero_headline]" class="form-control" style="min-height: 90px; font-size: 1rem; line-height: 1.6;"><?= sanitize(getCmsVal('hero_headline', $contentMap, $defaultContent)) ?></textarea>
                    <small style="color: var(--text-muted);">Appears directly underneath the giant hero letters. Strong bold statements.</small>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Primary Featured Project Button Text</label>
                        <input type="text" name="content[hero_featured_btn_text]" class="form-control" value="<?= sanitize(getCmsVal('hero_featured_btn_text', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div class="form-group">
                        <label>Secondary Explore Works Button Text</label>
                        <input type="text" name="content[hero_explore_btn_text]" class="form-control" value="<?= sanitize(getCmsVal('hero_explore_btn_text', $contentMap, $defaultContent)) ?>">
                    </div>
                </div>

                <!-- Hero Photo with Live Preview & File Picker -->
                <div class="form-group" style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border);">
                    <label style="font-size: 0.95rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-image" style="color: var(--primary);"></i> Hero Showcase Photo (Teaser & Background)
                    </label>
                    
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1.25rem; align-items: center;">
                        <div class="photo-preview-box" id="preview_hero_image_wrap" style="width: 140px; height: 100px; border-radius: var(--radius-sm); overflow: hidden; background: #000; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center;">
                            <img id="preview_hero_image" src="<?= sanitize(getCmsVal('hero_image', $contentMap, $defaultContent)) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80'">
                        </div>
                        <div>
                            <div style="margin-bottom: 0.5rem;">
                                <label style="font-size: 0.8rem; color: var(--text-muted);">Option A: Image Web URL</label>
                                <input type="text" name="content[hero_image]" id="input_hero_image" class="form-control" value="<?= sanitize(getCmsVal('hero_image', $contentMap, $defaultContent)) ?>" oninput="updatePhotoPreview('input_hero_image', 'preview_hero_image')" placeholder="https://images.unsplash.com/...">
                            </div>
                            <div>
                                <label style="font-size: 0.8rem; color: var(--text-muted);">Option B: Upload New Photo from Device</label>
                                <input type="file" name="file_hero_image" class="form-control" accept="image/*" onchange="previewUploadedFile(this, 'preview_hero_image')">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Disciplines -->
                <div style="margin-top: 1.5rem;">
                    <label style="font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem; display: block;">
                        Hero Footer Core Disciplines (4 items separated by bullet points)
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem;">
                        <input type="text" name="content[hero_discipline_1]" class="form-control" value="<?= sanitize(getCmsVal('hero_discipline_1', $contentMap, $defaultContent)) ?>" placeholder="Discipline 1">
                        <input type="text" name="content[hero_discipline_2]" class="form-control" value="<?= sanitize(getCmsVal('hero_discipline_2', $contentMap, $defaultContent)) ?>" placeholder="Discipline 2">
                        <input type="text" name="content[hero_discipline_3]" class="form-control" value="<?= sanitize(getCmsVal('hero_discipline_3', $contentMap, $defaultContent)) ?>" placeholder="Discipline 3">
                        <input type="text" name="content[hero_discipline_4]" class="form-control" value="<?= sanitize(getCmsVal('hero_discipline_4', $contentMap, $defaultContent)) ?>" placeholder="Discipline 4">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Hero Changes</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 2: CAPABILITIES & SERVICES -->
        <?php if ($activeTab === 'services'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-cubes" style="color: var(--primary); margin-right: 0.5rem;"></i> Capabilities & Services ("What We Architect")</h3>
                        <p class="card-subtitle">Every service section has a custom title, description, and hover preview photo input</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Services</button>
                </div>

                <!-- Section Header -->
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Services Badge</label>
                        <input type="text" name="content[services_badge]" class="form-control" value="<?= sanitize(getCmsVal('services_badge', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div class="form-group">
                        <label>Main Section Title</label>
                        <input type="text" name="content[services_title]" class="form-control" value="<?= sanitize(getCmsVal('services_title', $contentMap, $defaultContent)) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Services Subtitle / Philosophy</label>
                    <textarea name="content[services_subtitle]" class="form-control" style="min-height: 60px;"><?= sanitize(getCmsVal('services_subtitle', $contentMap, $defaultContent)) ?></textarea>
                </div>

                <!-- Service 1 to 4 Cards -->
                <?php for ($i = 1; $i <= 4; $i++): 
                    $numKey = "service_{$i}_num";
                    $titleKey = "service_{$i}_title";
                    $descKey = "service_{$i}_desc";
                    $imgKey = "service_{$i}_image";
                ?>
                    <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <span style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                                Service #<?= $i ?>: <?= sanitize(getCmsVal($titleKey, $contentMap, $defaultContent)) ?>
                            </span>
                            <span class="badge" style="background: var(--bg-card); color: var(--text-muted); border: 1px solid var(--border);">Index 0<?= $i - 1 ?></span>
                        </div>

                        <div style="display: grid; grid-template-columns: 80px 1fr; gap: 1rem; margin-bottom: 0.75rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label>Number</label>
                                <input type="text" name="content[<?= $numKey ?>]" class="form-control" value="<?= sanitize(getCmsVal($numKey, $contentMap, $defaultContent)) ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label>Service Title</label>
                                <input type="text" name="content[<?= $titleKey ?>]" class="form-control" value="<?= sanitize(getCmsVal($titleKey, $contentMap, $defaultContent)) ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Service Description</label>
                            <textarea name="content[<?= $descKey ?>]" class="form-control" style="min-height: 65px;"><?= sanitize(getCmsVal($descKey, $contentMap, $defaultContent)) ?></textarea>
                        </div>

                        <!-- Service Photo Input & Live Preview -->
                        <div style="background: var(--bg-card); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                            <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; display: block;">
                                <i class="fas fa-camera" style="color: var(--accent); margin-right: 0.35rem;"></i> Floating Interactive Hover Photo
                            </label>
                            <div style="display: grid; grid-template-columns: 120px 1fr; gap: 1rem; align-items: center;">
                                <div style="width: 120px; height: 80px; border-radius: 6px; overflow: hidden; background: #000; border: 1px solid var(--border);">
                                    <img id="preview_<?= $imgKey ?>" src="<?= sanitize(getCmsVal($imgKey, $contentMap, $defaultContent)) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80'">
                                </div>
                                <div>
                                    <div style="margin-bottom: 0.4rem;">
                                        <input type="text" name="content[<?= $imgKey ?>]" id="input_<?= $imgKey ?>" class="form-control" value="<?= sanitize(getCmsVal($imgKey, $contentMap, $defaultContent)) ?>" oninput="updatePhotoPreview('input_<?= $imgKey ?>', 'preview_<?= $imgKey ?>')" placeholder="Photo URL...">
                                    </div>
                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                        <input type="file" name="file_<?= $imgKey ?>" class="form-control" style="font-size: 0.8rem; padding: 0.35rem 0.65rem;" accept="image/*" onchange="previewUploadedFile(this, 'preview_<?= $imgKey ?>')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>

                <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Services</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 3: ALL SITE PHOTOS STUDIO -->
        <?php if ($activeTab === 'photos'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-images" style="color: var(--primary); margin-right: 0.5rem;"></i> Master Photo & Media Studio</h3>
                        <p class="card-subtitle">Every single photo rendered on the front end, with live preview and upload / URL replacement</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Photos</button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem;">
                    <?php 
                    $photoFields = [
                        'hero_image' => ['label' => 'Hero Showcase Photo', 'desc' => 'High-impact editorial image on main hero'],
                        'service_1_image' => ['label' => 'Service 1: Software Architecture Photo', 'desc' => 'Hover visual for Enterprise Architecture'],
                        'service_2_image' => ['label' => 'Service 2: Cloud Automation Photo', 'desc' => 'Hover visual for Google Workspace & Cloud'],
                        'service_3_image' => ['label' => 'Service 3: Real-Time Dashboards Photo', 'desc' => 'Hover visual for Dashboards & Metrics'],
                        'service_4_image' => ['label' => 'Service 4: Turnkey Templates Photo', 'desc' => 'Hover visual for Turnkey Web Codebases'],
                        'nav_teaser_img' => ['label' => 'Menu Teaser Card Photo', 'desc' => 'Teaser image in fullscreen sliding nav drawer'],
                        'og_image' => ['label' => 'OpenGraph Social Share Photo', 'desc' => 'Image shown when sharing link on Twitter, WhatsApp, FB']
                    ];
                    foreach ($photoFields as $fKey => $fInfo):
                        $currImg = getCmsVal($fKey, $contentMap, $defaultContent);
                    ?>
                        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.15rem; display: flex; flex-direction: column;">
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.92rem; margin-bottom: 0.2rem;">
                                <?= $fInfo['label'] ?>
                            </div>
                            <div style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.85rem;">
                                <?= $fInfo['desc'] ?>
                            </div>

                            <div style="width: 100%; height: 160px; border-radius: var(--radius-sm); overflow: hidden; background: #000; border: 1px solid var(--border); margin-bottom: 0.85rem; position: relative;">
                                <img id="preview_tab_<?= $fKey ?>" src="<?= sanitize($currImg) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80'">
                                <div style="position: absolute; bottom: 6px; right: 6px; background: rgba(0,0,0,0.7); color: #fff; font-size: 0.65rem; padding: 2px 6px; border-radius: 4px;">
                                    Live Preview
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0.5rem;">
                                <label style="font-size: 0.78rem; color: var(--text-muted);">Image URL</label>
                                <input type="text" name="content[<?= $fKey ?>]" id="input_tab_<?= $fKey ?>" class="form-control" value="<?= sanitize($currImg) ?>" oninput="updatePhotoPreview('input_tab_<?= $fKey ?>', 'preview_tab_<?= $fKey ?>')" placeholder="Paste https:// image URL...">
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 0.78rem; color: var(--text-muted);">Upload Device File</label>
                                <input type="file" name="file_<?= $fKey ?>" class="form-control" style="font-size: 0.8rem; padding: 0.35rem 0.65rem;" accept="image/*" onchange="previewUploadedFile(this, 'preview_tab_<?= $fKey ?>')">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Photos</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 4: TRUST METRICS & STATS -->
        <?php if ($activeTab === 'metrics'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-chart-line" style="color: var(--primary); margin-right: 0.5rem;"></i> Trust Metrics & Key Performance Numbers</h3>
                        <p class="card-subtitle">Displayed on the dark architectural break section to inspire high enterprise confidence</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Metrics</button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
                    <?php for ($m = 1; $m <= 4; $m++): 
                        $vKey = "metric_{$m}_val";
                        $lKey = "metric_{$m}_lbl";
                    ?>
                        <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
                            <div style="font-weight: 700; color: var(--primary); font-size: 0.85rem; margin-bottom: 0.75rem;">
                                Stat Counter #<?= $m ?>
                            </div>
                            <div class="form-group">
                                <label>Metric Number / Value</label>
                                <input type="text" name="content[<?= $vKey ?>]" class="form-control" value="<?= sanitize(getCmsVal($vKey, $contentMap, $defaultContent)) ?>" style="font-size: 1.25rem; font-weight: 800;">
                                <small style="color: var(--text-muted);">e.g. 99.9%, &lt; 85ms, 15+, 100%</small>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label>Metric Label</label>
                                <input type="text" name="content[<?= $lKey ?>]" class="form-control" value="<?= sanitize(getCmsVal($lKey, $contentMap, $defaultContent)) ?>">
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Trust Metrics</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 5: FEATURED WORKS / DEMOS MANAGER -->
        <?php if ($activeTab === 'showcase'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-laptop-code" style="color: var(--primary); margin-right: 0.5rem;"></i> Featured Prototypes & Works Showcase</h3>
                        <p class="card-subtitle">Manage each horizontal project card on the home page with titles, prices, live URLs & preview photos</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Showcases</button>
                </div>

                <?php if (empty($portfolioDemos)): ?>
                    <p style="color: var(--text-muted); padding: 2rem; text-align: center;">No featured demos in database yet. <a href="<?= eurl('/demos/create') ?>">Create one here</a>.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <?php foreach ($portfolioDemos as $demo): ?>
                            <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
                                <div style="display: grid; grid-template-columns: 140px 1fr 1fr 120px; gap: 1rem; align-items: center;">
                                    <!-- Photo Preview -->
                                    <div style="width: 140px; height: 90px; border-radius: var(--radius-sm); overflow: hidden; background: #000; border: 1px solid var(--border);">
                                        <img id="preview_demo_<?= $demo['id'] ?>" src="<?= sanitize($demo['preview_image']) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=400&q=80'">
                                    </div>

                                    <div>
                                        <label style="font-size: 0.75rem; color: var(--text-muted);">Title</label>
                                        <input type="text" name="demos[<?= $demo['id'] ?>][title]" class="form-control" value="<?= sanitize($demo['title']) ?>" required>
                                        <label style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem;">Category</label>
                                        <input type="text" name="demos[<?= $demo['id'] ?>][category]" class="form-control" value="<?= sanitize($demo['category']) ?>">
                                    </div>

                                    <div>
                                        <label style="font-size: 0.75rem; color: var(--text-muted);">Photo URL</label>
                                        <input type="text" name="demos[<?= $demo['id'] ?>][preview_image]" id="input_demo_<?= $demo['id'] ?>" class="form-control" value="<?= sanitize($demo['preview_image']) ?>" oninput="updatePhotoPreview('input_demo_<?= $demo['id'] ?>', 'preview_demo_<?= $demo['id'] ?>')">
                                        <label style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem;">Upload New Photo</label>
                                        <input type="file" name="demo_file_<?= $demo['id'] ?>" class="form-control" style="font-size: 0.8rem; padding: 0.3rem 0.6rem;" accept="image/*" onchange="previewUploadedFile(this, 'preview_demo_<?= $demo['id'] ?>')">
                                    </div>

                                    <div>
                                        <label style="font-size: 0.75rem; color: var(--text-muted);">Turnkey Price ($)</label>
                                        <input type="number" step="1" name="demos[<?= $demo['id'] ?>][price]" class="form-control" value="<?= sanitize($demo['price']) ?>">
                                        <label style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem;">Demo URL</label>
                                        <input type="text" name="demos[<?= $demo['id'] ?>][demo_url]" class="form-control" value="<?= sanitize($demo['demo_url']) ?>" style="font-size: 0.8rem;">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Featured Showcases</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 6: ZOOM SCOPING & BOOKING -->
        <?php if ($activeTab === 'consultation'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-video" style="color: var(--primary); margin-right: 0.5rem;"></i> Zoom Consultation & Booking Banner</h3>
                        <p class="card-subtitle">Scoping banner, CTA button, modal titles, and default Zoom meeting credentials</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Consultation</button>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Scoping Eyebrow Badge</label>
                        <input type="text" name="content[consult_badge]" class="form-control" value="<?= sanitize(getCmsVal('consult_badge', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div class="form-group">
                        <label>Consultation Headline</label>
                        <input type="text" name="content[consult_title]" class="form-control" value="<?= sanitize(getCmsVal('consult_title', $contentMap, $defaultContent)) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Pitch Description</label>
                    <textarea name="content[consult_desc]" class="form-control" style="min-height: 80px;"><?= sanitize(getCmsVal('consult_desc', $contentMap, $defaultContent)) ?></textarea>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>CTA Button Text</label>
                        <input type="text" name="content[consult_btn_text]" class="form-control" value="<?= sanitize(getCmsVal('consult_btn_text', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div class="form-group">
                        <label>Modal Headline</label>
                        <input type="text" name="content[consult_modal_title]" class="form-control" value="<?= sanitize(getCmsVal('consult_modal_title', $contentMap, $defaultContent)) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Modal Subtitle Description</label>
                    <input type="text" name="content[consult_modal_subtitle]" class="form-control" value="<?= sanitize(getCmsVal('consult_modal_subtitle', $contentMap, $defaultContent)) ?>">
                </div>

                <!-- Global Zoom Credentials -->
                <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; margin-top: 1rem;">
                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-video" style="color: #2d8cff; margin-right: 0.35rem;"></i> Official Zoom Room Connection
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Default Zoom Meeting Link</label>
                            <input type="text" name="settings[zoom_default_link]" class="form-control" value="<?= sanitize($settings['zoom_default_link'] ?? 'https://zoom.us/j/9924883102?pwd=tektrend_consult') ?>">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Default Meeting ID</label>
                            <input type="text" name="settings[zoom_meeting_id]" class="form-control" value="<?= sanitize($settings['zoom_meeting_id'] ?? '992-488-3102') ?>">
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Consultation Settings</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 7: INVESTOR RELATIONS MODAL -->
        <?php if ($activeTab === 'investor'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-gem" style="color: var(--accent); margin-right: 0.5rem;"></i> Investor Relations & Growth Capital Modal</h3>
                        <p class="card-subtitle">Pitch narrative and 3 key investor vitals displayed when clients click "Invest"</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Investor Modal</button>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Eyebrow Badge</label>
                        <input type="text" name="content[invest_badge]" class="form-control" value="<?= sanitize(getCmsVal('invest_badge', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div class="form-group">
                        <label>Modal Headline</label>
                        <input type="text" name="content[invest_title]" class="form-control" value="<?= sanitize(getCmsVal('invest_title', $contentMap, $defaultContent)) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Pitch Narrative Subtitle</label>
                    <textarea name="content[invest_subtitle]" class="form-control" style="min-height: 80px;"><?= sanitize(getCmsVal('invest_subtitle', $contentMap, $defaultContent)) ?></textarea>
                </div>

                <!-- 3 Investor Vitals -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <div style="background: var(--bg-card-subtle); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        <label style="font-size: 0.8rem; font-weight: 700;">Stat 1 (e.g. 11+)</label>
                        <input type="text" name="content[invest_stat_1_val]" class="form-control" value="<?= sanitize(getCmsVal('invest_stat_1_val', $contentMap, $defaultContent)) ?>" style="font-weight: 800; font-size: 1.2rem; margin-bottom: 0.5rem;">
                        <input type="text" name="content[invest_stat_1_lbl]" class="form-control" value="<?= sanitize(getCmsVal('invest_stat_1_lbl', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div style="background: var(--bg-card-subtle); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        <label style="font-size: 0.8rem; font-weight: 700;">Stat 2 (e.g. Eldoret, KE)</label>
                        <input type="text" name="content[invest_stat_2_val]" class="form-control" value="<?= sanitize(getCmsVal('invest_stat_2_val', $contentMap, $defaultContent)) ?>" style="font-weight: 800; font-size: 1.2rem; margin-bottom: 0.5rem;">
                        <input type="text" name="content[invest_stat_2_lbl]" class="form-control" value="<?= sanitize(getCmsVal('invest_stat_2_lbl', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div style="background: var(--bg-card-subtle); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        <label style="font-size: 0.8rem; font-weight: 700;">Stat 3 (e.g. 78%+)</label>
                        <input type="text" name="content[invest_stat_3_val]" class="form-control" value="<?= sanitize(getCmsVal('invest_stat_3_val', $contentMap, $defaultContent)) ?>" style="font-weight: 800; font-size: 1.2rem; margin-bottom: 0.5rem;">
                        <input type="text" name="content[invest_stat_3_lbl]" class="form-control" value="<?= sanitize(getCmsVal('invest_stat_3_lbl', $contentMap, $defaultContent)) ?>">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Investor Modal</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 8: FOOTER, CONTACTS & BRAND -->
        <?php if ($activeTab === 'footer'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-shoe-prints" style="color: var(--primary); margin-right: 0.5rem;"></i> Footer, Corporate Identity & Contacts</h3>
                        <p class="card-subtitle">Manage company bio, office location, official phone/WhatsApp, and social links</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Brand & Contacts</button>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Footer Brand Title</label>
                        <input type="text" name="content[footer_brand_title]" class="form-control" value="<?= sanitize(getCmsVal('footer_brand_title', $contentMap, $defaultContent)) ?>">
                    </div>
                    <div class="form-group">
                        <label>Footer Big Stamped Mark</label>
                        <input type="text" name="content[footer_big_mark]" class="form-control" value="<?= sanitize(getCmsVal('footer_big_mark', $contentMap, $defaultContent)) ?>" style="font-family: monospace; font-weight: 800;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Corporate Bio / Tagline</label>
                    <textarea name="content[footer_tagline]" class="form-control" style="min-height: 70px;"><?= sanitize(getCmsVal('footer_tagline', $contentMap, $defaultContent)) ?></textarea>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Office Headquarters Address</label>
                        <textarea name="content[footer_headquarters]" class="form-control" style="min-height: 60px;"><?= sanitize(getCmsVal('footer_headquarters', $contentMap, $defaultContent)) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Copyright Notice</label>
                        <input type="text" name="content[footer_copyright]" class="form-control" value="<?= sanitize(getCmsVal('footer_copyright', $contentMap, $defaultContent)) ?>">
                    </div>
                </div>

                <!-- Direct Communication Channels -->
                <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; margin-top: 1rem;">
                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-phone" style="color: var(--primary); margin-right: 0.35rem;"></i> Official Contact Channels
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Company Email</label>
                            <input type="email" name="settings[company_email]" class="form-control" value="<?= sanitize($settings['company_email'] ?? 'tektrend.softwares@gmail.com') ?>">
                        </div>
                        <div class="form-group">
                            <label>Direct Call Phone</label>
                            <input type="text" name="settings[company_phone]" class="form-control" value="<?= sanitize($settings['company_phone'] ?? '0707246273') ?>">
                        </div>
                        <div class="form-group">
                            <label>WhatsApp Number (International without +)</label>
                            <input type="text" name="settings[company_whatsapp]" class="form-control" value="<?= sanitize($settings['company_whatsapp'] ?? '254707246273') ?>">
                        </div>
                        <div class="form-group">
                            <label>Physical Address (Short)</label>
                            <input type="text" name="settings[company_address]" class="form-control" value="<?= sanitize($settings['company_address'] ?? 'Tektrend Softwares, Eldoret, Kenya') ?>">
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Brand & Contacts</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 9: SEO & OPEN GRAPH -->
        <?php if ($activeTab === 'seo'): ?>
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title"><i class="fas fa-search" style="color: var(--primary); margin-right: 0.5rem;"></i> SEO, Search Engines & Social Share Preview</h3>
                        <p class="card-subtitle">Control the Google search snippet, browser tab title, and OpenGraph social thumbnail</p>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save SEO Settings</button>
                </div>

                <div class="form-group">
                    <label class="required">Browser Title Tag (&lt;title&gt;)</label>
                    <input type="text" name="content[meta_title]" class="form-control" value="<?= sanitize(getCmsVal('meta_title', $contentMap, $defaultContent)) ?>" required>
                </div>

                <div class="form-group">
                    <label class="required">Meta Description (Search Snippet)</label>
                    <textarea name="content[meta_description]" class="form-control" style="min-height: 85px;"><?= sanitize(getCmsVal('meta_description', $contentMap, $defaultContent)) ?></textarea>
                    <small style="color: var(--text-muted);">Recommended length: 150-160 characters for peak Google ranking.</small>
                </div>

                <div class="form-group">
                    <label>Meta Keywords</label>
                    <input type="text" name="content[meta_keywords]" class="form-control" value="<?= sanitize(getCmsVal('meta_keywords', $contentMap, $defaultContent)) ?>">
                </div>

                <!-- OpenGraph Photo -->
                <div style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-top: 1rem;">
                    <label style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.5rem; display: block;">
                        <i class="fas fa-share-alt" style="color: var(--primary); margin-right: 0.35rem;"></i> Social Share Card Photo (OpenGraph / Twitter Card)
                    </label>
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1rem; align-items: center;">
                        <div style="width: 140px; height: 90px; border-radius: var(--radius-sm); overflow: hidden; background: #000; border: 1px solid var(--border);">
                            <img id="preview_og_image" src="<?= sanitize(getCmsVal('og_image', $contentMap, $defaultContent)) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=85'">
                        </div>
                        <div>
                            <div style="margin-bottom: 0.4rem;">
                                <input type="text" name="content[og_image]" id="input_og_image" class="form-control" value="<?= sanitize(getCmsVal('og_image', $contentMap, $defaultContent)) ?>" oninput="updatePhotoPreview('input_og_image', 'preview_og_image')" placeholder="Photo URL...">
                            </div>
                            <div>
                                <input type="file" name="file_og_image" class="form-control" style="font-size: 0.8rem; padding: 0.35rem 0.65rem;" accept="image/*" onchange="previewUploadedFile(this, 'preview_og_image')">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save SEO Settings</button>
                </div>
            </div>
        <?php endif; ?>
    </form>
</div>

<style>
.cms-tab-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 0.95rem;
    border-radius: var(--radius-sm);
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 600;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}
.cms-tab-link i {
    width: 20px;
    font-size: 0.95rem;
    color: var(--text-light);
}
.cms-tab-link:hover {
    background: var(--bg-card-subtle);
    color: var(--primary);
}
.cms-tab-link:hover i {
    color: var(--primary);
}
.cms-tab-link.active {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary-border);
    font-weight: 700;
}
.cms-tab-link.active i {
    color: var(--primary);
}
</style>

<script>
// Live Image Preview function from URL text input
function updatePhotoPreview(inputId, imgId) {
    const input = document.getElementById(inputId);
    const img = document.getElementById(imgId);
    if (input && img && input.value.trim() !== '') {
        img.src = input.value.trim();
    }
}

// Live Image Preview from File Upload picker
function previewUploadedFile(fileInput, imgId) {
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(imgId);
            if (img) {
                img.src = e.target.result;
            }
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
}
</script>
