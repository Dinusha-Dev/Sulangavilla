<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Sulanga
 */

get_header();
?>

<main id="primary" class="site-main" style="padding-top: 130px; padding-bottom: 80px;">
  <div class="container">
    <?php
    if ( have_posts() ) :
      while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
          </header>
          
          <div class="entry-content">
            <?php the_content(); ?>
          </div>
        </article>
        <?php
      endwhile;
    else :
      ?>
      <section class="no-results not-found">
        <header class="page-header">
          <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'sulanga' ); ?></h1>
        </header>
        <div class="page-content">
          <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'sulanga' ); ?></p>
        </div>
      </section>
      <?php
    endif;
    ?>
  </div>
</main>

<?php
get_footer();
