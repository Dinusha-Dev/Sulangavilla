<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package Sulanga
 */

get_header();
?>

<main id="primary" class="site-main">
  <?php
  while ( have_posts() ) :
    the_post();

    // Outputs Elementor built layouts or default page content
    the_content();

  endwhile; // End of the loop.
  ?>
</main>

<?php
get_footer();
