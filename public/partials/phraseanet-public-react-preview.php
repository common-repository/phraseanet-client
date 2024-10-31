<?php

//Admin block iframe preview

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}
//Post id is required
$post =  sanitize_text_field(strip_tags($_GET['post']));



?>
<!-- Hides the page content -->
<script> jQuery( document ).ready(function() { jQuery("div#page").hide(); return( false ); }); </script>

<body>
    <div id="page_id" data-pageid="<?php echo  esc_attr($post); ?>"></div>
    <!-- Load react dom -->
    <div id="react-render-dom" class="phraseanet-client-class">
        <div>
        </div>

    </div>

</body>
