# WPForge — Modern WordPress Business Theme

A high-performance, modular, and secure WordPress business theme built from scratch to demonstrate senior-level theme architecture, modern Gutenberg integration, and custom API-driven interactivity.

**Author:** Umang Prajapati  
**Version:** 1.0.0  
**Requires PHP:** 7.4+ (Tested on 8.2)

---

## 🎯 Architecture & Technical Highlights

This project intentionally avoids page builders (like Elementor) and heavy plugin reliance to showcase native, code-level mastery of WordPress core functionality.

### 1. Modern Data Architecture
- **Custom Post Types & Taxonomies:** Cleanly registers `Project`, `Service`, and `Team Member` entities via a modular `inc/post-types.php` architecture.
- **Secure Custom Meta Boxes:** Implements native meta boxes (Client, Technology, URL) with strict data validation, CSRF nonce verification, and capability checks (`current_user_can( 'edit_post' )`).

### 2. Security Hardening
- **Strict Data Sanitization:** Comprehensive use of `sanitize_text_field()`, `sanitize_email()`, `esc_html()`, `esc_attr()`, and `esc_url()` across all templates and form handlers.
- **Database Security:** Demonstrates raw SQL querying safety using `$wpdb->prepare()` to prevent SQL injection vulnerabilities.
- **Form Handling:** Zero-plugin contact form featuring pre-render POST handling, nonce validation, sticky fields, and secure headers for `wp_mail()`.
- **Attack Surface Reduction:** Disables XML-RPC and strips WordPress version strings from the `wp_head` to prevent automated vulnerability scanning.

### 3. Performance & Optimization
- **Dynamic Cache-Busting:** Implements `filemtime()` in `wp_enqueue_scripts` for seamless active development without hard-refreshing.
- **Bloat Removal:** Aggressively dequeues legacy WordPress assets (RSD links, Windows Live Writer, core emoji scripts) to minimize HTTP requests.
- **Lighthouse Results:** 
  - *Before Optimization:* Performance: ~78
  - *After Optimization:* Performance: 95+ (Tested via PageSpeed Insights)

### 4. Custom APIs & Interactivity (Vanilla JS)
- **REST API Integration:** Registers a highly-optimized, custom REST route (`wpforge/v1/latest-projects`) that returns a clean JSON schema. Consumed asynchronously via ES6 `fetch()` on the front page.
- **AJAX Filtering:** Implements secure `admin-ajax.php` category filtering utilizing PHP output buffering (`ob_start()`) to maintain DRY template architecture.

### 5. Gutenberg-First Development
- **`theme.json` Configuration:** Enforces agency design tokens (colors, typography) and locks down the editor UI, preventing client-side design fragmentation.
- **Custom Block Styles:** Extends native Core blocks with custom CSS styles (e.g., "Card Panel", "Subtle Shadow") registered via PHP.
- **Block Patterns:** Utilizes WordPress 6.0+ auto-registering block patterns (`patterns/cta-section.php`) for rapid, high-conversion page building.

---

## 📁 Theme Structure

```text
wpforge-theme/
├── assets/
│   ├── css/ (main.css, responsive.css, editor.css)
│   └── js/  (main.js)
├── inc/
│   ├── setup.php          # add_theme_support, menus, image sizes
│   ├── enqueue.php        # Script/style loading with wp_localize_script
│   ├── post-types.php     # CPT & Taxonomy registration
│   ├── meta-boxes.php     # Secure custom fields
│   ├── block-styles.php   # Gutenberg extensions
│   ├── widgets.php        # Sidebar & Footer areas
│   ├── ajax.php           # admin-ajax.php endpoints
│   ├── rest-api.php       # Custom WP REST API routes
│   ├── security.php       # $wpdb->prepare & hardening
│   └── performance.php    # Head cleanup & optimization
├── patterns/              # Auto-registered Block Patterns
├── template-parts/        # DRY reusable template components
├── page-templates/        # Custom page layouts (e.g., Contact)
├── functions.php          # Modular bootstrap file
├── style.css              # Theme declaration & tokens
├── theme.json             # Gutenberg design system configuration
└── README.md