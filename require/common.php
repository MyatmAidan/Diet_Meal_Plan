<?php
// Common functions and utilities
require_once(__DIR__ . '/i18n.php');

/**
 * Generate pagination HTML with smart page number display
 * Shows pages 1-3 and uses dots for other pages
 */
function generate_pagination($current_page, $total_pages, $search = '') {
    if ($total_pages <= 1) return '';
    
    $search_param = $search ? "&search=" . urlencode($search) : '';
    
    $html = '<nav aria-label="Page navigation" class="mt-4 d-flex justify-content-end align-items-center">
        <ul class="pagination justify-content-center" style="
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.5);
            padding: 12px 20px;
        ">';
    
    // Previous button
    if ($current_page > 1) {
        $html .= '<li class="page-item">
            <a class="page-link" href="?page=' . ($current_page - 1) . $search_param . '" style="
                background-color: rgba(255, 255, 255, 0.15);
                color: #0e0e0e;
                border: none;
                padding: 6px 14px;
                margin: 0 6px;
                border-radius: 10px;
                font-weight: 600;
                box-shadow: 0 0 6px rgba(255, 255, 255, 0.25);
                transition: 0.3s ease-in-out;
            ">&laquo; ' . __('နောက်သို့') . '</a>
        </li>';
    }
    
    // Page numbers
    if ($total_pages <= 7) {
        // If total pages is 7 or less, show all pages
        for ($p = 1; $p <= $total_pages; $p++) {
            $html .= generate_page_link($p, $current_page, $search_param);
        }
    } else {
        // Smart pagination for more than 7 pages
        if ($current_page <= 3) {
            // Show pages 1, 2, 3, ..., last
            for ($p = 1; $p <= 3; $p++) {
                $html .= generate_page_link($p, $current_page, $search_param);
            }
            $html .= generate_dots();
            $html .= generate_page_link($total_pages, $current_page, $search_param);
        } elseif ($current_page >= $total_pages - 2) {
            // Show pages 1, ..., last-2, last-1, last
            $html .= generate_page_link(1, $current_page, $search_param);
            $html .= generate_dots();
            for ($p = $total_pages - 2; $p <= $total_pages; $p++) {
                $html .= generate_page_link($p, $current_page, $search_param);
            }
        } else {
            // Show pages 1, ..., current-1, current, current+1, ..., last
            $html .= generate_page_link(1, $current_page, $search_param);
            $html .= generate_dots();
            for ($p = $current_page - 1; $p <= $current_page + 1; $p++) {
                $html .= generate_page_link($p, $current_page, $search_param);
            }
            $html .= generate_dots();
            $html .= generate_page_link($total_pages, $current_page, $search_param);
        }
    }
    
    // Next button
    if ($current_page < $total_pages) {
        $html .= '<li class="page-item">
            <a class="page-link" href="?page=' . ($current_page + 1) . $search_param . '" style="
                background-color: rgba(255, 255, 255, 0.15);
                color: #0e0e0e;
                border: none;
                padding: 6px 14px;
                margin: 0 6px;
                border-radius: 10px;
                font-weight: 600;
                box-shadow: 0 0 6px rgba(255, 255, 255, 0.25);
                transition: 0.3s ease-in-out;
            ">' . __('ရှေ့သို့') . ' &raquo;</a>
        </li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * Generate a single page link
 */
function generate_page_link($page_num, $current_page, $search_param) {
    $is_active = ($page_num == $current_page);
    $active_style = $is_active ? 'rgba(80, 77, 77, 0.49)' : 'rgba(255, 255, 255, 0.1)';
    $active_shadow = $is_active ? '0 0 10px rgba(255, 255, 255, 0.5)' : '0 0 5px rgba(255, 255, 255, 0.1)';
    
    return '<li class="page-item' . ($is_active ? ' active' : '') . '">
        <a class="page-link" href="?page=' . $page_num . $search_param . '" style="
            background-color: ' . $active_style . ';
            color: #000;
            font-weight: 600;
            padding: 6px 14px;
            margin: 0 6px;
            border-radius: 10px;
            border: none;
            box-shadow: ' . $active_shadow . ';
            transition: 0.3s ease-in-out;
        ">' . $page_num . '</a>
    </li>';
}

/**
 * Generate dots separator
 */
function generate_dots() {
    return '<li class="page-item disabled">
        <span class="page-link" style="
            background-color: transparent;
            color: #666;
            border: none;
            padding: 6px 8px;
            margin: 0 2px;
            font-weight: 600;
        ">...</span>
    </li>';
}
