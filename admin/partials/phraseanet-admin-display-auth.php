<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.alchemy.fr
 * @since      1.0.0
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/admin/partials
 */


// <!-- This file should primarily consist of HTML with a little bit of PHP. -->

?>



<div class="phraseanet-client-class">

  <?php require_once 'logo.php'; ?>



  <form class="col-md-12 col-lg-12 col-sm-12" method="post" action="options.php">


    <?php
settings_fields('phraseanetClientSettings');
do_settings_sections('phraseanetClientSettings');

$auth_type =  esc_attr(get_option('phraseanet_auth_type'));

//Get url of the site
$blog_url = esc_url(get_site_url());

$url = esc_url(get_option('phraseanet_callback_url'));

if (empty($url)) {
    $blog_url .= '/wp-admin/admin.php?page=phraseanet-settings';
} else {
    $blog_url = $url;
}

?>



    <div class="col-sm-8 col-md-8 col-lg-8">
      <div class="row">
        <div class="col-sm-6" style="display: none;">
          <label for="auth_type"
            class="form-label"><b><?php echo esc_attr(_e('Auth Phraseanet with token', 'phraseanet')); ?></b>
            <input type="radio" class="auth_type" name="phraseanet_auth_type" value="token" <?php if (empty($auth_type) or $auth_type=='token') {
    echo esc_attr("checked");
} ?>>
          </label>

        </div>


      </div>
    </div>


    <div class="col-sm-6 col-md-6 col-lg-6">

      <div class="mb-3">
        <label for="url" class="form-label"><b><?php echo esc_attr(_e('Phraseanet URL', 'phraseanet')); ?></b></label>
        <input type="text" name="phraseanet_url" class="form-control" id="url" aria-describedby="urlHelp"
          placeholder="<?php echo esc_attr(_e('Enter your phraseanet url', 'phraseanet')); ?>"
          value="<?php echo esc_url(get_option('phraseanet_url')); ?>" required="required">
        <div id="urlHelp" class="form-text">eg. https://demo.alchemyasp.com/</div>
      </div>

      <div class="mb-3 ">
        <label for="clientId" class="form-label"><b><?php echo esc_attr(_e('Client id', 'phraseanet')); ?></b></label>
        <input type="text" name="phraseanet_client_id" class="form-control" id="clientId"
          aria-describedby="clientIdHelp"
          placeholder="<?php echo esc_attr(_e('Enter your phraseanet client id', 'phraseanet')); ?>"
          value="<?php echo esc_attr(get_option('phraseanet_client_id')); ?>" required="required">
        <div id="clientIdHelp" class="form-text"> </div>
      </div>

      <div class="mb-3 ">
        <label for="clientSecret"
          class="form-label"><b><?php echo esc_attr(_e('Client Secret', 'phraseanet')); ?></b></label>
        <input type="text" name="phraseanet_client_secret" class="form-control" id="clientSecret"
          aria-describedby="clientSecretHelp"
          placeholder="<?php echo esc_attr(_e('Enter your phraseanet client secret', 'phraseanet')); ?>"
          value="<?php echo esc_attr(get_option('phraseanet_client_secret')); ?>" required="required">
        <div id="clientSecretHelp" class="form-text"> </div>
      </div>

      <div class="mb-3 token">
        <label for="phras_token" class="form-label"><b><?php echo esc_attr(_e('Token', 'phraseanet')); ?></b></label>
        <input type="text" name="phraseanet_token" class="form-control" id="phras_token"
          aria-describedby="phras_tokenHelp" placeholder="<?php echo esc_attr(_e('Enter your Token', 'phraseanet')); ?>"
          value="<?php echo esc_attr(get_option('phraseanet_token')); ?>" required="required">
        <div id="phras_tokenHelp" class="form-text">
          <?php echo esc_attr(_e('Generate token in phraseanet and add it here', 'phraseanet')); ?></div>
      </div>

      <button type="submit" class="btn btn-primary"><?php echo esc_attr(_e('Save', 'phraseanet')); ?></button>

    </div>
  </form>
</div>