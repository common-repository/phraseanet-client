<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.alchemy.fr
 * @since      1.0.0
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Phraseanet
 * @subpackage Phraseanet/includes
 * @author     Alchemy <maillat@alchemy.fr>
 */
class Phraseanet_Deactivator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        load_license()->add_action('after_uninstall', 'pwc_fs_uninstall_cleanup');
    }
}
