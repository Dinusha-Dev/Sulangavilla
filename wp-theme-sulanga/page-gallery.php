<?php
/**
 * Template Name: Gallery Template
 *
 * @package Sulanga
 */

get_header();
?>

<!-- HERO -->
<header class="hero">
  <div class="hero-bg"><img src="https://nuwaraeliyahotel.esupportdev2.xyz/wp-content/uploads/2026/06/eGhLj1JB6hbTewWWnlnpYryAVSnr8FS4Kdwk11Qx.jpg.jpeg" alt="Sulanga villa at dusk" /></div>
  <div class="container">
    <span class="eyebrow h-anim a1">Gallery</span>
    <h1 class="h-anim a2">Moments of<br/>Tranquility</h1>
    <p class="h-anim a3">Explore the beauty, comfort, and experiences that make Sulanga Luxury Chalets a truly unforgettable escape.</p>
  </div>
</header>



<section class="gallery-sec">
  <div class="container">
    
    <?php $sulanga_gallery = sulanga_get_gallery_images(); ?>

    <!-- Filter Bar -->
    <div class="filter-bar reveal">
      <div class="filter-buttons" id="filterButtons">
        <button class="filter-btn active" data-filter="all"><?php echo count( $sulanga_gallery ); ?> Photos</button>
      </div>
      <button class="slideshow-btn" id="startSlideshow">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        <span>View Slideshow</span>
      </button>
    </div>

    <!-- Masonry Grid (images pulled from the WordPress Media Library) -->
    <div class="gallery-grid reveal d1" id="galleryGrid">
      <?php
      if ( ! empty( $sulanga_gallery ) ) :
        foreach ( $sulanga_gallery as $sulanga_img ) :
          $sulanga_caption = $sulanga_img->post_title;
          $sulanga_full    = wp_get_attachment_image_url( $sulanga_img->ID, 'full' );
          ?>
          <div class="gallery-item" data-category="all" data-full="<?php echo esc_url( $sulanga_full ); ?>">
            <?php
            echo wp_get_attachment_image(
              $sulanga_img->ID,
              'large',
              false,
              array(
                'alt'     => esc_attr( $sulanga_caption ),
                'loading' => 'lazy',
              )
            );
            ?>
          </div>
          <?php
        endforeach;
      else :
        ?>
        <p class="gallery-empty">No images yet. Add photos to the <strong>Media Library</strong> in WordPress and they will appear here automatically.</p>
        <?php
      endif;
      ?>
    </div>
  </div>
</section>

<!-- LIGHTBOX MODAL -->
<div class="lightbox" id="lightbox">
  <button class="lightbox-close" id="lightboxClose" aria-label="Close">&times;</button>
  <button class="lightbox-arrow prev" id="lightboxPrev" aria-label="Previous">&#10094;</button>
  <div class="lightbox-content">
    <img id="lightboxImg" src="" alt="Slideshow Image" />
  </div>
  <button class="lightbox-arrow next" id="lightboxNext" aria-label="Next">&#10095;</button>
</div>

<script>
  const filterButtons = document.getElementById('filterButtons');
  const galleryItems = document.querySelectorAll('.gallery-item');

  function applyFilter(filterVal) {
    galleryItems.forEach(item => {
      const categories = item.getAttribute('data-category').split(' ');
      if (filterVal === 'all' || categories.includes(filterVal)) {
        item.classList.remove('hide');
      } else {
        item.classList.add('hide');
      }
    });
  }

  if (filterButtons) {
    filterButtons.addEventListener('click', (e) => {
      if (e.target.classList.contains('filter-btn')) {
        filterButtons.querySelector('.filter-btn.active').classList.remove('active');
        e.target.classList.add('active');
        const filterVal = e.target.getAttribute('data-filter');
        applyFilter(filterVal);
      }
    });
  }

  // URL Parameter triggering
  (function() {
    const params = new URLSearchParams(window.location.search);
    const filterParam = params.get('filter');
    if (filterParam && filterButtons) {
      const btn = filterButtons.querySelector(`[data-filter="${filterParam}"]`);
      if (btn) {
        filterButtons.querySelector('.filter-btn.active').classList.remove('active');
        btn.classList.add('active');
        applyFilter(filterParam);
      }
    }
  })();

  /* Lightbox slider */
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightboxImg');
  const closeBtn = document.getElementById('lightboxClose');
  const prevBtn = document.getElementById('lightboxPrev');
  const nextBtn = document.getElementById('lightboxNext');
  const startSlideshowBtn = document.getElementById('startSlideshow');

  let activeItems = [];
  let currentIndex = 0;

  function updateActiveItems() {
    activeItems = Array.from(galleryItems).filter(item => !item.classList.contains('hide'));
  }

  function showImage(index) {
    if (index < 0) index = activeItems.length - 1;
    if (index >= activeItems.length) index = 0;
    
    currentIndex = index;
    const currentItem = activeItems[currentIndex];
    const imgEl = currentItem.querySelector('img');

    // Prefer the full-size image stored on the item; fall back to the thumbnail.
    lightboxImg.src = currentItem.getAttribute('data-full') || imgEl.src;
    lightboxImg.alt = imgEl.alt || '';
  }

  galleryItems.forEach(item => {
    item.addEventListener('click', () => {
      updateActiveItems();
      const index = activeItems.indexOf(item);
      if (index !== -1) {
        showImage(index);
        lightbox.classList.add('active');
      }
    });
  });

  if (startSlideshowBtn) {
    startSlideshowBtn.addEventListener('click', () => {
      updateActiveItems();
      if (activeItems.length > 0) {
        showImage(0);
        lightbox.classList.add('active');
      }
    });
  }

  if (closeBtn) closeBtn.addEventListener('click', () => lightbox.classList.remove('active'));
  if (prevBtn) prevBtn.addEventListener('click', () => showImage(currentIndex - 1));
  if (nextBtn) nextBtn.addEventListener('click', () => showImage(currentIndex + 1));

  if (lightbox) {
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox) {
        lightbox.classList.remove('active');
      }
    });
  }

  window.addEventListener('keydown', (e) => {
    if (lightbox && lightbox.classList.contains('active')) {
      if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
      if (e.key === 'ArrowRight') showImage(currentIndex + 1);
      if (e.key === 'Escape') lightbox.classList.remove('active');
    }
  });
</script>

<?php
get_footer();
