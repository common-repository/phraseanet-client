<?php

//Get the freemius secret key
$freemius_secret_key = getenv('FREEMIUS_SECRET_KEY');

//Set you Freemius API key here
define("FREEMIUS_ID", "9897");
define("FREEMIUS_PUBLIC_KEY", "pk_c94e1f48558a4e7526c793565ac9d");
define("FREEMIUS_IS_PREMIUM", false);
define("FREEMIUS_PREMIUM_SUFFIX", 'pro');
define("FREEMIUS_SECRET_KEY", $freemius_secret_key);
define("FREEMIUS_HAS_PREMIUM_VERSION", false);
define("FREEMIUS_HAS_ADDONS", false);
define("FREEMIUS_HAS_PAID_PLANS", false);




//Load Freemius SDK
 function RegisterFreemius($pwc_fs=null)
 {
     if (! isset($pwc_fs)) {
         // Include Freemius SDK.
         require_once dirname(__FILE__) . '/freemius/start.php';

         $pwc_fs = fs_dynamic_init(array(
            'id'                  => FREEMIUS_ID,
            'slug'                => 'phraseanet-client',
            'type'                => 'plugin',
            'public_key'          => FREEMIUS_PUBLIC_KEY,
            'is_premium'          => FREEMIUS_IS_PREMIUM,
            'premium_suffix'      => FREEMIUS_PREMIUM_SUFFIX,
            // If your plugin is a serviceware, set this option to false.
            'has_premium_version' => FREEMIUS_HAS_PREMIUM_VERSION,
            'has_addons'          => FREEMIUS_HAS_ADDONS,
            'has_paid_plans'      => FREEMIUS_HAS_PAID_PLANS,
            'menu'                => array(
                'slug'           => 'phraseanet-settings',
                'support'        => false,
            ),
            // Set the SDK to work in a sandbox mode (for development & testing).
            // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
            'secret_key'          => FREEMIUS_SECRET_KEY,
        ));
     }

     return $pwc_fs;
 }

 // Init Freemius.
 RegisterFreemius();
 // Signal that SDK was initiated.
 do_action('pwc_fs_loaded');
