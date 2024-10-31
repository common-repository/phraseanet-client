<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.alchemy.fr
 * @since      1.0.0
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Phraseanet
 * @subpackage Phraseanet/includes
 * @author     Alchemy <maillat@alchemy.fr>
 */
class Phraseanet_Activator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $subdefs = get_posts(['post_type'=>'subdef_block_setting']);

        //Check if there is already a subdef_block_setting post if not create the default one
        if (empty($subdefs)) {
            $default_mapping =  PHRASEANET_ROOT_PATH.'sub-definitions.json';
            $file = fopen($default_mapping, 'r');
            $read_json = fread($file, filesize($default_mapping));

            $default_post = array(
                'post_title'    => 'Default Mapping',
                'post_content'  => $read_json,
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'=> 'subdef_block_setting'
              );
    
            // Insert the Sub defination into the database
            wp_insert_post($default_post);
            fclose($file);
        }
    }
}
