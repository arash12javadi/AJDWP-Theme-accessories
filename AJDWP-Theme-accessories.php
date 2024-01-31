<?php

if ( ! defined( 'ABSPATH' ) ) exit; 

/**
 * Plugin Name:       AJDWP-Theme-accessories
 * Plugin URI:        https://github.com/arash12javadi/
 * Description:       Convenient and essential functions, easily executable, all bundled in a single plugin. Enjoy :)
 * Version:           240131
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Arash Javadi
 * Author URI:        https://arashjavadi.com/  
 */


//__________________________________________________________________________//
//                               CODES HERE                   
//__________________________________________________________________________//

// Add the option to the admin settings page
add_action('admin_menu', 'theme_options_menu');

function theme_options_menu() {
    add_menu_page('Theme Accessories', 'Theme Accessories', 'manage_options', 'theme-addon-options', 'theme_options_page');
}

function theme_options_page() {
    ?>
    <div class="wrap">
        <h2>Theme needed functions</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('theme_options');
            do_settings_sections('theme-addon-options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register the option
add_action('admin_init', 'register_theme_options');


function register_theme_options() {
    //---------------- Limit users to acces login page ----------------//
    register_setting('theme_options', 'close_login_page');
    add_settings_section('theme_options_section', '', '', 'theme-addon-options');
    add_settings_field('close_login_page', 'Limit access to login page for users', 'close_login_page_callback', 'theme-addon-options', 'theme_options_section');
    
    //---------------- Limit admin access ----------------/
    register_setting('theme_options', 'limit_admin_access');
    add_settings_section('theme_options_section_2', '', '', 'theme-addon-options');
    add_settings_field('limit_admin_access', 'Limit access to admin side for users', 'limit_admin_access_callback', 'theme-addon-options', 'theme_options_section_2');
    
    //---------------- Hide admin bar ----------------/
    register_setting('theme_options', 'hide_admin_bar');
    add_settings_section('theme_options_section_3', '', '', 'theme-addon-options');
    add_settings_field('hide_admin_bar', 'Hide admin bar for users', 'hide_admin_bar_callback', 'theme-addon-options', 'theme_options_section_3');
    
    //---------------- Hide admin notices ----------------/
    register_setting('theme_options', 'hide_admin_notices');
    add_settings_section('theme_options_section_4', '', '', 'theme-addon-options');
    add_settings_field('hide_admin_notices', 'Hide notices from all theme and plugins', 'pl_hide_admin_notices_callback', 'theme-addon-options', 'theme_options_section_4');
    
    //---------------- limit user to access to their own posts only ----------------/
    register_setting('theme_options', 'limit_user_post_access');
    add_settings_section('theme_options_section_5', '', '', 'theme-addon-options');
    add_settings_field('limit_user_post_access', 'Limit user access their own posts only', 'pl_limit_user_post_access_callback', 'theme-addon-options', 'theme_options_section_5');
    
    //---------------- limit user to access to their own comments only ----------------/
    register_setting('theme_options', 'limit_user_comments_access');
    add_settings_section('theme_options_section_6', '', '', 'theme-addon-options');
    add_settings_field('limit_user_comments_access', 'Limit user access their own comments only', 'pl_limit_user_comments_access_callback', 'theme-addon-options', 'theme_options_section_6');
    
    //---------------- limit user to access to their own comments only ----------------/
    register_setting('theme_options', 'limit_user_media_access');
    add_settings_section('theme_options_section_7', '', '', 'theme-addon-options');
    add_settings_field('limit_user_media_access', 'Limit user access their own medias only', 'pl_limit_user_media_access_callback', 'theme-addon-options', 'theme_options_section_7');
    
    //---------------- Excerpt Lenght ----------------/
    register_setting('theme_options', 'excerpt_length');
    add_settings_section('theme_options_section_8', '', '', 'theme-addon-options');
    add_settings_field('excerpt_length', 'Excerpt Length', 'pl_excerpt_length_callback', 'theme-addon-options', 'theme_options_section_8');

    register_setting('theme_options', 'author_page_excerpt_length');
    add_settings_section('theme_options_section_9', '', '', 'theme-addon-options');
    add_settings_field('author_page_excerpt_length', 'Author Page Excerpt Length', 'pl_author_page_excerpt_length_callback', 'theme-addon-options', 'theme_options_section_9');

    //--------------------------- Stop wordpress to make junk files ---------------------------//
    register_setting('theme_options', 'wp_junk_file');
    add_settings_section('theme_options_section_10', '', '', 'theme-addon-options');
    add_settings_field('wp_junk_file', 'Stop wordpress make junk photos', 'pl_wp_junk_file_callback', 'theme-addon-options', 'theme_options_section_10');

}



//---------------- Limit users to acces login page ----------------//
function close_login_page_callback() {
    $value = get_option('close_login_page', 'off');
    echo '<label><input type="checkbox" name="close_login_page" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('close_login_page', 'off') === 'on') {
    function pl_redirect_login_page() {
        // Redirect to the custom login page
        $login_page = home_url('/user-profile/');
        $page_viewed = basename($_SERVER['REQUEST_URI']);
    
        if ($page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' && !current_user_can('manage_options')) {
            wp_redirect($login_page);
            exit;
        } 
    }
    add_action('init','pl_redirect_login_page');
}


//---------------- Limit admin access ----------------//
function limit_admin_access_callback() {
    $value = get_option('limit_admin_access', 'off');
    echo '<label><input type="checkbox" name="limit_admin_access" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('limit_admin_access', 'off') === 'on') {
    add_action('init', 'pl_restrict_wp_admin_access');

    function pl_restrict_wp_admin_access() {
        // Check if it's the admin area
        if (is_admin()) {
            // Get the current user
            $current_user = wp_get_current_user();
    
            // Check if the user is not an administrator
            if ( !in_array('administrator', $current_user->roles) || !current_user_can('manage_options') ) {
                // Redirect non-admin users to a custom page
                wp_redirect(home_url('/404'));
                exit();
            }
        }
    }
}

//---------------- Hide admin bar ----------------//
function hide_admin_bar_callback() {
    $value = get_option('hide_admin_bar', 'off');
    echo '<label><input type="checkbox" name="hide_admin_bar" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('hide_admin_bar', 'off') === 'on') {
    add_action('after_setup_theme', 'pl_remove_admin_bar');
    function pl_remove_admin_bar() {
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }

}



//---------------- Hide admin notices ----------------//
function pl_hide_admin_notices_callback() {
    $value = get_option('hide_admin_notices', 'off');
    echo '<label><input type="checkbox" name="hide_admin_notices" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('hide_admin_notices', 'off') === 'on') {
    add_action('init', 'pl_hide_admin_notices');

    function pl_hide_admin_notices() {
        function hide_all_admin_notices() {
            remove_all_actions('admin_notices');
        }
        add_action('admin_init', 'hide_all_admin_notices');
    }
}


//---------------- limit user to access to their own posts only ----------------//
function pl_limit_user_post_access_callback() {
    $value = get_option('limit_user_post_access', 'off');
    echo '<label><input type="checkbox" name="limit_user_post_access" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('limit_user_post_access', 'off') === 'on') {
    
    function pl_posts_for_current_author($query) {
        global $pagenow;
        
        if( 'edit.php' != $pagenow || !$query->is_admin )
            return $query;
        
        if( !current_user_can( 'edit_others_posts' ) ) {
            global $user_ID;
            $query->set('author', $user_ID );
        }
        return $query;
    }
    add_filter('pre_get_posts', 'pl_posts_for_current_author');
}


//---------------- limit user to access to their own comments only ----------------//
function pl_limit_user_comments_access_callback() {
    $value = get_option('limit_user_comments_access', 'off');
    echo '<label><input type="checkbox" name="limit_user_comments_access" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('limit_user_comments_access', 'off') === 'on') {
    function pl_limit_comments_to_author_posts($comments_query) {
        if (is_admin() && current_user_can('author')) {
            global $user_ID;
            $comments_query->query_vars['post_author'] = $user_ID;
        }
    }
    
    add_filter('pre_get_comments', 'pl_limit_comments_to_author_posts');
}


//---------------- limit user to access to their own medias only ----------------//
function pl_limit_user_media_access_callback() {
    $value = get_option('limit_user_media_access', 'off');
    echo '<label><input type="checkbox" name="limit_user_media_access" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('limit_user_media_access', 'off') === 'on') {
    
    add_filter( 'ajax_query_attachments_args', 'pl_kanithemes_show_current_user_attachments' );
    function pl_kanithemes_show_current_user_attachments( $query ) {
        $user_id = get_current_user_id();
        if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts') ) {
            $query['author'] = $user_id;
        }
        return $query;
    }

}



//---------------- Excerpt Lenght ----------------//

function pl_excerpt_length_callback() {
    $value = get_option('excerpt_length', 55); // Set a default value if not set
    echo '<input type="number" name="excerpt_length" value="' . esc_attr($value) . '" />';
}

function pl_author_page_excerpt_length_callback() {
    $value = get_option('author_page_excerpt_length', 100); // Set a default value if not set
    echo '<input type="number" name="author_page_excerpt_length" value="' . esc_attr($value) . '" />';
}

function pl_mytheme_custom_excerpt_length($length) {
    $excerpt_length = get_option('excerpt_length', 55); // Default value if not set
    $author_page_excerpt_length = get_option('author_page_excerpt_length', 100); // Default value if not set

    if (is_author()) {
        return $author_page_excerpt_length;
    } else {
        return $excerpt_length;
    }
}
add_filter('excerpt_length', 'pl_mytheme_custom_excerpt_length', 999);

//---------------- limit user to access to their own comments only ----------------//
function pl_wp_junk_file_callback() {
    $value = get_option('wp_junk_file', 'off');
    echo '<label><input type="checkbox" name="wp_junk_file" value="on" ' . checked('on', $value, false) . '></label>';
}

if (get_option('wp_junk_file', 'off') === 'on') {
    function add_image_insert_override( $sizes ){
        unset( $sizes[ 'thumbnail' ]);
        unset( $sizes[ 'medium' ]);
        unset( $sizes[ 'medium_large' ] );
        unset( $sizes[ 'large' ]);
        unset( $sizes[ 'full' ] );
        return $sizes;
    }
    add_filter( 'intermediate_image_sizes_advanced', 'add_image_insert_override' );
    
}
