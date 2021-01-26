# PeepSo Turbo

PeepSo turbo improves performance and the potential to also improve SEO ranking by preventing unneeded Javascript and CSS from loading for guests and search engines.

## Installation

To use the plugin, create the /wp-content/plugins/peepso-turbo directory and upload the peepso-turbo.php file. Then activate the plugin in WordPress.

The code is shared with the hope that it will be useful, but with no guarantees nor support. Make sure you test that all the functionality needed for guests works and it's not being affected by the plugin.

## Removing all PeepSo JS/CSS for specific pages

On some pages on your site, like privacy policy, terms, contact, etc. There's probably no need to load any JS/CSS. You can find function `peepso_turbo_is_remove_everything_page` in the code and adjust the code there to your specific needs:

```php
// Remove all CSS/JS for pages that include specific shortcodes    

if (strpos($post->post_content, '[some_shortcode]') !== false) {
    return true;
}

// Remove all CSS/JS for specific pages by slug

if (in_array($post->post_name,['privacy-policy'])) {
    return true;
}
```    

