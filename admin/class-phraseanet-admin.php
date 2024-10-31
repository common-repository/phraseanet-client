<?php

require_once 'metabox.php';
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.alchemy.fr
 * @since      1.0.0
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/admin
 * @author     Alchemy <maillat@alchemy.fr>
 */
class Phraseanet_Admin extends MetaBoxes
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

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/phraseanet-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-bootstrap-phraseanet-min-css', plugin_dir_url(__FILE__) . '../public/css/bootstrap-phraseanet.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/phraseanet-admin.js', array( 'jquery' ), rand(0, 100), false);

        wp_enqueue_script($this->plugin_name.'-bootstrap-min-js', plugin_dir_url(__FILE__) . '../public/js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false);
        wp_enqueue_script(
            $this->plugin_name.'-react-pages-min-js',
            plugin_dir_url(__FILE__) . '../dist/react_pages.js',
            array(
        'wp-blocks',
         ),
            $this->version,
            false
        );


        $licensing = array( 'can_use_premium_code' => load_license()->can_use_premium_code() );
        wp_localize_script('jquery', 'my_block_licensing_data', $licensing);
    }


    /**
     * Register a phraseanet gutenberg block
     *
     * @since 1.0.0
     */
    public function phraseanet_client_block_register()
    {
        wp_register_script(
            'phraseanet-client-block-editor-script',
            plugins_url('../dist/editor.js', __FILE__),
            array(
                            'wp-blocks',
                            'wp-i18n',
                            'wp-element',
                            'wp-components',
                            'wp-editor',
                            'wp-block-editor',
                            'wp-data',
                            'wp-edit-post',
                            'wp-plugins',
                            'wp-compose'


                )
        );

        register_block_type('phraseanet-client-block/client-block', array(

            'editor_script'=> 'phraseanet-client-block-editor-script',
            //'editor_style' => '' //We don't need styling right now ;)

        ));
    }


    /**
     * Register Phraseanet Menu in the admin
     *
     * @since 1.0.0
     */
    public function register_admin_menu()
    {
        add_menu_page(
            'Phraseanet Client settings',
            'Phraseanet client',
            'manage_options',
            'phraseanet-settings',
            array($this,'phraseanet_settings_auth'),
            plugins_url('images/phrasea-logo_sm.png', __FILE__),
            100
        );


        add_submenu_page('phraseanet-settings', ('Phraseanet '), __('Auth', 'phraseanet'), 'publish_posts', 'phraseanet-settings', '');
        add_submenu_page('phraseanet-settings', ('Phraseanet '), __('Settings', 'phraseanet'), 'publish_posts', 'phraseanet-settings-other', array($this,'phraseanet_settings_other'));
        add_submenu_page('phraseanet-settings', ('Phraseanet '), __('Requester', 'phraseanet'), 'publish_posts', 'phraseanet-settings-requester', array($this,'phraseanet_settings_requester'));
        add_submenu_page('phraseanet-settings', ('Phraseanet '), null, 'publish_posts', 'phraseanet-form-generator', array($this,'phraseanet_form_generator'));
        add_submenu_page('phraseanet-settings', ('Phraseanet '), __('Block settings', 'phraseanet'), 'publish_posts', 'phraseanet-block-settings', array($this,'phraseanet_block_settings'));
    }

    /**
     * Hides the Form edit page from the admin menu
     */
    public function hidesJsonPageLink()
    {
        remove_submenu_page('phraseanet-settings', 'phraseanet-form-generator');
    }

    public function phraseanet_set_script_translations()
    {
        wp_set_script_translations('phraseanet-client-block-editor-script', 'default', plugin_dir_path(__DIR__).'languages/');
    }

    /**
     * Return view for Auth settings screen
     * @since 1.0.0
     */
    public function phraseanet_settings_auth()
    {

        //Return view
        require_once 'partials/phraseanet-admin-display-auth.php';
    }


    /**
     * Return view for Other settings screen
     */
    public function phraseanet_settings_other()
    {

        //Return view
        require_once 'partials/phraseanet-admin-display-other-settings.php';
    }

    /**
     * Return view of requester AKA request builder
     * @since 1.0.0
     */
    public function phraseanet_settings_requester()
    {

        //Return view
        require_once 'partials/phraseanet-admin-display-requester.php';
    }

    public function phraseanet_form_generator()
    {

        //Return view
        require_once 'partials/phraseanet-admin-display-form-generator.php';
    }

    public function phraseanet_block_settings()
    {

        //Return view
        require_once 'partials/phraseanet-admin-display-block-settings.php';
    }


    //********** Custom posts are the saved config of the sub defination mapping and json generated forms - Saved in json format ****/

    /**
     * for form generator and block settings
     */
    public function custom_post_type()
    {
        register_post_type('generated_form', ['public'=>false,'label'=>'']);
        register_post_type('subdef_block_setting', ['public'=>false,'label'=>'']);
    }

    /**
     * for form generator
     */
    public function add_custom_post()
    {
        $postType = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'generated_form';
        $my_post = array(
            'post_title'    => sanitize_text_field($_POST['post_title']),
            'post_content'  => sanitize_text_field($_POST['post_content']),
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'=> $postType
          );

        // Insert the post into the database
        wp_insert_post($my_post);
        wp_die();
    }

    /**
     * for form generator
     */
    public function get_custom_post()
    {
        $custom_posts = [];
        $postType = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'generated_form';
        $args = array(
            'post_type'      => $postType,
            'posts_per_page' => -1,
        );

        $posts = get_posts($args);

        foreach ($posts as $post) {
            array_push($custom_posts, [$post->ID,$post->post_title]);
        }

        header("Content-Type: application/json");
        echo json_encode($custom_posts);
        wp_die();
    }

    public function get_custom_single_post()
    {
        $post = get_post(sanitize_text_field($_POST['id']));
        header("Content-Type: application/json");
        echo json_encode($post);
        wp_die();
    }

    public function edit_custom_single_post()
    {
        $postType = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'generated_form';

        $arg = array(
            'ID'            => sanitize_text_field($_POST['id']),
            'post_content'     => sanitize_text_field($_POST['content']),
            'post_type'      => $postType,
        );
        $post = wp_update_post($arg);
        header("Content-Type: application/json");
        echo json_encode($post);
        wp_die();
    }

    public function edit_custom_single_post_title()
    {
        $postType = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'generated_form';
        $arg = array(
            'ID'            => sanitize_text_field($_POST['id']),
            'post_title'     => sanitize_text_field($_POST['post_title']),
            'post_name'     => sanitize_text_field($_POST['post_title']),
            'post_type'      => $postType,
        );
        $post = wp_update_post($arg);
        header("Content-Type: application/json");
        echo json_encode($post);
        wp_die();
    }


    public function delete_custom_post()
    {
        wp_delete_post(sanitize_text_field($_POST['id']), true);
        header("Content-Type: application/json");
        wp_die();
    }


    /**
     *
     * @since 1.0.0
     */
    public function register_phraseanet_settings()
    {

        //Register all settings for phraseanet settings


        //Auth Settings
        register_setting('phraseanetClientSettings', 'phraseanet_url');
        register_setting('phraseanetClientSettings', 'phraseanet_client_id');
        register_setting('phraseanetClientSettings', 'phraseanet_client_secret');
        register_setting('phraseanetClientSettings', 'phraseanet_callback_url');
        register_setting('phraseanetClientSettings', 'phraseanet_auth_required');
        register_setting('phraseanetClientSettings', 'phraseanet_auth_type');
        register_setting('phraseanetClientSettings', 'phraseanet_username');
        register_setting('phraseanetClientSettings', 'phraseanet_password');
        register_setting('phraseanetClientSettings', 'phraseanet_token');

        //Other Settings
        register_setting('phraseanetClientOtherSettings', 'phraseanet_per_page_records');
        register_setting('phraseanetClientOtherSettings', 'phraseanet_modal_bc');
        register_setting('phraseanetClientOtherSettings', 'phraseanet_modal_fc');
    }

    public function modify_footer_admin()
    {
        echo '<div style="font-size: 13px;">Design & Developed with <span style="color:red" class="dashicons dashicons-heart"></span> by <a href="https://www.alchemy.fr/en/">Alchemy</a></div>';
    }
}
