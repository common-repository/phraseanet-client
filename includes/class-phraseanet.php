<?php

        /**
         * The class responsible for defining all actions to Activate the Pro features of the plugin
         *
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-phraseanet-pro.php';
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.alchemy.fr
 * @since      1.0.0
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Phraseanet
 * @subpackage Phraseanet/includes
 * @author     Alchemy <maillat@alchemy.fr>
 */
class Phraseanet
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Phraseanet_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;





    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('PHRASEANET_VERSION')) {
            $this->version = PHRASEANET_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'Phraseanet Client';


        $this->load_license(); //init the license activator

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Phraseanet_Loader. Orchestrates the hooks of the plugin.
     * - Phraseanet_i18n. Defines internationalization functionality.
     * - Phraseanet_Admin. Defines all hooks for the admin area.
     * - Phraseanet_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-phraseanet-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-phraseanet-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-phraseanet-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-phraseanet-public.php';



        $this->loader = new Phraseanet_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Phraseanet_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Phraseanet_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Phraseanet_Admin($this->get_plugin_name(), $this->get_version());

        /** style and scripts */
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        /** register block , menu and settings */
        $this->loader->add_action('admin_init', $plugin_admin, 'phraseanet_client_block_register', 1);
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_admin_menu');

        /** Register shortcode */
        $this->loader->add_action('admin_init', $plugin_admin, 'register_phraseanet_settings');

        $this->loader->add_action('admin_init', $plugin_admin, 'phraseanet_set_script_translations');

        $this->loader->add_action('init', $plugin_admin, 'custom_post_type');
        $this->loader->add_action('admin_head', $plugin_admin, 'hidesJsonPageLink');
        $this->loader->add_action('admin_footer_text', $plugin_admin, 'modify_footer_admin');


        /** Register Meta */


        //Facets
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_metabox', 10, 2);

        //Show Facets bar
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_enableFacets');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_enableFacets');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_enableFacets_metabox', 10, 2);

        //Restricted facets
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_rfacets');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_rfacets');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_rfacets_metabox', 10, 2);

        //Collections
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_collections');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_collectons');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_metabox_collections', 10, 2);

        //Subdef Mapping
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_subdef');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_subdef');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_metabox_subdef', 10, 2);

        //Title
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_title');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_title');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_title_metabox', 10, 2);

        //Complex Query
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_query');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_query');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_metabox_query', 10, 2);

        //Auth
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_auth');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_auth');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_auth_metabox', 10, 2);


        //preview_details
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_preview_details');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_preview_details');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_preview_details_metabox', 10, 2);


        //UI
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_ui');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_ui');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_ui_metabox', 10, 2);

        //Grid
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_grid');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_grid');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_grid_metabox', 10, 2);

        //downloadList
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_downloadList');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_downloadList');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_downloadList_metabox', 10, 2);


        //downloadList
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_permalinkList');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_permalinkList');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_permalinkList_metabox', 10, 2);


        //Debug
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_debug');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_debug');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_debug_metabox', 10, 2);

        //Generated forms
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_gForms');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_gForms');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_gForms_metabox', 10, 2);

        //Generated forms
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_masonryImageLayout');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_masonryImageLayout');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_masonryImageLayout_metabox', 10, 2);

        //Color Palette on asset details
        $this->loader->add_action('init', $plugin_admin, 'phrasenet_client_register_meta_asset_details_color_palette');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, '_phraseanet_client_add_meta_box_asset_details_color_palette');
        $this->loader->add_action('save_post', $plugin_admin, '_phraseanet_client_save_post_asset_details_color_palette_metabox', 10, 2);






        //Ajax
        $this->loader->add_action('wp_ajax_add_custom_post', $plugin_admin, 'add_custom_post');
        $this->loader->add_action('wp_ajax_get_custom_post', $plugin_admin, 'get_custom_post');
        $this->loader->add_action('wp_ajax_get_custom_post', $plugin_admin, 'get_custom_post');
        $this->loader->add_action('wp_ajax_delete_custom_post', $plugin_admin, 'delete_custom_post');
        $this->loader->add_action('wp_ajax_nopriv_get_custom_single_post', $plugin_admin, 'get_custom_single_post');
        $this->loader->add_action('wp_ajax_get_custom_single_post', $plugin_admin, 'get_custom_single_post');
        $this->loader->add_action('wp_ajax_edit_custom_single_post', $plugin_admin, 'edit_custom_single_post');
        $this->loader->add_action('wp_ajax_edit_custom_single_post_title', $plugin_admin, 'edit_custom_single_post_title');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Phraseanet_Public($this->get_plugin_name(), $this->get_version());

        /** register styles and scripts */
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'registerSession');

        /** register shortcode for frontend */
        $this->loader->add_shortcode('phraseanet-client-block', $plugin_public, 'phraseanet_shortcode');

        /** Register render preview page */
        $this->loader->add_action('wp_head', $plugin_public, 'render_preview_page');

        $this->loader->add_action('init', $plugin_public, 'isAdmin');

        /** register ajax methods */
        $this->loader->add_action('wp_ajax_getMediaAjax', $plugin_public, 'getMediaAjax');
        //_nopriv user can access this api without login
        $this->loader->add_action('wp_ajax_nopriv_getMediaAjax', $plugin_public, 'getMediaAjax');

        $this->loader->add_action('wp_ajax_collection', $plugin_public, 'collection');
        $this->loader->add_action('wp_ajax_nopriv_collection', $plugin_public, 'collection');

        $this->loader->add_action('wp_ajax_getFacets', $plugin_public, 'getFacets');
        $this->loader->add_action('wp_ajax_nopriv_getFacets', $plugin_public, 'getFacets');

        $this->loader->add_action('wp_ajax_getDataboxStructure', $plugin_public, 'getDataboxStructure');
        $this->loader->add_action('wp_ajax_nopriv_getDataboxStructure', $plugin_public, 'getDataboxStructure');

        $this->loader->add_action('wp_ajax_downloader', $plugin_public, 'downloader');
        $this->loader->add_action('wp_ajax_nopriv_downloader', $plugin_public, 'downloader');

        $this->loader->add_action('wp_ajax_getSubdefs', $plugin_public, 'getSubdefs');
        $this->loader->add_action('wp_ajax_nopriv_getSubdefs', $plugin_public, 'getSubdefs');

        


        $this->loader->add_action('wp_ajax_pageConfig', $plugin_public, 'pageConfig');
        $this->loader->add_action('wp_ajax_nopriv_pageConfig', $plugin_public, 'pageConfig');

        $this->loader->add_action('wp_ajax_logout', $plugin_public, 'logout');
        $this->loader->add_action('wp_ajax_nopriv_logout', $plugin_public, 'logout');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Phraseanet_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Load the license system
     * @since 2.0.1
     * @return Object
     */
    public function load_license()
    {
        $pro = new PRO();

        // Init Freemius.
        return $pro->init();
        // Signal that SDK was initiated.
        do_action('pwc_fs_loaded');
        //$plugin_admin = new Phraseanet_Admin($this->get_plugin_name(), $this->get_version());
        /** style and scripts */
       // $this->loader->add_action('do_action', $plugin_admin, 'pwc_fs_loaded');
    }
}
