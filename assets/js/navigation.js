/**
 * File navigation.js.
 * Handles toggling the navigation menu for small screens and enables TAB key navigation.
 */
document.addEventListener('DOMContentLoaded', () => {
    const siteNavigation = document.getElementById('site-navigation');
    
    if (!siteNavigation) {
        return;
    }

    const button = document.getElementById('menu-toggle');
    const menu = document.getElementById('primary-menu');

    // Hide button if menu is missing or empty
    if (!menu || !menu.childNodes.length) {
        button.style.display = 'none';
        return;
    }

    // Toggle the .is-toggled class and the aria-expanded attribute
    button.addEventListener('click', function() {
        siteNavigation.classList.toggle('is-toggled');

        if (button.getAttribute('aria-expanded') === 'true') {
            button.setAttribute('aria-expanded', 'false');
        } else {
            button.setAttribute('aria-expanded', 'true');
        }
    });

    // Close the menu if the user clicks outside of it
    document.addEventListener('click', function(event) {
        const isClickInside = siteNavigation.contains(event.target);

        if (!isClickInside && siteNavigation.classList.contains('is-toggled')) {
            siteNavigation.classList.remove('is-toggled');
            button.setAttribute('aria-expanded', 'false');
        }
    });
});