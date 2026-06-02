<?php
/**
 * Sulaga Theme Functions and Definitions
 *
 * @package Sulanga
 */

if ( ! function_exists( 'sulanga_setup' ) ) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   */
  function sulanga_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'sulanga', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Register Navigation Menus
    register_nav_menus( array(
      'primary' => esc_html__( 'Primary Menu', 'sulanga' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
    ) );
  }
endif;
add_action( 'after_setup_theme', 'sulanga_setup' );

/**
 * Enqueue scripts and styles.
 */
function sulanga_scripts() {
  // Load Google Fonts
  wp_enqueue_style( 'sulanga-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Great+Vibes&family=Jost:wght@300;400;500;600&display=swap', array(), null );

  // Load Main Theme Stylesheet. Version is the file's modified time so browsers
  // automatically fetch the latest CSS whenever it changes (no stale cache).
  $css_path = get_stylesheet_directory() . '/style.css';
  wp_enqueue_style( 'sulanga-style', get_stylesheet_uri(), array(), file_exists( $css_path ) ? filemtime( $css_path ) : '1.0.0' );

  // Load Main Theme JS (same file-based cache busting).
  $js_path = get_template_directory() . '/assets/js/theme.js';
  wp_enqueue_script( 'sulanga-theme-js', get_template_directory_uri() . '/assets/js/theme.js', array(), file_exists( $js_path ) ? filemtime( $js_path ) : '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'sulanga_scripts' );

/**
 * Auto-provision the site on theme activation.
 *
 * The page templates in this theme (Homepage, About, Gallery, Booking, Contact)
 * are "Template Name" templates that only render once a Page exists with the
 * matching slug AND has the template assigned. Without this, a freshly activated
 * theme shows the blog index on the front page and 404s on /about-us/, /booking/
 * etc. — and the booking form (a shortcode inside the Booking template) never
 * appears. This runs once on activation to create those pages, assign the right
 * template to each, and set a static front page so everything works immediately.
 *
 * It is idempotent: existing pages (matched by slug) are reused, never duplicated.
 */
function sulanga_provision_pages() {
  // slug => array( Title, template file )
  $pages = array(
    'home'       => array( 'Home',       'page-home.php' ),
    'about-us'   => array( 'About Us',   'page-about.php' ),
    'gallery'    => array( 'Gallery',    'page-gallery.php' ),
    'booking'    => array( 'Booking',    'page-booking.php' ),
    'contact-us' => array( 'Contact Us', 'page-contact.php' ),
  );

  $ids = array();

  foreach ( $pages as $slug => $info ) {
    list( $title, $template ) = $info;

    $existing = get_page_by_path( $slug );
    if ( $existing instanceof WP_Post ) {
      $ids[ $slug ] = $existing->ID;
      update_post_meta( $existing->ID, '_wp_page_template', $template );
      continue;
    }

    $page_id = wp_insert_post( array(
      'post_title'   => $title,
      'post_name'    => $slug,
      'post_status'  => 'publish',
      'post_type'    => 'page',
      'post_content' => '',
    ) );

    if ( $page_id && ! is_wp_error( $page_id ) ) {
      $ids[ $slug ] = $page_id;
      update_post_meta( $page_id, '_wp_page_template', $template );
    }
  }

  // Use the Home page as a static front page.
  if ( ! empty( $ids['home'] ) ) {
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $ids['home'] );
  }

  // Mark as provisioned so the admin_init safety net below doesn't repeat it.
  update_option( 'sulanga_provisioned', '1' );
}
add_action( 'after_switch_theme', 'sulanga_provision_pages' );

/**
 * Safety net: after_switch_theme only fires on (re)activation. For sites where
 * the theme is already active when these files are updated, run provisioning
 * once on the next admin load so the pages/front page get set up too.
 */
function sulanga_maybe_provision() {
  if ( '1' !== get_option( 'sulanga_provisioned' ) ) {
    sulanga_provision_pages();
  }
}
add_action( 'admin_init', 'sulanga_maybe_provision' );

/**
 * Whether the given menu slug is the page currently being viewed.
 * Used to mark the correct item active in the fallback navigation.
 *
 * @param string $slug 'home' or a page slug (about-us, booking, gallery, contact-us).
 * @return bool
 */
function sulanga_is_current( $slug ) {
  if ( 'home' === $slug ) {
    return is_front_page();
  }
  return is_page( $slug );
}

/**
 * Echo ' active' when $slug is the current page (for fallback nav links).
 */
function sulanga_active_class( $slug ) {
  echo sulanga_is_current( $slug ) ? ' active' : '';
}

/**
 * Return image attachments from the Media Library for the gallery.
 *
 * @param int $limit Number of images (-1 for all).
 * @return WP_Post[]
 */
function sulanga_get_gallery_images( $limit = -1 ) {
  return get_posts( array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'posts_per_page' => $limit,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ) );
}

/**
 * The villa's amenities, each with a relevant icon (label => SVG inner markup).
 *
 * @return array[]
 */
function sulanga_amenities_list() {
  return array(
    array( 'label' => 'Cloth Rack',         'icon' => '<path d="M12 6a2 2 0 1 1 2 2"/><path d="M12 8 3.6 14.2A1 1 0 0 0 4.2 16h15.6a1 1 0 0 0 .6-1.8L12 8z"/>' ),
    array( 'label' => 'Satellite TV',        'icon' => '<rect x="3" y="8" width="18" height="12" rx="2"/><path d="m7 8 5-5 5 5"/><path d="M9 23h6"/>' ),
    array( 'label' => 'Free WiFi',           'icon' => '<path d="M5 12.5a10 10 0 0 1 14 0"/><path d="M8.5 16a5 5 0 0 1 7 0"/><path d="M12 19h.01"/>' ),
    array( 'label' => 'Private Bathroom',    'icon' => '<path d="M4 12V6a2 2 0 0 1 4 0"/><path d="M2 12h20v3a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5z"/><path d="M7 20l-1 2M18 20l1 2"/>' ),
    array( 'label' => 'Electric Kettle',     'icon' => '<path d="M18 8h1a3 3 0 0 1 0 6h-1"/><path d="M4 8h14v5a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5z"/><path d="M7 2v2M11 2v2"/>' ),
    array( 'label' => 'Tea/Coffee Maker',    'icon' => '<path d="M18 8h1a3 3 0 0 1 0 6h-1"/><path d="M3 8h15v6a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5z"/><path d="M6 1c0 1-1 1-1 2s1 1 1 2M10 1c0 1-1 1-1 2s1 1 1 2"/>' ),
    array( 'label' => 'Karaoke',             'icon' => '<rect x="9" y="2" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3M8 21h8"/>' ),
    array( 'label' => 'Towels',              'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 5v14"/>' ),
    array( 'label' => 'Toilet Paper',        'icon' => '<ellipse cx="9" cy="8" rx="6" ry="3.5"/><path d="M3 8v7c0 1.9 2.7 3.5 6 3.5s6-1.6 6-3.5V8"/><path d="M15 11h4v8"/>' ),
    array( 'label' => 'Private Entrance',    'icon' => '<path d="M3 21h18"/><path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M14 12h.01"/>' ),
    array( 'label' => 'Ironing Facilities',  'icon' => '<path d="M3 14a8 8 0 0 1 8-8h7a3 3 0 0 1 3 3v5z"/><path d="M3 14v3h18"/>' ),
  );
}

/**
 * Output the amenities as icon items inside the given wrapper class.
 */
function sulanga_render_amenities( $wrapper_class = 'amenities-grid' ) {
  echo '<div class="' . esc_attr( $wrapper_class ) . '">';
  foreach ( sulanga_amenities_list() as $amenity ) {
    echo '<div class="amenity"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $amenity['icon'] . '</svg><span>' . esc_html( $amenity['label'] ) . '</span></div>';
  }
  echo '</div>';
}

/**
 * Output a single-image-per-view carousel of Media Library photos.
 */
function sulanga_render_amenities_carousel() {
  $images = sulanga_get_gallery_images( 12 );
  echo '<div class="am-carousel">';
  if ( ! empty( $images ) ) {
    echo '<div class="am-track" id="amTrack">';
    foreach ( $images as $img ) {
      echo '<div class="am-slide">' . wp_get_attachment_image( $img->ID, 'large', false, array( 'alt' => esc_attr( $img->post_title ), 'loading' => 'lazy' ) ) . '</div>';
    }
    echo '</div>';
    echo '<button type="button" class="am-arrow am-prev" id="amPrev" aria-label="Previous image">&#8249;</button>';
    echo '<button type="button" class="am-arrow am-next" id="amNext" aria-label="Next image">&#8250;</button>';
    echo '<div class="am-dots" id="amDots"></div>';
  } else {
    echo '<div class="am-track"><div class="am-slide"><img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=900&q=80" alt="Sulaga villa" /></div></div>';
  }
  echo '</div>';
}

/**
 * Custom walker to support the clean navigation styling structure.
 */
class Sulanga_Nav_Walker extends Walker_Nav_Menu {
  function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
    
    // Add custom class if page is active
    $active_class = in_array( 'current-menu-item', $classes ) ? ' active' : '';
    
    $output .= '<li>';
    
    $attributes = '';
    if ( ! empty( $item->url ) ) {
      $attributes .= ' href="' . esc_url( $item->url ) . '"';
    }
    
    $item_output = $args->before;
    $item_output .= '<a class="' . esc_attr( $active_class ) . '"' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;
    
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}
