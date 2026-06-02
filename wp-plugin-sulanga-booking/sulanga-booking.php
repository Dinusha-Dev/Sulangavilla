<?php
/**
 * Plugin Name: Sulanga Booking & Reservation
 * Plugin URI: https://sulanga.com/booking-plugin
 * Description: Custom booking management, database logs, and checkouts for Sulaga Luxury Chalets.
 * Version: 1.0.0
 * Author: Antigravity
 * Author URI: https://google.com
 * License: GPL2
 * Text Domain: sulanga-booking
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

// Define Constants
define( 'SULANGA_BOOKING_VERSION', '1.1.0' );
define( 'SULANGA_BOOKING_PATH', plugin_dir_path( __FILE__ ) );
define( 'SULANGA_BOOKING_URL', plugin_dir_url( __FILE__ ) );

/**
 * Register Custom Post Type for Bookings
 */
function sulanga_booking_register_cpt() {
  $labels = array(
    'name'                  => _x( 'Bookings', 'Post Type General Name', 'sulanga-booking' ),
    'singular_name'         => _x( 'Booking', 'Post Type Singular Name', 'sulanga-booking' ),
    'menu_name'             => __( 'Sulanga Bookings', 'sulanga-booking' ),
    'name_admin_bar'        => __( 'Booking', 'sulanga-booking' ),
    'archives'              => __( 'Booking Archives', 'sulanga-booking' ),
    'attributes'            => __( 'Booking Attributes', 'sulanga-booking' ),
    'parent_item_colon'     => __( 'Parent Booking:', 'sulanga-booking' ),
    'all_items'             => __( 'All Bookings', 'sulanga-booking' ),
    'add_new_item'          => __( 'Add New Booking', 'sulanga-booking' ),
    'add_new'               => __( 'Add New', 'sulanga-booking' ),
    'new_item'              => __( 'New Booking', 'sulanga-booking' ),
    'edit_item'             => __( 'Edit Booking', 'sulanga-booking' ),
    'update_item'           => __( 'Update Booking', 'sulanga-booking' ),
    'view_item'             => __( 'View Booking', 'sulanga-booking' ),
    'view_items'            => __( 'View Bookings', 'sulanga-booking' ),
    'search_items'          => __( 'Search Booking', 'sulanga-booking' ),
    'not_found'             => __( 'No bookings found', 'sulanga-booking' ),
    'not_found_in_trash'    => __( 'No bookings found in Trash', 'sulanga-booking' ),
    'featured_image'        => __( 'Featured Image', 'sulanga-booking' ),
    'set_featured_image'    => __( 'Set featured image', 'sulanga-booking' ),
    'remove_featured_image' => __( 'Remove featured image', 'sulanga-booking' ),
    'use_featured_image'    => __( 'Use as featured image', 'sulanga-booking' ),
    'insert_into_item'      => __( 'Insert into booking', 'sulanga-booking' ),
    'uploaded_to_this_item' => __( 'Uploaded to this booking', 'sulanga-booking' ),
    'items_list'            => __( 'Bookings list', 'sulanga-booking' ),
    'items_list_navigation' => __( 'Bookings list navigation', 'sulanga-booking' ),
    'filter_items_list'     => __( 'Filter bookings list', 'sulanga-booking' ),
  );
  
  $args = array(
    'label'                 => __( 'Booking', 'sulanga-booking' ),
    'description'           => __( 'Reservation logs for Sulaga Luxury Chalets', 'sulanga-booking' ),
    'labels'                => $labels,
    'supports'              => array( 'title' ),
    'hierarchical'          => false,
    'public'                => false, // Keep it internal/admin-only
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 26,
    'menu_icon'             => 'dashicons-calendar-alt',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => false,
    'can_export'            => true,
    'has_archive'           => false,
    'exclude_from_search'   => true,
    'publicly_queryable'    => false,
    'capability_type'       => 'post',
  );
  
  register_post_type( 'sulanga_booking', $args );
}
add_action( 'init', 'sulanga_booking_register_cpt', 0 );

/**
 * Add Booking Metadata Boxes in Admin
 */
function sulanga_booking_add_meta_boxes() {
  add_meta_box(
    'sulanga_booking_details',
    __( 'Booking Information Details', 'sulanga-booking' ),
    'sulanga_booking_render_meta_box',
    'sulanga_booking',
    'normal',
    'high'
  );
}
add_action( 'add_meta_boxes', 'sulanga_booking_add_meta_boxes' );

function sulanga_booking_render_meta_box( $post ) {
  // Retrieve saved values
  $checkin   = get_post_meta( $post->ID, '_checkin', true );
  $checkout  = get_post_meta( $post->ID, '_checkout', true );
  $guests    = get_post_meta( $post->ID, '_guests', true );
  $email     = get_post_meta( $post->ID, '_email', true );
  $phone     = get_post_meta( $post->ID, '_phone', true );
  $requests  = get_post_meta( $post->ID, '_requests', true );
  $status    = get_post_meta( $post->ID, '_status', true );
  $total     = get_post_meta( $post->ID, '_total_estimate', true );
  $currency  = get_post_meta( $post->ID, '_currency', true );
  if ( empty( $currency ) ) {
    $currency = 'LKR';
  }
  $nights    = get_post_meta( $post->ID, '_nights', true );

  // Nonce field for verification
  wp_nonce_field( 'sulanga_booking_save_meta', 'sulanga_booking_meta_nonce' );
  
  if ( empty( $status ) ) {
    $status = 'Pending';
  }
  
  ?>
  <style>
    .booking-meta-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .booking-meta-table th, .booking-meta-table td { padding: 10px; text-align: left; vertical-align: top; border-bottom: 1px solid #eee; }
    .booking-meta-table th { width: 180px; font-weight: bold; }
    .booking-meta-table input, .booking-meta-table select, .booking-meta-table textarea { width: 100%; max-width: 400px; }
  </style>
  
  <table class="booking-meta-table">
    <tr>
      <th><label for="booking_status"><?php _e( 'Reservation Status', 'sulanga-booking' ); ?></label></th>
      <td>
        <select name="booking_status" id="booking_status">
          <option value="Pending" <?php selected( $status, 'Pending' ); ?>>Pending Confirmation</option>
          <option value="Confirmed" <?php selected( $status, 'Confirmed' ); ?>>Confirmed</option>
          <option value="Cancelled" <?php selected( $status, 'Cancelled' ); ?>>Cancelled</option>
        </select>
      </td>
    </tr>
    <tr>
      <th><label for="booking_checkin"><?php _e( 'Check-in Date', 'sulanga-booking' ); ?></label></th>
      <td><input type="date" name="booking_checkin" id="booking_checkin" value="<?php echo esc_attr( $checkin ); ?>" required /></td>
    </tr>
    <tr>
      <th><label for="booking_checkout"><?php _e( 'Check-out Date', 'sulanga-booking' ); ?></label></th>
      <td><input type="date" name="booking_checkout" id="booking_checkout" value="<?php echo esc_attr( $checkout ); ?>" required /></td>
    </tr>
    <tr>
      <th><label for="booking_nights"><?php _e( 'Number of Nights', 'sulanga-booking' ); ?></label></th>
      <td><input type="number" name="booking_nights" id="booking_nights" value="<?php echo esc_attr( $nights ); ?>" min="1" /></td>
    </tr>
    <tr>
      <th><label for="booking_guests"><?php _e( 'Guests Count', 'sulanga-booking' ); ?></label></th>
      <td><input type="text" name="booking_guests" id="booking_guests" value="<?php echo esc_attr( $guests ); ?>" /></td>
    </tr>
    <tr>
      <th><label for="booking_email"><?php _e( 'Guest Email Address', 'sulanga-booking' ); ?></label></th>
      <td><input type="email" name="booking_email" id="booking_email" value="<?php echo esc_attr( $email ); ?>" required /></td>
    </tr>
    <tr>
      <th><label for="booking_phone"><?php _e( 'Guest Phone Number', 'sulanga-booking' ); ?></label></th>
      <td><input type="text" name="booking_phone" id="booking_phone" value="<?php echo esc_attr( $phone ); ?>" /></td>
    </tr>
    <tr>
      <th><label for="booking_total"><?php _e( 'Total Estimate Cost', 'sulanga-booking' ); ?></label></th>
      <td><input type="text" name="booking_total" id="booking_total" value="<?php echo esc_attr( $total ); ?>" /> (<?php echo esc_html( $currency ); ?>)</td>
    </tr>
    <tr>
      <th><label for="booking_requests"><?php _e( 'Special Requests', 'sulanga-booking' ); ?></label></th>
      <td><textarea name="booking_requests" id="booking_requests" rows="4"><?php echo esc_textarea( $requests ); ?></textarea></td>
    </tr>
  </table>
  <?php
}

/**
 * Save Booking Metadata Box Values
 */
function sulanga_booking_save_meta_box( $post_id ) {
  // Check nonce
  if ( ! isset( $_POST['sulanga_booking_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sulanga_booking_meta_nonce'], 'sulanga_booking_save_meta' ) ) {
    return;
  }

  // Check autosave
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // Check permissions
  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
  }

  // Save Meta Fields
  $fields = array(
    'booking_checkin'  => '_checkin',
    'booking_checkout' => '_checkout',
    'booking_guests'   => '_guests',
    'booking_email'    => '_email',
    'booking_phone'    => '_phone',
    'booking_requests' => '_requests',
    'booking_status'   => '_status',
    'booking_total'    => '_total_estimate',
    'booking_nights'   => '_nights',
  );

  foreach ( $fields as $key => $meta_key ) {
    if ( isset( $_POST[ $key ] ) ) {
      update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
    }
  }
}
add_action( 'save_post_sulanga_booking', 'sulanga_booking_save_meta_box' );

/**
 * Enqueue scripts for the booking form shortcode
 */
function sulanga_booking_enqueue_assets() {
  $ver = function ( $rel ) {
    $path = SULANGA_BOOKING_PATH . $rel;
    return file_exists( $path ) ? filemtime( $path ) : SULANGA_BOOKING_VERSION;
  };

  // Shared availability calendar — loaded on every front-end page so both the
  // booking form and the homepage booking bar can use it.
  wp_enqueue_style( 'sulanga-calendar', SULANGA_BOOKING_URL . 'assets/sulanga-calendar.css', array(), $ver( 'assets/sulanga-calendar.css' ) );
  wp_enqueue_script( 'sulanga-calendar', SULANGA_BOOKING_URL . 'assets/sulanga-calendar.js', array(), $ver( 'assets/sulanga-calendar.js' ), true );
  wp_localize_script( 'sulanga-calendar', 'sulanga_cal_vars', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
  ) );

  // Booking-form handler (depends on the calendar; enqueued by the shortcode).
  wp_register_script( 'sulanga-booking-handler', SULANGA_BOOKING_URL . 'assets/booking-handler.js', array( 'sulanga-calendar' ), $ver( 'assets/booking-handler.js' ), true );
  wp_localize_script( 'sulanga-booking-handler', 'sulanga_booking_vars', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce'    => wp_create_nonce( 'sulanga_booking_nonce' ),
  ) );
}
add_action( 'wp_enqueue_scripts', 'sulanga_booking_enqueue_assets' );

/**
 * Render Interactive Booking Form Shortcode `[sulanga_booking_form]`
 */
function sulanga_booking_form_shortcode() {
  // Enqueue script asset dynamically when shortcode runs
  wp_enqueue_script( 'sulanga-booking-handler' );

  ob_start();
  ?>
  <style>
    .booking-msg { padding: 13px 16px; border-radius: 8px; font-size: 14px; line-height: 1.5; margin-bottom: 18px; display: flex; align-items: flex-start; gap: 10px; }
    .booking-msg.is-error { background: #fdecec; border: 1px solid #f5c2c2; color: #9b2c2c; }
    .booking-msg.is-info { background: #eef5ef; border: 1px solid #cfe3d4; color: #1f4a2c; }
    .booking-success { text-align: center; padding: 40px 24px; }
    .booking-success .bs-ic { width: 64px; height: 64px; border-radius: 50%; background: #1f4a2c; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
    .booking-success .bs-ic svg { width: 32px; height: 32px; color: #fff; }
    .booking-success h3 { font-size: 24px; margin-bottom: 10px; color: #1f4a2c; }
    .booking-success p { color: #5a5c54; font-size: 14.5px; line-height: 1.7; margin-bottom: 8px; }
    .booking-success .bs-ref { display: inline-block; margin-top: 14px; padding: 8px 16px; background: #f2f0e9; border-radius: 6px; font-weight: 600; letter-spacing: .03em; }
    .field.has-error .input { border-color: #d96a6a !important; }
    #checkin, #checkout { cursor: pointer; background: #fff; }

    .currency-toggle { display: inline-flex; border: 1px solid #e1ded4; border-radius: 9px; overflow: hidden; margin-bottom: 14px; }
    .currency-toggle .cur-btn { border: none; background: #fff; color: #5a5c54; font-size: 13px; font-weight: 600; padding: 7px 18px; cursor: pointer; transition: .2s; }
    .currency-toggle .cur-btn + .cur-btn { border-left: 1px solid #e1ded4; }
    .currency-toggle .cur-btn.active { background: var(--green, #1f4a2c); color: #fff; }
  </style>
  <div class="booking-grid">
    <!-- LEFT: FORM -->
    <div class="card reveal">
      <div class="card-head">
        <h2><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> Booking Details</h2>
        <p>Please complete the form below to check availability and reserve your stay.</p>
      </div>
      
      <form class="form-body" id="sulanga-booking-form-el">
        <div id="sulanga-booking-msg" class="booking-msg" role="status" aria-live="polite" hidden></div>
        <div class="field-row two-col">
          <div class="field">
            <label for="checkin">Check-in Date</label>
            <div class="with-ic">
              <input type="text" placeholder="Select date" id="checkin" class="input" readonly autocomplete="off" required />
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
          </div>
          <div class="field">
            <label for="checkout">Check-out Date</label>
            <div class="with-ic">
              <input type="text" placeholder="Select date" id="checkout" class="input" readonly autocomplete="off" required />
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
          </div>
        </div>
        <div class="field">
          <label for="room-option">Bedrooms &amp; Occupancy</label>
          <div class="with-ic">
            <select id="room-option" class="input">
              <option value="6" data-bed="6" data-max="15">6 Bedrooms &mdash; Up to 15 Guests</option>
              <option value="5" data-bed="5" data-max="13">5 Bedrooms &mdash; Up to 13 Guests</option>
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 9V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4"/><path d="M2 11h20v6M2 17v2M22 17v2"/><path d="M6 9V8a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v1M13 9V8a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v1"/></svg>
          </div>
        </div>
        <div class="field">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" class="input" placeholder="Enter your full name" required />
        </div>
        <div class="field">
          <label for="email">Email Address</label>
          <input type="email" id="email" class="input" placeholder="Enter your email address" required />
        </div>
        <div class="field">
          <label for="phone-number">Phone Number</label>
          <div class="phone-row">
            <select id="phone-country" class="input">
              <option value="+94">🇱🇰 +94</option>
              <option value="+91">🇮🇳 +91</option>
              <option value="+44">🇬🇧 +44</option>
              <option value="+1">🇺🇸 +1</option>
              <option value="+61">🇦🇺 +61</option>
            </select>
            <input type="tel" id="phone-number" class="input" placeholder="Enter your phone number" required />
          </div>
        </div>
        <div class="field">
          <label for="special-requests">Special Requests (Optional)</label>
          <textarea id="special-requests" class="input" placeholder="Let us know if you have any special requests or requirements..."></textarea>
        </div>
        <button type="submit" id="submit-booking" class="btn btn-green" style="background: var(--green); border: none; color: #fff; width:100%;">
          <span>Continue to Reserve</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
        <div class="secure-note" style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--muted); margin-top: 14px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px; height:14px;"><path d="M12 2 4 5v6c0 5 3.5 9 8 11 4.5-2 8-6 8-11V5z"/><path d="m9 12 2 2 4-4"/></svg>
          Your information is secure and will only be used for your reservation.
        </div>
      </form>
    </div>

    <!-- RIGHT: SUMMARY -->
    <div class="card reveal d2">
      <div class="card-head"><h2 style="font-size:22px">Your Stay Summary</h2></div>
      <div class="sum-img">
        <img src="https://nuwaraeliyahotel.esupportdev2.xyz/wp-content/uploads/2026/06/gMhafAjEDGeoUl1Lf5XaLW0SYDquwBUUd8m4BH0z.jpg.jpeg" alt="Entire villa" />
        <span class="sum-badge">Entire Villa</span>
      </div>
      <div class="sum-body">
        <h3>Entire Villa</h3>
        <ul class="sum-meta">
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Nuwara Eliya, Sri Lanka</li>
          <li id="sum-capacity"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg> <span id="sum-capacity-text">6 Bedrooms &middot; Up to 15 Guests</span></li>
        </ul>
        <ul class="sum-feats">
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg> Plunge Pool</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg> Living &amp; Dining Area</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg> Mountain Views</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg> Balcony &amp; Terrace</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg> Modern Interiors</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg> Whole Property Privacy</li>
        </ul>
        <div class="currency-toggle" id="currencyToggle" role="group" aria-label="Display currency">
          <button type="button" class="cur-btn active" data-cur="LKR">LKR</button>
          <button type="button" class="cur-btn" data-cur="USD">USD</button>
        </div>
        <div class="sum-price">
          <div class="amt" id="nightlyRate">LKR 150,000 <small>/ night</small></div>
        </div>
        <div class="breakdown">
          <div class="row"><span>Nightly Rate (Entire Villa)</span><span class="val" id="rateRow">LKR 150,000</span></div>
          <div class="row"><span>Number of Nights</span>
            <span class="stepper"><button type="button" id="minus">−</button><span id="nights">2</span><button type="button" id="plus">+</button></span>
          </div>
          <div class="total"><b>Total Estimate</b><span class="tval" id="total">LKR 300,000</span></div>
        </div>
        <div class="tax-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
          Taxes and additional fees may apply.
        </div>
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean();
}
add_shortcode( 'sulanga_booking_form', 'sulanga_booking_form_shortcode' );

/**
 * Handle Form Submission AJAX
 */
/**
 * Send a clean JSON response.
 *
 * Discards any stray output (PHP notices/warnings that may have leaked into the
 * buffer) before emitting JSON, so the browser always receives parseable JSON
 * and a saved booking is never reported as a failure.
 */
function sulanga_booking_respond( $success, $payload ) {
  while ( ob_get_level() > 0 ) {
    ob_end_clean();
  }
  if ( $success ) {
    wp_send_json_success( $payload );
  } else {
    wp_send_json_error( $payload );
  }
}

function sulanga_booking_submit_ajax_handler() {
  // Capture (and later discard) any incidental output so it can't corrupt JSON.
  ob_start();

  // Nonce validation
  if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'sulanga_booking_nonce' ) ) {
    sulanga_booking_respond( false, array( 'message' => __( 'Security nonce validation failed.', 'sulanga-booking' ) ) );
  }

  // Input sanitization. wp_unslash() reverses the slashes WordPress adds to
  // $_POST, and the null-coalescing default avoids notices on missing fields.
  $checkin   = sanitize_text_field( wp_unslash( $_POST['checkin'] ?? '' ) );
  $checkout  = sanitize_text_field( wp_unslash( $_POST['checkout'] ?? '' ) );
  $guests    = sanitize_text_field( wp_unslash( $_POST['guests'] ?? '' ) );
  $bedrooms  = intval( $_POST['bedrooms'] ?? 6 );
  // Only two configured options: 5 bedrooms (≤13 guests) or 6 bedrooms (≤15).
  if ( 5 === $bedrooms ) {
    $max_guests = 13;
  } else {
    $bedrooms   = 6;
    $max_guests = 15;
  }
  $guests_int = intval( $guests );
  // No separate guest field on the form — default to the option's max occupancy.
  if ( $guests_int < 1 || $guests_int > $max_guests ) {
    $guests = (string) $max_guests;
  }
  $fullname  = sanitize_text_field( wp_unslash( $_POST['fullname'] ?? '' ) );
  $email     = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
  $country   = sanitize_text_field( wp_unslash( $_POST['country_code'] ?? '' ) );
  $phone     = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
  $requests  = sanitize_textarea_field( wp_unslash( $_POST['requests'] ?? '' ) );
  $nights    = intval( $_POST['nights'] ?? 0 );
  $currency  = strtoupper( sanitize_text_field( wp_unslash( $_POST['currency'] ?? 'LKR' ) ) );
  if ( ! in_array( $currency, array( 'LKR', 'USD' ), true ) ) {
    $currency = 'LKR';
  }

  if ( empty( $checkin ) || empty( $checkout ) || empty( $fullname ) || empty( $email ) || empty( $phone ) ) {
    sulanga_booking_respond( false, array( 'message' => __( 'Please fill in all required fields.', 'sulanga-booking' ) ) );
  }

  // Validate the date range.
  $in_ts  = strtotime( $checkin );
  $out_ts = strtotime( $checkout );
  if ( ! $in_ts || ! $out_ts || $out_ts <= $in_ts ) {
    sulanga_booking_respond( false, array( 'message' => __( 'Please select a valid date range — the check-out date must be after the check-in date.', 'sulanga-booking' ) ) );
  }

  // Availability check: reject if the range overlaps an existing active booking.
  if ( ! sulanga_booking_dates_available( $checkin, $checkout ) ) {
    sulanga_booking_respond( false, array( 'message' => __( 'Sorry, the villa is already reserved for one or more of the dates you selected. Please choose a different date range.', 'sulanga-booking' ) ) );
  }

  // Server-authoritative pricing (the client value is never trusted). No
  // service/cleaning fee — the total is simply nights × nightly rate, converted
  // to the chosen currency, which is also the currency used for payment.
  $rate_lkr  = 150000;
  $usd_rate  = 300;
  $total_lkr = $rate_lkr * max( 1, $nights );
  $total     = ( 'USD' === $currency ) ? (int) round( $total_lkr / $usd_rate ) : $total_lkr;

  // Create Custom Post Type Log entry
  $booking_post = array(
    'post_title'    => sprintf( 'Booking: %s (%s to %s)', $fullname, $checkin, $checkout ),
    'post_status'   => 'publish',
    'post_type'     => 'sulanga_booking',
  );

  $post_id = wp_insert_post( $booking_post );

  if ( is_wp_error( $post_id ) ) {
    sulanga_booking_respond( false, array( 'message' => __( 'Failed to save reservation details to database.', 'sulanga-booking' ) ) );
  }

  // Save Meta Information
  update_post_meta( $post_id, '_checkin', $checkin );
  update_post_meta( $post_id, '_checkout', $checkout );
  update_post_meta( $post_id, '_guests', $guests );
  update_post_meta( $post_id, '_bedrooms', $bedrooms );
  update_post_meta( $post_id, '_email', $email );
  update_post_meta( $post_id, '_phone', $country . ' ' . $phone );
  update_post_meta( $post_id, '_requests', $requests );
  update_post_meta( $post_id, '_status', 'Pending' );
  update_post_meta( $post_id, '_total_estimate', $total );
  update_post_meta( $post_id, '_currency', $currency );
  update_post_meta( $post_id, '_nights', $nights );

  // Trigger Email Notifications (Standard wp_mail)
  $admin_email = get_option( 'admin_email' );
  $subject = sprintf( '[New Booking Reservation] Request #%d', $post_id );

  $headers   = array();
  $headers[] = 'Content-Type: text/html; charset=UTF-8';

  $message_body = "
    <h2>New Reservation Received</h2>
    <p>A new booking reservation has been submitted for Sulaga Luxury Chalets.</p>
    <table style='width:100%; border-collapse:collapse; font-family:sans-serif;'>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Booking ID</th><td style='padding:8px; border-bottom:1px solid #eee;'>#{$post_id}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Guest Name</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$fullname}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Check-in</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$checkin}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Check-out</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$checkout}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Nights</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$nights}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Bedrooms</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$bedrooms}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Guests</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$guests}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Contact Phone</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$country} {$phone}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Email</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$email}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Estimated Cost</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$currency} {$total}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Special Requests</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$requests}</td></tr>
    </table>
    <p>Please log in to the admin panel to review and confirm this booking status.</p>
  ";

  // Email to Guest
  $guest_subject = 'Your Reservation Request at Sulaga Luxury Chalets';
  $guest_message = "
    <h2>Thank You for Your Reservation Request</h2>
    <p>Hi {$fullname},</p>
    <p>We have received your reservation request for Sulaga Luxury Chalets. Our operations team is verifying availability and will send a confirmation shortly.</p>
    <h3>Your Reservation Summary</h3>
    <table style='width:100%; border-collapse:collapse; font-family:sans-serif;'>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Reference</th><td style='padding:8px; border-bottom:1px solid #eee;'>#{$post_id}</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Dates</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$checkin} to {$checkout} ({$nights} Nights)</td></tr>
      <tr><th style='text-align:left; padding:8px; border-bottom:1px solid #eee;'>Guests</th><td style='padding:8px; border-bottom:1px solid #eee;'>{$guests}</td></tr>
    </table>
    <p>If you have any questions, please contact us directly at rtissera@hotmail.com.</p>
    <p>Best regards,<br/>The Sulaga Chalets Team</p>
  ";

  // Send notifications. Email delivery must never break a successful booking,
  // so any mail-layer error is swallowed — the reservation is already saved.
  try {
    wp_mail( $admin_email, $subject, $message_body, $headers );
    wp_mail( $email, $guest_subject, $guest_message, $headers );
  } catch ( \Throwable $e ) {
    // Catches both Exceptions and Errors — booking is saved regardless of email.
  }

  sulanga_booking_respond( true, array( 'booking_id' => $post_id ) );
}
add_action( 'wp_ajax_submit_booking', 'sulanga_booking_submit_ajax_handler' );
add_action( 'wp_ajax_nopriv_submit_booking', 'sulanga_booking_submit_ajax_handler' );

/**
 * Return all date ranges that are currently reserved (not cancelled).
 *
 * Cancelled bookings are skipped, and deleted/trashed bookings simply no longer
 * exist — so removing or cancelling a booking in the admin automatically frees
 * its dates for new reservations.
 *
 * @param int $exclude_id Optional booking ID to ignore (used when editing).
 * @return array[] List of array( 'checkin' => 'Y-m-d', 'checkout' => 'Y-m-d' ).
 */
function sulanga_booking_get_reserved_ranges( $exclude_id = 0 ) {
  $ranges   = array();
  $bookings = get_posts( array(
    'post_type'      => 'sulanga_booking',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'no_found_rows'  => true,
  ) );

  foreach ( $bookings as $id ) {
    if ( $exclude_id && (int) $id === (int) $exclude_id ) {
      continue;
    }
    if ( 'Cancelled' === get_post_meta( $id, '_status', true ) ) {
      continue;
    }
    $checkin  = get_post_meta( $id, '_checkin', true );
    $checkout = get_post_meta( $id, '_checkout', true );
    if ( $checkin && $checkout ) {
      $ranges[] = array(
        'checkin'  => $checkin,
        'checkout' => $checkout,
      );
    }
  }

  return $ranges;
}

/**
 * Check whether a requested date range is free of any active booking.
 *
 * Treats check-out day as available for a new check-in (standard hotel logic):
 * two ranges overlap only when new_in < existing_out AND existing_in < new_out.
 *
 * @return bool True if the range can be booked.
 */
function sulanga_booking_dates_available( $checkin, $checkout, $exclude_id = 0 ) {
  $new_in  = strtotime( $checkin );
  $new_out = strtotime( $checkout );
  if ( ! $new_in || ! $new_out || $new_out <= $new_in ) {
    return false;
  }

  foreach ( sulanga_booking_get_reserved_ranges( $exclude_id ) as $range ) {
    $ex_in  = strtotime( $range['checkin'] );
    $ex_out = strtotime( $range['checkout'] );
    if ( ! $ex_in || ! $ex_out ) {
      continue;
    }
    if ( $new_in < $ex_out && $ex_in < $new_out ) {
      return false;
    }
  }

  return true;
}

/**
 * AJAX: expose reserved date ranges so the booking form can block them.
 */
function sulanga_booking_booked_dates_handler() {
  wp_send_json_success( array( 'ranges' => sulanga_booking_get_reserved_ranges() ) );
}
add_action( 'wp_ajax_get_booked_dates', 'sulanga_booking_booked_dates_handler' );
add_action( 'wp_ajax_nopriv_get_booked_dates', 'sulanga_booking_booked_dates_handler' );

/* ============================================================
 * ADMIN UI: booking list columns, status badges & styling
 * ============================================================ */

/**
 * Replace the bare Title column with informative booking columns.
 */
function sulanga_booking_admin_columns( $columns ) {
  return array(
    'cb'       => isset( $columns['cb'] ) ? $columns['cb'] : '<input type="checkbox" />',
    'title'    => __( 'Booking', 'sulanga-booking' ),
    'status'   => __( 'Status', 'sulanga-booking' ),
    'checkin'  => __( 'Check-in', 'sulanga-booking' ),
    'checkout' => __( 'Check-out', 'sulanga-booking' ),
    'nights'   => __( 'Nights', 'sulanga-booking' ),
    'guests'   => __( 'Guests', 'sulanga-booking' ),
    'email'    => __( 'Email', 'sulanga-booking' ),
    'total'    => __( 'Total', 'sulanga-booking' ),
    'date'     => __( 'Received', 'sulanga-booking' ),
  );
}
add_filter( 'manage_sulanga_booking_posts_columns', 'sulanga_booking_admin_columns' );

/**
 * Render the custom column cells.
 */
function sulanga_booking_admin_column_content( $column, $post_id ) {
  switch ( $column ) {
    case 'status':
      $status = get_post_meta( $post_id, '_status', true );
      if ( empty( $status ) ) {
        $status = 'Pending';
      }
      printf(
        '<span class="sulanga-badge sulanga-%s">%s</span>',
        esc_attr( strtolower( $status ) ),
        esc_html( $status )
      );
      break;

    case 'checkin':
      echo esc_html( get_post_meta( $post_id, '_checkin', true ) ?: '—' );
      break;

    case 'checkout':
      echo esc_html( get_post_meta( $post_id, '_checkout', true ) ?: '—' );
      break;

    case 'nights':
      echo esc_html( get_post_meta( $post_id, '_nights', true ) ?: '—' );
      break;

    case 'guests':
      echo esc_html( get_post_meta( $post_id, '_guests', true ) ?: '—' );
      break;

    case 'email':
      $email = get_post_meta( $post_id, '_email', true );
      if ( $email ) {
        printf( '<a href="mailto:%s">%s</a>', esc_attr( $email ), esc_html( $email ) );
      } else {
        echo '—';
      }
      break;

    case 'total':
      $total = get_post_meta( $post_id, '_total_estimate', true );
      $cur   = get_post_meta( $post_id, '_currency', true );
      if ( empty( $cur ) ) {
        $cur = 'LKR';
      }
      echo ( '' !== $total ) ? esc_html( $cur . ' ' . number_format_i18n( (float) $total ) ) : '—';
      break;
  }
}
add_action( 'manage_sulanga_booking_posts_custom_column', 'sulanga_booking_admin_column_content', 10, 2 );

/**
 * Make Check-in and Status sortable.
 */
function sulanga_booking_sortable_columns( $columns ) {
  $columns['checkin'] = 'checkin';
  $columns['status']  = 'status';
  return $columns;
}
add_filter( 'manage_edit-sulanga_booking_sortable_columns', 'sulanga_booking_sortable_columns' );

/**
 * Apply meta-based ordering for the sortable columns.
 */
function sulanga_booking_orderby( $query ) {
  if ( ! is_admin() || ! $query->is_main_query() ) {
    return;
  }
  $orderby = $query->get( 'orderby' );
  if ( 'checkin' === $orderby ) {
    $query->set( 'meta_key', '_checkin' );
    $query->set( 'orderby', 'meta_value' );
  } elseif ( 'status' === $orderby ) {
    $query->set( 'meta_key', '_status' );
    $query->set( 'orderby', 'meta_value' );
  }
}
add_action( 'pre_get_posts', 'sulanga_booking_orderby' );

/**
 * Admin styling for status badges and the booking edit screen.
 */
function sulanga_booking_admin_styles() {
  $screen = get_current_screen();
  if ( ! $screen || 'sulanga_booking' !== $screen->post_type ) {
    return;
  }
  ?>
  <style>
    .sulanga-badge { display: inline-block; padding: 3px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; line-height: 1.6; }
    .sulanga-pending { background: #fef3cd; color: #8a6d1b; }
    .sulanga-confirmed { background: #d6f0dd; color: #1f6b35; }
    .sulanga-cancelled { background: #fbdada; color: #a12626; }
    .column-status, .column-nights, .column-guests { width: 90px; }
    .column-checkin, .column-checkout { width: 110px; }
    .column-total { width: 110px; }

    /* Booking edit meta box */
    .booking-meta-table th { color: #1d2327; }
    .booking-meta-table input, .booking-meta-table select, .booking-meta-table textarea { padding: 6px 8px; border-radius: 5px; }
    #sulanga_booking_details .inside { padding: 0 12px 12px; }
  </style>
  <?php
}
add_action( 'admin_head', 'sulanga_booking_admin_styles' );
