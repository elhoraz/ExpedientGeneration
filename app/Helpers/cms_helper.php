<?php

use App\Models\SiteContentModel;

if (!function_exists('cms_content')) {
    /**
     * Get CMS content and wrap it for inline editing if user is admin.
     *
     * @param string $key The unique content key
     * @param string $default_value Default value if not found in DB
     * @param string $type Content type ('text', 'image', 'html', 'raw')
     * @param string $extra_class Extra CSS class to preserve on the element
     * @param string $extra_attr Extra HTML attributes (e.g. alt="..." width="...")
     * @return string
     */
    function cms_content(string $key, string $default_value, string $type = 'text', string $extra_class = '', string $extra_attr = '')
    {
        // Cache management for performance
        $cache = \Config\Services::cache();
        $cacheKey = 'cms_content_' . $key;
        
        $content = $cache->get($cacheKey);
        
        if ($content === null) {
            $model = new SiteContentModel();
            $row = $model->where('content_key', $key)->first();
            
            if ($row) {
                $content = $row['content_value'];
            } else {
                // Auto-create default content
                $model->insert([
                    'content_key' => $key,
                    'content_value' => $default_value,
                    'content_type' => ($type === 'image' ? 'image' : ($type === 'html' ? 'html' : 'text'))
                ]);
                $content = $default_value;
            }
            
            // Cache for 1 day
            $cache->save($cacheKey, $content, 86400);
        }

        // Return raw content if requested (for attributes like placeholders, src, etc.)
        if ($type === 'raw') {
            return $content;
        }

        if ($type === 'image') {
            $cls = $extra_class ?: '';
            return '<img src="' . esc($content) . '"' . ($cls ? ' class="' . $cls . '"' : '') . ' ' . $extra_attr . '>';
        } else if ($type === 'html') {
            return $content;
        } else {
            return esc($content);
        }
    }
}

if (!function_exists('cms_text')) {
    /**
     * Render editable text. For admin: contenteditable span. For user: plain text.
     */
    function cms_text(string $key, string $default_value, string $extra_class = '', string $extra_attr = '')
    {
        return cms_content($key, $default_value, 'text', $extra_class, $extra_attr);
    }
}

if (!function_exists('cms_html')) {
    /**
     * Render editable HTML block. For admin: contenteditable div. For user: raw HTML.
     */
    function cms_html(string $key, string $default_value, string $extra_class = '', string $extra_attr = '')
    {
        return cms_content($key, $default_value, 'html', $extra_class, $extra_attr);
    }
}

if (!function_exists('cms_image')) {
    /**
     * Render editable image. For admin: img with data-cms-key (clickable to change).
     * For user: plain img tag. Extra classes are preserved.
     */
    function cms_image(string $key, string $default_url, string $extra_class = '', string $extra_attr = '')
    {
        return cms_content($key, $default_url, 'image', $extra_class, $extra_attr);
    }
}

if (!function_exists('cms_raw')) {
    /**
     * Get raw CMS value (no HTML wrapping). Useful for src attributes, placeholders, etc.
     */
    function cms_raw(string $key, string $default_value)
    {
        return cms_content($key, $default_value, 'raw');
    }
}
