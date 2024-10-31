<?php

//With backward compatibility for older versions of WordPress
class MetaBoxes
{
    /**
     * Register meta for collections
     * @since 1.0.0
     */
    public function phrasenet_client_register_meta_collections()
    {
        register_meta('post', '__phraseanet_client_post_collections', array(
        'type'=>'object',
        'single'=>true,
        'show_in_rest'=>array(
            'schema'=>array(
              'type'=>'object',
              'properties'=>array('data'=>array('type'=>'array')))),
        'sanitize_callback'=> true,
        'auth_callback'=> function () {
            return current_user_can('edit_posts');
        },

        ));

        register_meta('post', '__phraseanet_client_post_static_collections', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                  'type'=>'object',
                  'properties'=>array('data'=>array('type'=>'array')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }



    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_collectons()
    {
        add_meta_box(
            '_phraseanet_client_post_metabox_collections',
            'collections_options',
            array($this,'_phraseanet_client_post_metabox_collections_html'),
            'post',
            'normal',
            'default',
            array(
                '__back_compat_meta_box' => true, //Hide the metabox
            )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_metabox_collections_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_metabox_collections', '_phraseanet_client_update_post_nonce_collections');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_metabox_collections($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_nonce_collections']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_nonce_collections'], '_phraseanet_client_update_post_metabox_collections')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_collections_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_collections',
                sanitize_text_field($_POST['_phraseanet_client_post_collections_field'])
            );

            update_post_meta(
                $post_id,
                '__phraseanet_client_post_static_collections',
                sanitize_text_field($_POST['_phraseanet_client_post_collections_field'])
            );
        }
    }



    /**
     * Register meta for subdef mapping
     * @since 1.0.0
     */
    public function phrasenet_client_register_meta_subdef()
    {
        register_meta('post', '__phraseanet_client_post_subdef', array(
        'type'=>'object',
        'single'=>true,
        'show_in_rest'=>array(
            'schema'=>array(
              'type'=>'object',
              'properties'=>array('preview_audio'=>array('type'=>'string','default'=>'document'),
              'thumbnail_audio'=>array('type'=>'string','default'=>'thumbnail'),
              'preview_video'=>array('type'=>'string','default'=>'document'),
              'thumbnail_video'=>array('type'=>'string','default'=>'thumbnail'),
              'preview'=>array('type'=>'string'),
              'thumbnail'=>array('type'=>'string','default'=>'thumbnail'),
              'preview_document'=>array('type'=>'string','default'=>'thumbnail'),
              'thumbnail_document'=>array('type'=>'string','default'=>'thumbnail'),
              'downloadable'=>array('type'=>'integer','default'=>0),
              'download'=>array('type'=>'string'),
              'permalink_shareable'=>array('type'=>'integer','default'=>0),
              'permalink'=>array('type'=>'string'),
              'configId'=>array('type'=>'integer','default'=>0),
            )
        )
    ),

        'sanitize_callback'=> true,
        'auth_callback'=> function () {
            return current_user_can('edit_posts');
        },

        ));
    }



    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_subdef()
    {
        add_meta_box(
            '_phraseanet_client_post_metabox_subdef',
            'subdef_options',
            array($this,'_phraseanet_client_post_metabox_subdef_html'),
            'post',
            'normal',
            'default',
            array(
                '__back_compat_meta_box' => true, //Hide the metabox
            )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_metabox_subdef_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_metabox_subdef', '_phraseanet_client_update_post_nonce_subdef');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_metabox_subdef($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_nonce_subdef']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_nonce_subdef'], '_phraseanet_client_update_post_metabox_subdef')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_subdef_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_subdef',
                sanitize_text_field($_POST['_phraseanet_client_post_subdef_field'])
            );
        }
    }




    /**
     * Register meta for complex Query
     * @since 1.0.0
     */
    public function phrasenet_client_register_meta_query()
    {
        register_meta('post', '__phraseanet_client_post_query', array(
        'type'=>'object',
        'single'=>true,
        'show_in_rest'=>array(
            'schema'=>array(
              'type'=>'object',
              'properties'=>array('allow_web'=>array('type'=>'boolean','default'=>true),'data'=>array('type'=>'string')))),
        'sanitize_callback'=> true,
        'auth_callback'=> function () {
            return current_user_can('edit_posts');
        },

        ));
    }



    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_query()
    {
        add_meta_box(
            '_phraseanet_client_post_metabox_query',
            'query_options',
            array($this,'_phraseanet_client_post_metabox_query_html'),
            'post',
            'normal',
            'default',
            array(
                '__back_compat_meta_box' => true, //Hide the metabox
            )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_metabox_query_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_metabox_query', '_phraseanet_client_update_post_nonce_query');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_metabox_query($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_nonce_query']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_nonce_query'], '_phraseanet_client_update_post_metabox_query')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_query_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_query',
                sanitize_text_field($_POST['_phraseanet_client_post_query_field'])
            );
        }
    }




    /**
         * Register meta for facets
         * @since 1.0.0
         */
    public function phrasenet_client_register_meta()
    {
        register_meta('post', '__phraseanet_client_post_facets', array(
        'type'=>'object',
        'single'=>true,
        'show_in_rest'=>array(
            'schema'=>array(
              'type'=>'object',
              'properties'=>array('name'=>array('type'=>'array'),'values'=>array('type'=>'array') ))),
        'sanitize_callback'=> true,
        'auth_callback'=> function () {
            return current_user_can('edit_posts');
        },

        ));
    }



    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box()
    {
        add_meta_box(
            '_phraseanet_client_post_metabox',
            'facets_options',
            array($this,'_phraseanet_client_post_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                '__back_compat_meta_box' => true, //Hide the metabox
            )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_metabox', '_phraseanet_client_update_post_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_nonce'], '_phraseanet_client_update_post_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_facets_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_facets',
                sanitize_text_field($_POST['_phraseanet_client_post_facets_field'])
            );
        }
    }




    /**
     * Register meta for restrited facets list in sidebar
     * @since 1.0.0
     */
    public function phrasenet_client_register_meta_rfacets()
    {
        register_meta('post', '__phraseanet_client_post_rfacets', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                  'type'=>'object',
                  'properties'=>array('name'=>array('type'=>'array')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }



    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_rfacets()
    {
        add_meta_box(
            '_phraseanet_client_post_rfacets_metabox',
            'facets_options',
            array($this,'_phraseanet_client_post_rfacets_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_rfacets_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_rfacets_metabox', '_phraseanet_client_update_post_rfacets_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_rfacets_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_rfacets_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_rfacets_nonce'], '_phraseanet_client_update_post_rfacets_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_rfacets_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_rfacets',
                sanitize_text_field($_POST['_phraseanet_client_post_rfacets_field'])
            );
        }
    }




    /**
    * Register meta for block title
    * @since 1.0.0
    */
    public function phrasenet_client_register_meta_title()
    {
        register_meta('post', '__phraseanet_client_post_title', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                  'type'=>'string') ),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_title()
    {
        add_meta_box(
            '_phraseanet_client_post_title_metabox',
            'block_title',
            array($this,'_phraseanet_client_post_title_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_title_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_title_metabox', '_phraseanet_client_update_post_title_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_title_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_title_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_title_nonce'], '_phraseanet_client_update_post_title_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_title_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_title',
                sanitize_text_field($_POST['_phraseanet_client_post_title_field'])
            );
        }
    }





    /**
    * Register meta for block Auth
    * @since 1.0.0
    */
    public function phrasenet_client_register_meta_auth()
    {
        register_meta('post', '__phraseanet_client_post_auth', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('is_auth'=>array('type'=>'boolean','default'=>false)))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_auth()
    {
        add_meta_box(
            '_phraseanet_client_post_auth_metabox',
            'block_auth',
            array($this,'_phraseanet_client_post_auth_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_auth_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_auth_metabox', '_phraseanet_client_update_post_auth_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_auth_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_auth_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_auth_nonce'], '_phraseanet_client_update_post_auth_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_auth_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_auth',
                sanitize_text_field($_POST['_phraseanet_client_post_auth_field'])
            );
        }
    }










    /**
    * Register meta for block Preview details
    * @since 1.0.0
    */
    public function phrasenet_client_register_meta_preview_details()
    {
        register_meta('post', '__phraseanet_client_post_preview_details', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('details'=>array('type'=>'array')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_preview_details()
    {
        add_meta_box(
            '_phraseanet_client_post_preview_details_metabox',
            'block_preview_details',
            array($this,'_phraseanet_client_post_preview_details_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_preview_details_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_preview_details_metabox', '_phraseanet_client_update_post_preview_details_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_preview_details_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_preview_details_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_preview_details_nonce'], '_phraseanet_client_update_post_preview_details_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_preview_details_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_preview_details',
                sanitize_text_field($_POST['_phraseanet_client_post_preview_details_field'])
            );
        }
    }









    /**
    * Register meta for UI
    * @since 1.0.0
    */
    public function phrasenet_client_register_meta_ui()
    {
        register_meta('post', '__phraseanet_client_post_ui', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('layout'=>array('type'=>'string','default'=>'overlay')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_ui()
    {
        add_meta_box(
            '_phraseanet_client_post_ui_metabox',
            'block_ui',
            array($this,'_phraseanet_client_post_ui_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_ui_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_ui_metabox', '_phraseanet_client_update_post_ui_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_ui_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_ui_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_ui_nonce'], '_phraseanet_client_update_post_ui_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_ui_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_ui',
                sanitize_text_field($_POST['_phraseanet_client_post_ui_field'])
            );
        }
    }






    /**
    * Register meta for block debug
    * @since 1.0.0
    */
    public function phrasenet_client_register_meta_debug()
    {
        register_meta('post', '__phraseanet_client_post_debug', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('is_debug'=>array('type'=>'boolean','default'=>false)))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_debug()
    {
        add_meta_box(
            '_phraseanet_client_post_debug_metabox',
            'block_debug',
            array($this,'_phraseanet_client_post_debug_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_debug_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_debug_metabox', '_phraseanet_client_update_post_debug_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_debug_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_debug_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_debug_nonce'], '_phraseanet_client_update_post_debug_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_debug_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_debug',
                sanitize_text_field($_POST['_phraseanet_client_post_debug_field'])
            );
        }
    }





    /**
    * Register meta for generated forms
    * @since 1.0.0
    */
    public function phrasenet_client_register_meta_gForms()
    {
        register_meta('post', '__phraseanet_client_post_gForms', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('gForm'=>array('type'=>'integer')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.0.0
     */
    public function _phraseanet_client_add_meta_box_gForms()
    {
        add_meta_box(
            '_phraseanet_client_post_gForms_metabox',
            'block_gForms',
            array($this,'_phraseanet_client_post_gForms_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.0.0
     */
    public function _phraseanet_client_post_gForms_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_gForms_metabox', '_phraseanet_client_update_post_gForms_nonce');
    }

    /**
     * Save meta value
     * @since 1.0.0
     */
    public function _phraseanet_client_save_post_gForms_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_gForms_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_gForms_nonce'], '_phraseanet_client_update_post_gForms_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_gForms_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_gForms',
                sanitize_text_field($_POST['_phraseanet_client_post_gForms_field'])
            );
        }
    }






    /**
    * Register meta for grid
    * @since 1.2.0
    */
    public function phrasenet_client_register_meta_grid()
    {
        register_meta('post', '__phraseanet_client_post_grid', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('grid'=>array('type'=>'string','default'=>'masonry')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.2.0
     */
    public function _phraseanet_client_add_meta_box_grid()
    {
        add_meta_box(
            '_phraseanet_client_post_grid_metabox',
            'block_grid',
            array($this,'_phraseanet_client_post_grid_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.2.0
     */
    public function _phraseanet_client_post_grid_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_grid_metabox', '_phraseanet_client_update_post_grid_nonce');
    }

    /**
     * Save meta value
     * @since 1.2.0
     */
    public function _phraseanet_client_save_post_grid_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_grid_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_grid_nonce'], '_phraseanet_client_update_post_grid_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_grid_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_grid',
                sanitize_text_field($_POST['_phraseanet_client_post_grid_field'])
            );
        }
    }




    /**
    * Register meta for download assets ~ downloadList
    * @since 1.2.0
    */
    public function phrasenet_client_register_meta_downloadList()
    {
        register_meta('post', '__phraseanet_client_post_downloadList', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('downloadList'=>array('type'=>'array')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.2.0
     */
    public function _phraseanet_client_add_meta_box_downloadList()
    {
        add_meta_box(
            '_phraseanet_client_post_downloadList_metabox',
            'block_downloadList',
            array($this,'_phraseanet_client_post_downloadList_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.2.0
     */
    public function _phraseanet_client_post_downloadList_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_downloadList_metabox', '_phraseanet_client_update_post_downloadList_nonce');
    }

    /**
     * Save meta value
     * @since 1.2.0
     */
    public function _phraseanet_client_save_post_downloadList_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_downloadList_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_downloadList_nonce'], '_phraseanet_client_update_post_downloadList_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_downloadList_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_downloadList',
                sanitize_text_field($_POST['_phraseanet_client_post_downloadList_field'])
            );
        }
    }




    /**
    * Register meta for permalink assets ~ permalinkList
    * @since 1.2.0
    */
    public function phrasenet_client_register_meta_permalinkList()
    {
        register_meta('post', '__phraseanet_client_post_permalinkList', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('permalinkList'=>array('type'=>'array')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.2.0
     */
    public function _phraseanet_client_add_meta_box_permalinkList()
    {
        add_meta_box(
            '_phraseanet_client_post_permalinkList_metabox',
            'block_permalinkList',
            array($this,'_phraseanet_client_post_permalinkList_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.2.0
     */
    public function _phraseanet_client_post_permalinkList_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_permalinkList_metabox', '_phraseanet_client_update_post_permalinkList_nonce');
    }

    /**
     * Save meta value
     * @since 1.2.0
     */
    public function _phraseanet_client_save_post_permalinkList_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_permalinkList_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_permalinkList_nonce'], '_phraseanet_client_update_post_permalinkList_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_permalinkList_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_permalinkList',
                sanitize_text_field($_POST['_phraseanet_client_post_permalinkList_field'])
            );
        }
    }





    /**
    * Register meta for enableFacets
    * @since 1.2.0
    */
    public function phrasenet_client_register_meta_enableFacets()
    {
        register_meta('post', '__phraseanet_client_post_enableFacets', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('enableFacets'=>array('type'=>'integer','default'=>1)))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.2.0
     */
    public function _phraseanet_client_add_meta_box_enableFacets()
    {
        add_meta_box(
            '_phraseanet_client_post_enableFacets_metabox',
            'block_enableFacets',
            array($this,'_phraseanet_client_post_enableFacets_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.2.0
     */
    public function _phraseanet_client_post_enableFacets_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_enableFacets_metabox', '_phraseanet_client_update_post_enableFacets_nonce');
    }

    /**
     * Save meta value
     * @since 1.2.0
     */
    public function _phraseanet_client_save_post_enableFacets_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_enableFacets_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_enableFacets_nonce'], '_phraseanet_client_update_post_enableFacets_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_enableFacets_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_enableFacets',
                sanitize_text_field($_POST['_phraseanet_client_post_enableFacets_field'])
            );
        }
    }



    /**
    * Register meta for masonry image layout ~ masonryImageLayout
    * @since 1.2.0
    */
    public function phrasenet_client_register_meta_masonryImageLayout()
    {
        register_meta('post', '__phraseanet_client_post_masonryImageLayout', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('masonryImageLayout'=>array('type'=>'string','default'=>'random')))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.2.0
     */
    public function _phraseanet_client_add_meta_box_masonryImageLayout()
    {
        add_meta_box(
            '_phraseanet_client_post_masonryImageLayout_metabox',
            'block_masonryImageLayout',
            array($this,'_phraseanet_client_post_masonryImageLayout_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.2.0
     */
    public function _phraseanet_client_post_masonryImageLayout_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_masonryImageLayout_metabox', '_phraseanet_client_update_post_masonryImageLayout_nonce');
    }

    /**
     * Save meta value
     * @since 1.2.0
     */
    public function _phraseanet_client_save_post_masonryImageLayout_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_masonryImageLayout_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_masonryImageLayout_nonce'], '_phraseanet_client_update_post_masonryImageLayout_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_masonryImageLayout_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_masonryImageLayout',
                sanitize_text_field($_POST['_phraseanet_client_post_masonryImageLayout_field'])
            );
        }
    }



    /**
    * Register meta for masonry image layout ~ masonryImageLayout
    * @since 1.3.8
    */
    public function phrasenet_client_register_meta_asset_details_color_palette()
    {
        register_meta('post', '__phraseanet_client_post_asset_details_color_palette', array(
            'type'=>'object',
            'single'=>true,
            'show_in_rest'=>array(
                'schema'=>array(
                    'type'=>'object',
                    'properties'=>array('color_palette'=>array('type'=>'array','default'=>['#357DFA','#FDFBC1'])))),
            'sanitize_callback'=> true,
            'auth_callback'=> function () {
                return current_user_can('edit_posts');
            },

            ));
    }


    /**
     * Add meta box
     * @since 1.3.8
     */
    public function _phraseanet_client_add_meta_box_asset_details_color_palette()
    {
        add_meta_box(
            '_phraseanet_client_post_asset_details_color_palette_metabox',
            'block_asset_details_color_palette',
            array($this,'_phraseanet_client_post_asset_details_color_palette_metabox_html'),
            'post',
            'normal',
            'default',
            array(
                    '__back_compat_meta_box' => true, //Hide the metabox
                )
        );
    }

    /**
     * @since 1.3.8
     */
    public function _phraseanet_client_post_asset_details_color_palette_metabox_html($post)
    {
        wp_nonce_field('_phraseanet_client_update_post_asset_details_color_palette_metabox', '_phraseanet_client_update_post_asset_details_color_palette_nonce');
    }

    /**
     * Save meta value
     * @since 1.3.8
     */
    public function _phraseanet_client_save_post_asset_details_color_palette_metabox($post_id, $post)
    {
        $edit_cap = get_post_type_object($post->post_type)->cap->edit_post;
        if (!current_user_can($edit_cap, $post_id)) {
            return;
        }
        if (!isset($_POST['_phraseanet_client_update_post_asset_details_color_palette_nonce']) || !wp_verify_nonce($_POST['_phraseanet_client_update_post_asset_details_color_palette_nonce'], '_phraseanet_client_update_post_asset_details_color_palette_metabox')) {
            return;
        }

        if (array_key_exists('_phraseanet_client_post_asset_details_color_palette_field', $_POST)) {
            update_post_meta(
                $post_id,
                '__phraseanet_client_post_asset_details_color_palette',
                sanitize_text_field($_POST['_phraseanet_client_post_asset_details_color_palette_field'])
            );
        }
    }
}
