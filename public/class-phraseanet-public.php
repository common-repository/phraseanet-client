<?php

global $wp_session;
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.alchemy.fr
 * @since      1.0.0
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/public
 * @author     Alchemy <maillat@alchemy.fr>
 */
class Phraseanet_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    private $phraseanet;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->phraseanetAuth(); //default phrasenet auth from wp admin
    }


    /**
     * Phraseanet Auth
     * @since 1.0.0
     */
    private function phraseanetAuth($username='', $password='')
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'phraseanet_sdk/Phraseanet_WP.php';


        $phraseanet_url 			= 	get_option('phraseanet_url');
        $phraseanet_client_id 		=  	get_option('phraseanet_client_id');
        $phraseanet_client_secret 	= 	get_option('phraseanet_client_secret');

        if (!empty($username) and !empty($password)) { //Check if it has username and password


            $phraseanet_username  		=  	$username;
            $phraseanet_password 		= 	$password;
            $phraseanet_auth_type       =   'login';
        } else {
            $phraseanet_username  		=  	get_option('phraseanet_username');
            $phraseanet_password 		= 	get_option('phraseanet_password');

            $phraseanet_token 			=   get_option('phraseanet_token');
            $phraseanet_auth_type		=	get_option('phraseanet_auth_type');

            $phraseanet_callback_url 	= 	get_option('phraseanet_callback_url'); //@todo
            $phraseanet_auth_required 	= 	get_option('phraseanet_auth_required'); //@todo
        }

        if (isset($_SESSION['token']) and !empty($_SESSION['token'])) { //Check if it has token in the session if yes use that token to authenticate the request

            $phraseanet_token =  sanitize_text_field($_SESSION['token']);
            $phraseanet_auth_type = '';
        }

        if ($this->isAdmin()) { //If user logged in as admin use auth from plugin settings


            $phraseanet_username  		=  	get_option('phraseanet_username');
            $phraseanet_password 		= 	get_option('phraseanet_password');

            $phraseanet_token 			=   get_option('phraseanet_token');
            $phraseanet_auth_type		=	get_option('phraseanet_auth_type');
        }




        return $this->phraseanet = new Phraseanet_WP($phraseanet_client_id, $phraseanet_client_secret, $phraseanet_url, $phraseanet_auth_type, ['phraseanet_username'=>$phraseanet_username, 'phraseanet_password'=>$phraseanet_password, 'phraseanet_token'=>$phraseanet_token]);
    }



    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         *
         * An instance of this class should be passed to the run() function
         * defined in Phraseanet_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Phraseanet_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

       
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . '../dist/front_end.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-bootstrap-phraseanet-min-css', plugin_dir_url(__FILE__) . 'css/bootstrap-phraseanet.css', array(), $this->version, 'all');
        
        
        wp_enqueue_style('dashicons');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         *
         * An instance of this class should be passed to the run() function
         * defined in Phraseanet_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Phraseanet_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name.'bootstrap-min-js', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array( 'jquery' ), rand(0, 100), true);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . '../dist/front_end.js', ['wp-i18n'], rand(0, 100), true);

        $licensing = array( 'can_use_premium_code' => load_license()->can_use_premium_code() );
        wp_localize_script('jquery', 'my_block_licensing_data', $licensing);
    }


    /**
     *Register shortcode for the plugin
     * @since 1.0.0
     */
    public function phraseanet_shortcode()
    {
        ob_start();

        $auth = get_post_meta(get_the_ID(), '__phraseanet_client_post_auth');


        /**
         * Load UI only on the frontpage
         */
        if (is_front_page() || is_singular() || is_archive()) {
            if (get_option('phraseanet_auth_required') or isset($auth[0]['is_auth']) and empty($_SESSION['token'])) {
                require_once 'partials/phraseanet-public-auth-form-display.php';
            } else {
                require_once 'partials/phraseanet-public-react-display.php';
            }
        }
        return ob_get_clean();
    }

    public function RenderPreview()
    {
        require_once 'partials/phraseanet-public-react-preview.php';
    }

    /**
     *
     * Get media assets from phraseanet
     * @since 1.0.0
     * @return json
     */
    public function getMediaAjax()
    {
        $data  = $this->phraseanet->getMedia(['searchQuery'=>'','searchType'=>'','recordType'=>'','pageNb'=>1,'']);

        header("Content-Type: application/json");
        echo json_encode($data);
        wp_die(); //required because wp return 0 at the end of the response
    }


    public function collection()
    {
        $data = $this->phraseanet->getCollection();
        header("Content-Type: application/json");
        echo json_encode($data);
        wp_die(); //required because wp return 0 at the end of the response
    }

    public function getDataboxStructure()
    {
        $data = $this->phraseanet->getDataboxStructure();
        header("Content-Type: application/json");
        echo json_encode($data);
        wp_die(); //required because wp return 0 at the end of the response
    }


    public function getFacets()
    {
        $data =  $this->phraseanet->getFacets();
        header("Content-Type: application/json");
        echo json_encode($data);
        wp_die(); //required because wp return 0 at the end of the response
    }

    public function getSubdefs()
    {
        $version = sanitize_text_field($_POST['version']);

        $data =  $this->phraseanet->getSubdefRaw($version);
        header("Content-Type: application/json");
        echo json_encode($data);
        wp_die(); //required because wp return 0 at the end of the response
    }



    /**
     * Make phraseanet auth with username and password - for public
     * @since 1.0.0
     */
    public function userPhraseanetAuth($username, $password)
    {
        return $this->phraseanetAuth($username, $password);
    }

    public function isAdmin()
    {
        if (!function_exists('wp_get_current_user')) {
            include(ABSPATH . "wp-includes/pluggable.php");
        }

        if (isset(wp_get_current_user()->caps['administrator'])) {
            if (wp_get_current_user()->caps['administrator']) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Admin block iframe preview - for admin
     */
    public function render_preview_page()
    {
        //Only invoke if query url has preview_post_id in it.

        if (isset($_GET['preview_post_id'])) {
            add_filter('show_admin_bar', '__return_false'); //Hide the admin menu bar if logged in
            add_filter('pre_get_posts', 'exclude_posts'); //Remove any post from the page
            require_once PHRASEANET_ROOT_PATH.'/public/partials/phraseanet-public-react-preview.php'; //Load the preview page
        }
    }


    
    public function downloader()
    {
        $this->phraseanet->download_file();
        return true;
    }

    /**
     * All the page specific config should be here in this API
     */
    public function pageConfig()
    {
        $page_id = sanitize_text_field($_POST['page_id']);

        $facets = get_post_meta($page_id, '__phraseanet_client_post_facets');
        $rfacets = get_post_meta($page_id, '__phraseanet_client_post_rfacets');
        $collections = get_post_meta($page_id, '__phraseanet_client_post_collections');
        $query = get_post_meta($page_id, '__phraseanet_client_post_query');
        $title = get_post_meta($page_id, '__phraseanet_client_post_title');
        $debug = get_post_meta($page_id, '__phraseanet_client_post_debug');
        $preview_details = get_post_meta($page_id, '__phraseanet_client_post_preview_details');
        $subdef = get_post_meta($page_id, '__phraseanet_client_post_subdef');
        $view = get_post_meta($page_id, '__phraseanet_client_post_ui');
        $jsonForms = get_post_meta($page_id, '__phraseanet_client_post_gForms');
        $grid_layout = get_post_meta($page_id, '__phraseanet_client_post_grid');
        $downloadableAssets = get_post_meta($page_id, '__phraseanet_client_post_downloadList');
        $permalinkAssets = get_post_meta($page_id, '__phraseanet_client_post_permalinkList');
        $enabled_facet = get_post_meta($page_id, '__phraseanet_client_post_enableFacets');
        $masonryImageLayout = get_post_meta($page_id, '__phraseanet_client_post_masonryImageLayout');
        $assetDetailsColorPalette = get_post_meta($page_id, '__phraseanet_client_post_asset_details_color_palette');

        $modal_bc = get_option('phraseanet_modal_bc');
        $modal_fc = get_option('phraseanet_modal_fc');


        $username = '';
        if (isset($_SESSION['username'])) {
            $username = sanitize_text_field($_SESSION['username']);
        }


        header("Content-Type: application/json");

        echo  json_encode(['facets'=>$facets,'rfacets'=>$rfacets,'collections'=>$collections,
                          'query'=>$query,'title'=>$title,'debug'=>$debug,'preview_details'=>$preview_details,
                        'subdef'=>$subdef,'view'=>$view,'username'=>$username,'jforms'=>$jsonForms,'grid_layout'=>$grid_layout,
                        'downloadable_assets'=>$downloadableAssets,'permalink_assets'=>$permalinkAssets,'enabled_facet'=>$enabled_facet,
                        'masonry_image_layout'=>$masonryImageLayout,'modal_fc'=>$modal_fc,'modal_bc'=>$modal_bc,'asset_details_color_palette'=>$assetDetailsColorPalette]);



        wp_die();
    }

    public function logout()
    {
        unset($_SESSION['token']);
        unset($_SESSION['username']);
        wp_die();
    }

    //Register the session
    public function registerSession()
    {
        if (! session_id()) {
            session_start();
        }
    }
}
