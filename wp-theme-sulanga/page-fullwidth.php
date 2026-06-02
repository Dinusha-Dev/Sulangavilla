<?php
/**
 * Template Name: Full Width (Elementor)
 *
 * A no-container, full-bleed page template. Use this for pages built with
 * Elementor (or the block editor) so sections span the full viewport width and
 * all widgets render. The page's content is output via the_content(), which is
 * what Elementor hooks into — unlike the fixed "Homepage / About / …" templates
 * in this theme, which output hardcoded markup and ignore Elementor.
 *
 * @package Sulanga
 */

get_header();
?>

<main id="primary" class="site-main site-main--fullwidth">
  <?php
  while ( have_posts() ) :
    the_post();
    the_content();
  endwhile;
  ?>
</main>

<?php
get_footer();
