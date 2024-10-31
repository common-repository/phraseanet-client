<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.alchemy.fr
 * @since             1.0.0
 * @package           Phraseanet
 *
 * @wordpress-plugin
 * Plugin Name:       Phraseanet Client
 * Plugin URI:        https://www.phraseanet.com/
 * Description:       This plugin creates the possibility to get and add assets from Phraseanet server into your wordpress website. This plugin allows you to create a phraseanet Gutenberg block with various custom configurations that allows you to customize the block.
* Version: 1.3.11 
 * Author:            Alchemy
 * Author URI:        https://www.alchemy.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       phraseanet
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

define('PHRASEANET_ROOT_PATH', plugin_dir_path(__FILE__));



/**
 * Add link to get user to Purchase Premium version of the plugin
 */

function add_action_links($links)
{
    $mylinks = array(
        '<a class="text-success h6" href="https://checkout.freemius.com/mode/dialog/plugin/9897/plan/16655/licenses/1/currency/eur/">Get Premium</a>',
    );
    return array_merge($links, $mylinks);
}

//Set currency to EUR
function plugin_default_currency($currency)
{
    return 'eur';
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define("PHRASEANET_VERSION", "1.3.11");  

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-phraseanet-activator.php
 */
function activate_phraseanet()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-phraseanet-activator.php';
    Phraseanet_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-phraseanet-deactivator.php
 */
function deactivate_phraseanet()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-phraseanet-deactivator.php';
    Phraseanet_Deactivator::deactivate();
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'activate_phraseanet');
register_deactivation_hook(__FILE__, 'deactivate_phraseanet');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-phraseanet.php';



 function load_license()
 {
     // Load the license class
     $pro = new PRO();

     global $pwc_fs;
     if (! isset($pwc_fs)) {
         $pwc_fs =  $pro->init();//init the license
     }


     //Show the get premium link if user is not a premium user
     if (!$pwc_fs->can_use_premium_code()) {
         add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');
     }

     //Change default currency
     $pwc_fs->add_filter('default_currency', 'plugin_default_currency');
    
     return $pwc_fs;
 }

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_phraseanet()
{
    $plugin = new Phraseanet();
    $plugin->run();
}
run_phraseanet();
