<?php
/**
 * The front page template.
 *
 * WordPress uses this for the site's front page automatically (it takes
 * precedence over index.php and the page template), so the Homepage design
 * renders out of the box without any manual front-page configuration.
 * It simply reuses the Homepage Template layout to avoid duplicating markup.
 *
 * @package Sulanga
 */

// Render the same layout as the "Homepage Template".
include locate_template( 'page-home.php' );
