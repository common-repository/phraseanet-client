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
settings_fields('phraseanetClientOtherSettings');
do_settings_sections('phraseanetClientOtherSettings');

?>

  <div class="col-sm-6 col-md-6 col-lg-6">

    <p class="h3"><?php echo esc_attr(_e('Phraseanet setting', 'phraseanet')); ?></p></p>
    <hr>

    <div class="mb-3">
      <label for="perpage" class="form-label"><?php echo esc_attr(_e('Set the number of records loads on the page per API call', 'phraseanet')); ?></label>
      <input type="number" name="phraseanet_per_page_records" class="form-control" id="perpage"
        aria-describedby="perpageHelp" placeholder="<?php echo esc_attr(_e('Enter Number Eg. 50', 'phraseanet')); ?>"
        value="<?php echo esc_attr(get_option('phraseanet_per_page_records')); ?>" min="50">
      <div id="perpageHelp" class="form-text"><?php echo esc_attr(_e('Default is 100 records. Note it requires minimum 50 records on the page. ', 'phraseanet')); ?></div>
    </div>

    <br><br>



    <p class="h3"><?php echo esc_attr(_e('UI', 'phraseanet')); ?></p>
    <hr>
    <div class="mb-3">
      <label for="phraseanet_modal_bc" class="form-label"><?php echo esc_attr(_e('Modal backgorund color', 'phraseanet')); ?></label>
      <input type="text" name="phraseanet_modal_bc" class="form-control" id="phraseanet_modal_bc"
        aria-describedby="phraseanet_modal_bc" placeholder="<?php echo esc_attr(_e('Color', 'phraseanet')); ?>"
        value="<?php echo esc_attr(get_option('phraseanet_modal_bc')); ?>">
      <div id="phraseanet_modal_bc" class="form-text">eg. <b>rgba(0, 0, 0, 0.5) </b> <?php echo esc_attr(_e('to make it transparent or any color value like', 'phraseanet')); ?> <b><?php echo esc_attr(_e('Blue', 'phraseanet')); ?></b> <?php echo esc_attr(_e('to make solid background', 'phraseanet')); ?> </div>
    </div>

    <div class="mb-3">
      <label for="phraseanet_modal_fc" class="form-label"><?php echo esc_attr(_e('Modal font color', 'phraseanet')); ?> </label>
      <input type="text" name="phraseanet_modal_fc" class="form-control" id="phraseanet_modal_fc"
        aria-describedby="phraseanet_modal_fc" placeholder="<?php echo esc_attr(_e('Color', 'phraseanet')); ?>"
        value="<?php echo esc_attr(get_option('phraseanet_modal_fc')); ?>">
      <div id="phraseanet_modal_fc" class="form-text">eg. <b><?php echo esc_attr(_e('White', 'phraseanet')); ?></b>,<b><?php echo esc_attr(_e('Red', 'phraseanet')); ?></b>, <?php echo esc_attr(_e('or any', 'phraseanet')); ?> <b>rgba</b> </div>
    </div>

    <button type="submit" class="btn btn-primary"><?php echo esc_attr(_e('Save', 'phraseanet')); ?></button>



  </div>


</form>

<br/>
<h2>Git Logs</h2>
<?php
$path =  plugin_dir_path(__DIR__);
$logs = file_get_contents($path.'../version.txt');
?>

<textarea style="margin-top: 0px;margin-bottom: 0px;height: 500px;width: 100%;">

<?php echo esc_attr($logs); ?>

</textarea>

</div>