<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.alchemy.fr
 * @since      1.0.0
 *
 * @package    Phraseanet
 * @subpackage Phraseanet/public/partials
 */
$error = '';
 if (isset($_POST['login'])) {
     if (!empty($_POST['username']) and !empty($_POST['password'])) {
         $username = sanitize_text_field($_POST['username']);
         $password = sanitize_text_field($_POST['password']);

         $r =  $this->userPhraseanetAuth($username, $password);

         if (!empty($r->token)) {
             $_SESSION['token'] = sanitize_text_field($r->token); //Save token into session to use it later
             $_SESSION['username'] = sanitize_text_field($username);
             /** @note header may give issue so instead of php header we are using js */
             $page = esc_url($_SERVER['REQUEST_URI']);
             echo "<script>window.location.href='$page'</script>";
         } else {
             $error =  __('Invalid login creadentials!', 'phraseanet');
         }
     } else {
         $error = __('Please submit valid Username and password', 'phraseanet');
     }
 }



?>

<div class="background-gradient col-md-12 col-sm-12 col-lg-12" style="color:white;    width: 90%;
    max-width: 60%;
    height: 570px;
    max-height: 60%;" id="auth_page">


<p style="display: inline-flex;padding-top: 7px;"><img src="<?php echo plugin_dir_url(__DIR__).'/images/phrasea-logo.png' ?>" style="width: 66px;"> <span style="padding-top: 20px;"><?php echo esc_attr(_e('Phraseanet Login', 'phraseanet')); ?></span></p>

<?php if (!empty($error)) { ?> <p style="text-align:center" class="alert alert-danger col-md-6 col-sm-6 col-lg-6 offset-lg-3 offset-md-3 offset-ms-3"><?php echo esc_attr($error); ?></p> <?php } ?>

<form class="col-md-6 col-sm-6 col-lg-6 offset-lg-3 offset-md-3 offset-ms-3" method="post" style="padding-top: 73px;">
  <div class="mb-3">
    <label for="Username" class="form-label"><?php echo esc_attr(_e('Username', 'phraseanet')); ?></label>
    <input type="text" class="form-control" name="username" id="Username" aria-describedby="UsernameHelp" style="border-color: #d3b9d5;border-bottom: 3px solid #00000012;">
    
  </div>
  <div class="mb-3">
    <label for="Password" class="form-label"><?php echo esc_attr(_e('Password', 'phraseanet')); ?></label>
    <input type="password" name="password" class="form-control" id="Password" style="border-color: #d3b9d5;border-bottom: 3px solid #00000012;">
  </div>

  <div  class="col-md-6 col-sm-6 col-lg-6 offset-lg-5 offset-md-5 offset-ms-5">
  <button type="submit" name="login" class="btn btn-outline-secondary btn-lg"><?php echo esc_attr(_e('Login', 'phraseanet')); ?></button>

  </div>

</form>

</div>