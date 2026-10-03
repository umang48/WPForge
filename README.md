## Performance & Security

This theme is aggressively optimized for Core Web Vitals and security.

### Security Hardening
* Form submission validated via `wp_verify_nonce()`
* AJAX requests secured with `check_ajax_referer()`
* Deep database queries isolated and sanitized via `$wpdb->prepare()`
* XSS mitigation applied through strict `esc_html()`, `esc_url()`, and `sanitize_text_field()` wrappers
* XML-RPC disabled to prevent DDoS / Brute Force attacks

### Lighthouse Performance
By dequeuing legacy WordPress bloat (Emojis, RSD, WLW), shifting scripts to the footer, and utilizing modern `theme.json` constraints, the theme achieves optimal rendering.

* **Before Optimization:** 78 / 100
* **After Optimization:** 98+ / 100