<?php

require_once get_template_directory() . '/TGMPA/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'starter_theme_register_required_plugins' );

function starter_theme_register_required_plugins()
{
    $plugins = array(
        array(
            'name'     => 'Advanced Custom Fields Pro',
            'slug'     => 'advanced-custom-fields-pro',
            'source'   => get_stylesheet_directory() . '/TGMPA/plugins/advanced-custom-fields-pro.zip',
            'required' => true,
            'force_activation' => true,
        ),
        array(
            'name'     => 'Custom Post Type UI',
            'slug'     => 'custom-post-type-ui',
            'required' => true,
            'force_activation' => true,
        ),
        array(
            'name'     => 'Debug Bar',
            'slug'     => 'debug-bar',
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
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new StarterSite();
