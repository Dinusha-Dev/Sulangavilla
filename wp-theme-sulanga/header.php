<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script>
    /* Enable scroll-reveal animations only when JS is available, and never let
       content stay hidden: if theme.js hasn't initialised shortly after load,
       drop the flag so every .reveal section becomes visible. */
    ( function () {
      var d = document.documentElement;
      d.className += ' reveal-on';
      window.addEventListener( 'load', function () {
        setTimeout( function () {
          if ( ! window.__sulangaRevealReady ) {
            d.className = d.className.replace( ' reveal-on', '' );
          }
        }, 1000 );
      } );
    } )();
  </script>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <?php wp_body_open(); ?>

  <!-- PRELOADER -->
  <div id="preload">
    <div class="pl-name"><?php bloginfo( 'name' ); ?></div>
    <div class="pl-line"></div>
  </div>

  <!-- TOP BAR -->
  <div class="topbar">
    <div class="container">
      <div class="topbar-left">
        <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-10 5L2 7" />
          </svg> rtissera@hotmail.com</span>
        <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path
              d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />
          </svg> For Reservations: 076 0730 139</span>
        <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path
              d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />
          </svg> For Operations: 077 2487 000</span>
      </div>
    </div>
  </div>

  <!-- NAV -->
  <nav class="nav" id="nav">
    <div class="container">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo"><span class="ln1">Sulanga</span><span class="ln2">Luxury Chalets</span></a>
      
      <?php
      if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'nav-menu',
          'menu_id'        => 'navMenu',
          'walker'         => new Sulanga_Nav_Walker(),
          'fallback_cb'    => false,
        ) );
      } else {
        // Fallback static menu for instant visual readiness
        ?>
        <ul class="nav-menu" id="navMenu">
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php sulanga_active_class( 'home' ); ?>">Home</a></li>
          <li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>" class="<?php sulanga_active_class( 'about-us' ); ?>">About Us</a></li>
          <li><a href="<?php echo esc_url( home_url( '/booking/' ) ); ?>" class="<?php sulanga_active_class( 'booking' ); ?>">Booking</a></li>
          <li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="<?php sulanga_active_class( 'gallery' ); ?>">Gallery</a></li>
          <li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="<?php sulanga_active_class( 'contact-us' ); ?>">Contact Us</a></li>
        </ul>
        <?php
      }
      ?>

      <a href="<?php echo esc_url( home_url( '/booking/' ) ); ?>" class="btn btn-green btn-nav">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" />
          <path d="M16 2v4M8 2v4M3 10h18" />
        </svg>
        <span>Book Now</span>
      </a>
      <button class="nav-toggle" id="navToggle" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>
  </nav>
