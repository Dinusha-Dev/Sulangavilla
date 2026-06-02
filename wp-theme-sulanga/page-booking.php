<?php
/**
 * Template Name: Booking Page Template
 *
 * @package Sulanga
 */

get_header();
?>

<!-- HERO -->
<header class="hero">
  <div class="hero-bg"><img src="<?php echo sulanga_upload_url( '2026/06/ChatGPT-Image-Jun-1-2026-10_09_17-PM.png' ); ?>" alt="Sulanga villa at dusk" /></div>
  <div class="container">
    <span class="eyebrow h-anim a1">Book Your Stay</span>
    <h1 class="h-anim a2">Reserve Your<br/>Private Escape</h1>
    <p class="h-anim a3">Book the entire villa for an exclusive stay in Nuwara Eliya. One reservation. Total privacy. Unforgettable memories.</p>
  </div>
</header>

<!-- BOOKING -->
<section class="booking-sec" id="booking-form">
  <div class="container">
    <?php
    if ( shortcode_exists( 'sulanga_booking_form' ) ) {
      echo do_shortcode( '[sulanga_booking_form]' );
    } else {
      while ( have_posts() ) :
        the_post();
        the_content();
      endwhile;
    }
    ?>
  </div>
</section>

<!-- TRUST STRIP -->
<section class="trust">
  <div class="container">
    <div class="trust-grid reveal">
      <div class="trust-item">
        <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2 4 5v6c0 5 3.5 9 8 11 4.5-2 8-6 8-11V5z"/><path d="m9 12 2 2 4-4"/></svg></div>
        <div><b>Secure Reservation</b><small>Your data is protected with industry-leading security.</small></div>
      </div>
      <div class="trust-item">
        <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg></div>
        <div><b>Instant Confirmation Request</b><small>We'll confirm your booking as quickly as possible.</small></div>
      </div>
      <div class="trust-item">
        <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/></svg></div>
        <div><b>Private Villa Stay</b><small>Enjoy complete privacy and exclusivity.</small></div>
      </div>
      <div class="trust-item">
        <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg></div>
        <div><b>Scenic Mountain Location</b><small>Wake up to breathtaking views in Nuwara Eliya.</small></div>
      </div>
    </div>
  </div>
</section>

<!-- AMENITIES -->
<section class="amenities">
  <div class="container">
    <div class="amenities-head reveal">
      <span class="eyebrow">Comfort &amp; Convenience</span>
      <h2>Villa Amenities</h2>
    </div>
    <div class="amenities-2col reveal d1">
      <?php sulanga_render_amenities_carousel(); ?>
      <?php sulanga_render_amenities( 'am-features' ); ?>
    </div>
  </div>
</section>

<!-- HOUSE RULES & CANCELLATION -->
<section class="rules-sec" id="house-rules">
  <div class="container">
    <div class="rules-grid">
      <div class="rules-card reveal" id="house-rules-card">
        <h3>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
          House Rules
        </h3>
        <ul>
          <li>Check-in from <strong>2:00 PM</strong> &middot; Check-out by <strong>12:00 PM</strong>.</li>
          <li>Children of any age are welcome.</li>
          <li>No cots or extra beds are available.</li>
          <li>There is no age requirement for check-in.</li>
        </ul>
      </div>
      <div class="rules-card reveal d1" id="cancellation">
        <h3>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
          Cancellation
        </h3>
        <p>Free cancellation is available up to <strong>two weeks</strong> before your booking date. After that, the reservation becomes non-refundable.</p>
      </div>
    </div>
  </div>
</section>

<?php
get_footer();
