<?php

require_once get_template_directory() . '/TGMPA/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'starter_theme_register_required_plugins' );

function starter_theme_register_required_plugins() {
    $plugins = array(
        array(
            'name'             => 'Advanced Custom Fields Pro',
            'slug'             => 'advanced-custom-fields-pro',
            'source'           => get_stylesheet_directory() . '/TGMPA/plugins/advanced-custom-fields-pro.zip',
            'required'         => true,
            'force_activation' => true,
        ),
        array(
            'name'             => 'Custom Post Type UI',
            'slug'             => 'custom-post-type-ui',
            'required'         => true,
            'force_activation' => true,
        ),
        array(
            'name'     => 'Debug Bar',
            'slug'     => 'debug-bar',
            'required' => false,
        ),
        array(
            'name'     => 'Post type selector for Advanced Custom Fields',
            'slug'     => 'acf-post-type-selector',
            'source'   => 'https://github.com/TimPerry/acf-post-type-selector/archive/v1.0.1.zip',
            'required' => false,
        ),
        array(
            'name'     => 'Redirection',
            'slug'     => 'redirection',
            'required' => false,
        ),
        array(
            'name'     => 'Simple Custom Post Order',
            'slug'     => 'simple-custom-post-order',
            'required' => true,
            'force_activation' => true,
        ),
        array(
            'name'     => 'Theme Check',
            'slug'     => 'theme-check',
            'required' => false,
        ),
        array(
            'name'     => 'Timber',
            'slug'     => 'timber-library',
            'required' => true,
            'force_activation' => true,
        ),
        array(
            'name'     => 'Timber Debug Bar',
            'slug'     => 'debug-bar-timber',
            'required' => false,
        ),
        array(
            'name'     => 'W3 Total Cache',
            'slug'     => 'w3-total-cache',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'starter-theme',         // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                    // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );
}

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}

function starter_admin_menu() {
//    remove_menu_page( 'index.php' );                  //Dashboard
//    remove_menu_page( 'edit.php' );                   //Posts
//    remove_menu_page( 'upload.php' );                 //Media
//    remove_menu_page( 'edit.php?post_type=page' );    //Pages
//    remove_menu_page( 'edit-comments.php' );          //Comments
//    remove_menu_page( 'themes.php' );                 //Appearance
//    remove_menu_page( 'plugins.php' );                //Plugins
//    remove_menu_page( 'users.php' );                  //Users
//    remove_menu_page( 'tools.php' );                  //Tools
//    remove_menu_page( 'options-general.php' );        //Settings

    if (get_the_user_ip() !== '::1') {
//        remove_menu_page( 'edit.php?post_type=acf-field-group' ); // ACF
//        remove_menu_page( 'cptui_main_menu' ); // CPT
    }
}
add_action( 'admin_menu', 'starter_admin_menu' );

//function remove_admin_bar_links() {
//    global $wp_admin_bar;
//    $wp_admin_bar->remove_menu('new-post');
//}
//add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

//function disable_new_post() {
//    if ( get_current_screen()->post_type == 'post' ) {
//        wp_die( "You are not allowed to do that!" );
//    }
//}
//add_action( 'load-post-new.php', 'disable_new_post' );

//function starter_pre_get_posts( $query ) {
//    if ( $query->is_main_query() && is_post_type_archive( 'blog' ) && !is_admin() ) {
//        $query->set( 'post_type', 'blog' );
//        $query->set( 'posts_per_page', 6 );
//    }
//}
//add_action( 'pre_get_posts', 'starter_pre_get_posts' );

function start_wp_enqueue_scripts() {
    $ver = '0.1.0.0';
    $ver_jquery = '3.3.1';
    $template_name = 'starter';

    if ( !is_admin()) {
        wp_deregister_script( 'jquery' );
        wp_register_script(
            'jquery',
            ( "//ajax.googleapis.com/ajax/libs/jquery/" . $ver_jquery . "/jquery.min.js" ),
            array(),
            $ver_jquery
        );
        wp_enqueue_script( 'jquery' );

//        wp_enqueue_style( 'vegas', get_template_directory_uri() . '/assets/js/vegas/vegas.css', array(), '2.4.0' );
//        wp_enqueue_script( 'vegas', get_template_directory_uri() . '/assets/js/vegas/vegas.min.js', array( 'jquery' ), '2.4.0', true );

        wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), $ver, true );

        wp_enqueue_style( $template_name.'-fonts', get_template_directory_uri() . '/assets/css/fonts.min.css', array(), $ver );

        wp_enqueue_style( $template_name, get_stylesheet_uri(), array(), $ver );
    }
}
add_action( 'wp_enqueue_scripts', 'start_wp_enqueue_scripts' );

function starter_theme_login_head() {
    wp_enqueue_style( 'admin_login', get_template_directory_uri() . '/css/admin-login.css' );
}
add_action( 'login_head', 'starter_theme_login_head' );

function starter_theme_login_headerurl( $url ) {
    return home_url();
}
add_filter( 'login_headerurl', 'starter_theme_login_headerurl' );

function starter_theme_login_headertitle() {
    return get_option( 'blogname' );
}
add_filter( 'login_headertitle', 'starter_theme_login_headertitle' );

function starter_menus() {
    register_nav_menus(
        array(
            'menu_header' => __( 'Header' ),
        )
    );
}
add_action( 'init', 'starter_menus' );

//function starter_register_fields() {
//    include_once( WP_PLUGIN_DIR.'/acf-post-type-selector/acf-post-type-selector.php' );
//}
//add_action( 'acf/include_fields', 'starter_register_fields' );

if (function_exists( 'acf_add_options_page' )) {
    acf_add_options_page(
        array(
            'icon_url'   => 'dashicons-admin-settings',
            'menu_title' => 'Site Settings',
            'menu_slug'  => 'starter-site-settings',
            'page_title' => 'Site Settings',
            'position'   => 25,
            'redirect'   => false
        )
    );
}

function get_the_user_ip() {
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
        $context['menu'] = new TimberMenu('menu_header');
        $context['site'] = $this;
        $context['options'] = get_fields('option');
        $context['default_featured'] = '';
        $context['cache'] = false;

        if (get_the_user_ip() !== '::1') { // not local server
            $context['cache'] = 600; // (60 * 10 = 600) 10 minutes
        }

        if (isset($context['options']['default_featured_image'])) {
            if (!empty($context['options']['default_featured_image'])) {
                $context['default_featured'] = new Timber\Image($context['options']['default_featured_image']);
            }
        }

        return $context;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		return $twig;
	}

}

new StarterSite();
