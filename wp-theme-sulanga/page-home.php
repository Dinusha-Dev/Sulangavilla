<?php
/**
 * Template Name: Homepage Template
 *
 * @package Sulanga
 */

get_header();
?>

<!-- HERO -->
<header class="hero">
  <div class="hero-bg"><img src="<?php echo sulanga_upload_url( '2026/06/ChatGPT-Image-Jun-1-2026-10_09_17-PM.png' ); ?>" alt="Sulanga Luxury Chalet" /></div>
  <div class="container">
    <div class="hero-content">
      <span class="hero-badge h-anim a1">Full Villa &bull; 5&ndash;6 Bedrooms &bull; Up to 15 Guests</span>
      <h1 class="h-anim a2">Your Private<br>Luxury Escape in<br>Nuwara Eliya</h1>
      <p class="h-anim a3">A single, exclusive villa surrounded by mountains, crafted for privacy, comfort and unforgettable moments—only for you.</p>
      <div class="hero-cursive h-anim a4">Unwind. Reconnect. Belong.</div>
    </div>
  </div>
</header>

<!-- BOOKING BAR -->
<div class="booking-bar-container">
  <div class="container">
    <div class="booking-bar">
      <form class="booking-row" action="<?php echo esc_url( home_url( '/booking/' ) ); ?>" method="GET">
        <div class="booking-field">
          <label for="checkin">Check-in</label>
          <div class="field-inner">
            <input type="text" placeholder="Select date" id="checkin" readonly autocomplete="off" required />
            <input type="hidden" name="checkin" id="checkin-iso" />
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <rect x="3" y="4" width="18" height="18" rx="2" />
              <path d="M16 2v4M8 2v4M3 10h18" />
            </svg>
          </div>
        </div>
        <div class="booking-field">
          <label for="checkout">Check-out</label>
          <div class="field-inner">
            <input type="text" placeholder="Select date" id="checkout" readonly autocomplete="off" required />
            <input type="hidden" name="checkout" id="checkout-iso" />
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <rect x="3" y="4" width="18" height="18" rx="2" />
              <path d="M16 2v4M8 2v4M3 10h18" />
            </svg>
          </div>
        </div>
        <div class="booking-field">
          <label for="bedrooms">Bedrooms &amp; Occupancy</label>
          <div class="field-inner">
            <select name="bedrooms" id="bedrooms">
              <option value="6">6 Bedrooms &mdash; Up to 15 Guests</option>
              <option value="5">5 Bedrooms &mdash; Up to 13 Guests</option>
            </select>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M2 9V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4" />
              <path d="M2 11h20v6M2 17v2M22 17v2" />
              <path d="M6 9V8a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v1M13 9V8a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v1" />
            </svg>
          </div>
        </div>
        <div class="booking-action">
          <button type="submit" class="btn btn-green">
            <span>Check Availability</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M5 12h14M13 6l6 6-6 6" />
            </svg>
          </button>
        </div>
      </form>
      <div class="booking-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#3a8055;">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
          <path d="m22 4-10 10.01-3-3" />
        </svg>
        Entire villa booking only. You will have the whole villa to yourselves.
      </div>
    </div>
  </div>
</div>

<!-- ABOUT SULANGA -->
<section class="about" id="about">
  <div class="container">
    <div class="about-grid">
      <div class="about-text reveal">
        <span class="eyebrow">About Sulanga</span>
        <h2>A Private Sanctuary<br>in the Heart of Nature</h2>
        <p>Nestled in the misty highlands of Nuwara Eliya, Sulanga is a standalone luxury villa designed for those who value privacy, elegance and serenity.</p>
        <p>From breathtaking mountain views to a private plunge pool, every detail is thoughtfully curated to create a seamless and indulgent stay.</p>
        <a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>" class="btn btn-green">
          <span>Discover The Villa</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M13 6l6 6-6 6" />
          </svg>
        </a>
      </div>
      <div class="about-img-wrap reveal d2">
        <div class="about-img">
          <img src="<?php echo sulanga_upload_url( '2026/06/6VuYu3se3IEDlj8ORzHm374hWIBvr28x1BUkQPxK.jpg.jpeg' ); ?>" alt="Daytime Chalet Exterior" />
        </div>
      </div>
    </div>
  </div>
</section>

<!-- THE SULANGA EXPERIENCE -->
<section class="experience">
  <div class="container">
    <div class="experience-head reveal">
      <span class="eyebrow">The Sulanga Experience</span>
      <h2>One Villa. Complete Privacy. Pure Luxury.</h2>
    </div>
    <div class="experience-grid">
      <!-- Card 1 -->
      <div class="exp-card reveal">
        <div class="exp-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M2 12c2 0 2 2 4 2s2-2 4-2 2 2 4 2 2-2 4-2 2 2 4 2M6 12V5a2 2 0 0 1 4 0" />
          </svg></div>
        <h3>Private Plunge Pool</h3>
        <p>All yours, all the time.</p>
      </div>
      <!-- Card 2 -->
      <div class="exp-card reveal d1">
        <div class="exp-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg></div>
        <h3>Panoramic Views</h3>
        <p>Breathtaking mountains from every angle.</p>
      </div>
      <!-- Card 3 -->
      <div class="exp-card reveal d2">
        <div class="exp-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M9 3v18M3 9h18M3 15h18" />
          </svg></div>
        <h3>Modern Interiors</h3>
        <p>Elegant spaces with luxurious comfort.</p>
      </div>
      <!-- Card 4 -->
      <div class="exp-card reveal">
        <div class="exp-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M4 21V10h16v11M4 10l8-6 8 6M9 21v-6h6v6" />
          </svg></div>
        <h3>Balcony & Terrace</h3>
        <p>Relax and take in the cool highland breeze.</p>
      </div>
      <!-- Card 5 -->
      <div class="exp-card reveal d1">
        <div class="exp-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M12 2 2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
          </svg></div>
        <h3>Peaceful Surroundings</h3>
        <p>Nature, calm and complete tranquility.</p>
      </div>
      <!-- Card 6 -->
      <div class="exp-card reveal d2">
        <div class="exp-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg></div>
        <h3>Complete Privacy</h3>
        <p>Entire villa, exclusively yours.</p>
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

<!-- EXPLORE SULANGA -->
<section class="explore">
  <div class="container">
    <div class="explore-head reveal">
      <div>
        <span class="eyebrow">Explore Sulanga</span>
        <h2>Inside the Villa</h2>
      </div>
      <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="btn-gallery">
        <span>View Full Gallery</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="9" />
          <rect x="14" y="3" width="7" height="5" />
          <rect x="14" y="12" width="7" height="9" />
          <rect x="3" y="16" width="7" height="5" />
        </svg>
      </a>
    </div>
    <div class="explore-grid">
      <!-- Item 1 -->
      <a href="<?php echo esc_url( home_url( '/gallery/?filter=exterior' ) ); ?>" class="explore-card reveal">
        <img src="<?php echo sulanga_upload_url( '2026/06/ChatGPT-Image-Jun-1-2026-10_09_17-PM.png' ); ?>" alt="Exterior" />
        <div class="explore-card-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            <polyline points="9 22 9 12 15 12 15 22" />
          </svg>
          <span>Exterior</span>
        </div>
      </a>
      <!-- Item 2 -->
      <a href="<?php echo esc_url( home_url( '/gallery/?filter=interior' ) ); ?>" class="explore-card reveal d1">
        <img src="<?php echo sulanga_upload_url( '2026/06/gMhafAjEDGeoUl1Lf5XaLW0SYDquwBUUd8m4BH0z.jpg.jpeg' ); ?>" alt="Living Area" />
        <div class="explore-card-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M3 18v-6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v6M3 18h18" />
          </svg>
          <span>Living Area</span>
        </div>
      </a>
      <!-- Item 3 -->
      <a href="<?php echo esc_url( home_url( '/gallery/?filter=rooms' ) ); ?>" class="explore-card reveal d2">
        <img src="<?php echo sulanga_upload_url( '2026/06/Izm9Fx7wtf3MoWYcHfMTDIdlAD5tOB1SP9zgjjoE.jpg.jpeg' ); ?>" alt="Bedroom" />
        <div class="explore-card-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
            <line x1="9" y1="9" x2="15" y2="9" />
            <line x1="9" y1="13" x2="15" y2="13" />
            <line x1="9" y1="17" x2="13" y2="17" />
          </svg>
          <span>Bedroom</span>
        </div>
      </a>
      <!-- Item 4 -->
      <a href="<?php echo esc_url( home_url( '/gallery/?filter=pool' ) ); ?>" class="explore-card reveal d3">
        <img src="<?php echo sulanga_upload_url( '2026/06/A18b0EvjwP5wDqu0xz9bmTOTGQYO1d9YfroLI21a.jpg.jpeg' ); ?>" alt="Plunge Pool" />
        <div class="explore-card-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M2 12c2 0 2 2 4 2s2-2 4-2 2 2 4 2 2-2 4-2 2 2 4 2M6 12V5a2 2 0 0 1 4 0" />
          </svg>
          <span>Plunge Pool</span>
        </div>
      </a>
      <!-- Item 5 -->
      <a href="<?php echo esc_url( home_url( '/gallery/?filter=views' ) ); ?>" class="explore-card reveal d4">
        <img src="<?php echo sulanga_upload_url( '2026/06/Jic2J5B3IjB6mMLr0rq5HdGdtxrSF85e37aXLxfi.jpg.jpeg' ); ?>" alt="Mountain Views" />
        <div class="explore-card-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
          </svg>
          <span>Mountain Views</span>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- HIGHLIGHT -->
<section class="highlight">
  <div class="container">
    <div class="highlight-card reveal">
      <div class="hl-grid">
        <div class="hl-img-col">
          <img src="<?php echo sulanga_upload_url( '2026/06/ChatGPT-Image-Jun-1-2026-10_09_17-PM.png' ); ?>" alt="Sulanga Luxury Villa landscape" />
        </div>
        <div class="hl-text-col">
          <div class="hl-content-grid">
            <div class="hl-left-part">
              <div>
                <span class="hl-badge">Entire Villa</span>
                <h2>Sulanga Luxury Villa</h2>
                <p>A private hideaway in Nuwara Eliya with modern amenities, a private plunge pool, spacious living areas and panoramic mountain views.</p>
              </div>
              
              <div class="hl-pricing-list">
                <div class="hl-pricing-item">
                  <div class="hl-pricing-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                      <rect x="3" y="4" width="18" height="18" rx="2" />
                      <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                  </div>
                  <div class="hl-pricing-details">
                    <span class="hl-price-value">LKR 150,000</span> <span class="hl-price-unit">/ night</span>
                  </div>
                </div>
                <div class="hl-pricing-item">
                  <div class="hl-pricing-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                      <line x1="12" y1="1" x2="12" y2="23"></line>
                      <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                  </div>
                  <div class="hl-pricing-details">
                    <span class="hl-price-approx">Approx. USD 500</span> <span class="hl-price-unit">/ night</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="hl-right-part">
              <ul class="hl-features">
                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                  </svg> Up to 15 Guests</li>
                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                  </svg> 5 or 6 Bedrooms</li>
                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                  </svg> Private Plunge Pool</li>
                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                  </svg> Full Kitchen</li>
                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                  </svg> High-Speed Wi-Fi</li>
                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                  </svg> Free Parking</li>
              </ul>
              
              <a href="<?php echo esc_url( home_url( '/booking/' ) ); ?>" class="btn-hl-avail">
                <span>Check Availability</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- GUEST EXPERIENCES -->
<section class="testimonials">
  <div class="container">
    <div class="testimonials-head reveal" style="justify-content:center; text-align:center;">
      <div>
        <span class="eyebrow">Guest Experiences</span>
        <h2>Loved by Our Guests</h2>
      </div>
    </div>

    <div class="testimonials-reviews reveal d1">
      <?php echo do_shortcode( '[grw id=158]' ); ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta">
  <div class="cta-bg-pattern"></div>
  <div class="container">
    <h2 class="reveal">Ready to Escape?</h2>
    <p class="reveal d1">Book the entire villa and enjoy a private, luxurious retreat in Nuwara Eliya.</p>
    <a href="<?php echo esc_url( home_url( '/booking/' ) ); ?>" class="btn btn-white reveal d2">
      <span>Book the Entire Villa</span>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M5 12h14M13 6l6 6-6 6" />
      </svg>
    </a>
  </div>
</section>

<script>
  /* Homepage booking bar: use the shared availability calendar (blocks reserved
     dates) and pass the chosen ISO dates to the booking page via hidden fields.
     Deferred to DOMContentLoaded so the footer-loaded calendar script exists. */
  document.addEventListener('DOMContentLoaded', function () {
    const ci = document.getElementById('checkin'), co = document.getElementById('checkout');
    const ciIso = document.getElementById('checkin-iso'), coIso = document.getElementById('checkout-iso');
    if (!ci || !co || typeof SulangaCalendar === 'undefined' || typeof sulanga_cal_vars === 'undefined') return;

    const api = SulangaCalendar.attach({
      checkin: ci,
      checkout: co,
      onChange: function (inD, outD) {
        if (ciIso) ciIso.value = inD ? SulangaCalendar.toISO(inD) : '';
        if (coIso) coIso.value = outD ? SulangaCalendar.toISO(outD) : '';
      }
    });
    SulangaCalendar.load(sulanga_cal_vars.ajax_url, function () { api.rerender(); });
  });

</script>

<?php
get_footer();
