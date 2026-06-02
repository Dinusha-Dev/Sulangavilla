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
    <div class="pl-name">Sulaga</div>
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
          </svg> For Reservations: +94 77 248 7000</span>
      </div>
      <div class="topbar-right">
        <span class="ota-label">Also on</span>
        <a class="ota" href="https://www.booking.com/hotel/lk/sulanga.en-gb.html?aid=311984;label=sulanga-JZLQ1XtV*0pg8zWjTYuFMQS675410860977:pl:ta:p1:p2:ac:ap:neg:fi:tikwd-2320764870709:lp9231269:li:dec:dm:ppccp=UmFuZG9tSVYkc2RlIyh9YbSsBl3MCvHsD8UKUHIRFxY;ws=&gad_source=1&gad_campaignid=8471304875&gbraid=0AAAAAD_Ls1IqVLg9A_lMQH19f4T_aLa21&gclid=Cj0KCQjw2_TQBhCnARIsAF3-XhwVI1CAdE7yz2Ak_c4qeXaMCojT3cRoHZv0fn6pRy6CYx0K2t2i--QaAtv0EALw_wcB" target="_blank" rel="noopener noreferrer" title="Book on Booking.com" aria-label="Book on Booking.com"><img src="https://www.google.com/s2/favicons?domain=booking.com&sz=128" alt="Booking.com" width="18" height="18" loading="lazy" /></a>
        <a class="ota" href="https://www.tripadvisor.com/Hotel_Review-g608524-d33414792-Reviews-Sulaga_Luxury_Chalets-Nuwara_Eliya_Central_Province.html" target="_blank" rel="noopener noreferrer" title="Tripadvisor reviews" aria-label="Tripadvisor reviews"><img src="https://www.google.com/s2/favicons?domain=tripadvisor.com&sz=128" alt="Tripadvisor" width="18" height="18" loading="lazy" /></a>
        <a class="ota" href="https://en.planetofhotels.com/sri-lanka/nuwara-eliya/sulanga" target="_blank" rel="noopener noreferrer" title="Planet of Hotels" aria-label="Planet of Hotels"><img src="https://www.google.com/s2/favicons?domain=planetofhotels.com&sz=128" alt="Planet of Hotels" width="18" height="18" loading="lazy" /></a>
        <a class="ota" href="https://www.airbnb.com/rooms/882454172125576794" target="_blank" rel="noopener noreferrer" title="Book on Airbnb" aria-label="Book on Airbnb"><img src="https://www.google.com/s2/favicons?domain=airbnb.com&sz=128" alt="Airbnb" width="18" height="18" loading="lazy" /></a>
        <a class="ota" href="https://www.agoda.com/en-ie/sulanga/hotel/nuwara-eliya-lk.html" target="_blank" rel="noopener noreferrer" title="Book on Agoda" aria-label="Book on Agoda"><img src="https://www.google.com/s2/favicons?domain=agoda.com&sz=128" alt="Agoda" width="18" height="18" loading="lazy" /></a>
        <a class="ota" href="https://www.expedia.com/Nuwara-Eliya-Hotels-Welcome-To-Sulaga-A-Hidden-Gem-Nestled-In-The-Hills-Of-Nuwara-Eliya.h98275948.Hotel-Information" target="_blank" rel="noopener noreferrer" title="Book on Expedia" aria-label="Book on Expedia"><img src="https://www.google.com/s2/favicons?domain=expedia.com&sz=128" alt="Expedia" width="18" height="18" loading="lazy" /></a>
      </div>
    </div>
  </div>

  <!-- NAV -->
  <nav class="nav" id="nav">
    <div class="container">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo"><span class="ln1">Sulaga</span><span class="ln2">Luxury Chalets</span></a>
      
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

      <div class="nav-actions">
        <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="btn btn-outline btn-nav">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />
          </svg>
          <span>Contact Us</span>
        </a>
        <a href="<?php echo esc_url( home_url( '/booking/' ) ); ?>" class="btn btn-green btn-nav">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4M8 2v4M3 10h18" />
          </svg>
          <span>Book Now</span>
        </a>
      </div>
      <button class="nav-toggle" id="navToggle" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>
  </nav>
