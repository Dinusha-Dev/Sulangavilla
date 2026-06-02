/**
 * Sulanga Booking Handler JS
 *
 * Booking form behaviour. The date selection is delegated to the shared
 * SulangaCalendar (two-month range picker that blocks reserved dates); this
 * file handles pricing, the nights stepper, submission and messaging.
 */
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('sulanga-booking-form-el');
  if (!form || typeof SulangaCalendar === 'undefined') return;

  const ci = document.getElementById('checkin');
  const co = document.getElementById('checkout');
  const roomSelect = document.getElementById('room-option');
  const sumCapacity = document.getElementById('sum-capacity-text');
  const elNights = document.getElementById('nights');
  const elRateRow = document.getElementById('rateRow');
  const elNightlyRate = document.getElementById('nightlyRate');
  const elTotal = document.getElementById('total');
  const currencyToggle = document.getElementById('currencyToggle');
  const btnPlus = document.getElementById('plus');
  const btnMinus = document.getElementById('minus');
  const submitBtn = document.getElementById('submit-booking');
  const msgEl = document.getElementById('sulanga-booking-msg');

  const RATE = 150000;       // nightly rate in LKR (no extra fees)
  const USD_RATE = 300;      // 1 USD ≈ 300 LKR
  let nights = 2;
  let currency = 'LKR';      // display + payment currency (LKR or USD)

  let selectedIn = null;
  let selectedOut = null;

  const ajaxUrl = sulanga_booking_vars.ajax_url;

  function showMsg(text, type) {
    if (!msgEl) return;
    msgEl.textContent = text;
    msgEl.className = 'booking-msg ' + (type === 'error' ? 'is-error' : 'is-info');
    msgEl.hidden = false;
  }
  function clearMsg() { if (msgEl) { msgEl.hidden = true; msgEl.textContent = ''; } }

  // Convert an LKR amount to the selected currency.
  function toCurrency(lkr) {
    return currency === 'USD' ? Math.round(lkr / USD_RATE) : lkr;
  }
  function fmtPrice(lkr) {
    return currency + ' ' + toCurrency(lkr).toLocaleString('en-US');
  }

  function render() {
    const total = RATE * nights; // no service/cleaning fee
    if (elNights) elNights.textContent = nights;
    if (elNightlyRate) elNightlyRate.innerHTML = fmtPrice(RATE) + ' <small>/ night</small>';
    if (elRateRow) elRateRow.textContent = fmtPrice(RATE);
    if (elTotal) elTotal.textContent = fmtPrice(total);
  }

  // Bedrooms & occupancy option — sets the capacity (no separate guest field).
  let bedrooms = roomSelect ? parseInt(roomSelect.value, 10) : 6;
  let maxGuests = 15;
  function applyRoomOption() {
    if (!roomSelect) return;
    const opt = roomSelect.selectedOptions[0];
    bedrooms = parseInt(opt.getAttribute('data-bed'), 10) || 6;
    maxGuests = parseInt(opt.getAttribute('data-max'), 10) || 15;
    if (sumCapacity) sumCapacity.textContent = bedrooms + ' Bedrooms · Up to ' + maxGuests + ' Guests';
  }
  if (roomSelect) {
    roomSelect.addEventListener('change', applyRoomOption);
    applyRoomOption();
  }

  // Currency selector (LKR / USD) — also used as the payment currency.
  if (currencyToggle) {
    currencyToggle.addEventListener('click', function (e) {
      const btn = e.target.closest('.cur-btn');
      if (!btn) return;
      currency = btn.getAttribute('data-cur');
      currencyToggle.querySelectorAll('.cur-btn').forEach(function (b) {
        b.classList.toggle('active', b === btn);
      });
      render();
    });
  }

  // Attach the shared calendar; it owns date selection and reserved-date logic.
  const calApi = SulangaCalendar.attach({
    checkin: ci,
    checkout: co,
    onChange: function (inDate, outDate) {
      selectedIn = inDate;
      selectedOut = outDate;
      if (inDate && outDate) {
        nights = Math.round((outDate - inDate) / 86400000) || 1;
        clearMsg();
        submitBtn.disabled = false;
      }
      render();
    }
  });

  // Load reserved ranges, then re-render the (closed) calendar + prefill.
  SulangaCalendar.load(ajaxUrl, function () {
    calApi.rerender();
    prefillFromQuery();
  });

  function prefillFromQuery() {
    const params = new URLSearchParams(window.location.search);
    const ciP = params.get('checkin');
    const coP = params.get('checkout');
    const bP = params.get('bedrooms');
    if (bP && roomSelect) {
      for (let i = 0; i < roomSelect.options.length; i++) {
        if (roomSelect.options[i].value === String(bP)) { roomSelect.selectedIndex = i; break; }
      }
      applyRoomOption();
    }
    if (ciP && /^\d{4}-\d{2}-\d{2}$/.test(ciP)) {
      const inD = SulangaCalendar.parseISO(ciP);
      const outD = (coP && /^\d{4}-\d{2}-\d{2}$/.test(coP)) ? SulangaCalendar.parseISO(coP) : SulangaCalendar.addDays(inD, nights);
      calApi.setRange(inD, outD); // setRange ignores unavailable ranges (no error shown)
    }
  }

  /* nights stepper, clamped to availability */
  function maxNights() {
    if (!selectedIn) return Infinity;
    const cap = SulangaCalendar.nextReservedAfter(selectedIn);
    return cap ? Math.round((cap - selectedIn) / 86400000) : Infinity;
  }
  function applyNights() {
    if (selectedIn) {
      calApi.setRange(selectedIn, SulangaCalendar.addDays(selectedIn, nights));
    } else {
      render();
    }
  }
  if (btnPlus) {
    btnPlus.addEventListener('click', () => {
      if (nights + 1 > maxNights()) {
        showMsg('The villa is reserved right after your check-in, so the stay cannot be extended further. Pick an earlier check-in for a longer stay.', 'info');
        return;
      }
      nights++;
      applyNights();
    });
  }
  if (btnMinus) {
    btnMinus.addEventListener('click', () => { if (nights > 1) { nights--; applyNights(); } });
  }

  render();

  /* success panel */
  function showSuccess(bookingId, email) {
    const card = form.closest('.card') || form;
    card.innerHTML =
      '<div class="booking-success">' +
        '<div class="bs-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg></div>' +
        '<h3>Reservation Request Received</h3>' +
        '<p>Thank you! Your request has been submitted successfully.</p>' +
        '<p>A confirmation email is on its way to <strong>' + email + '</strong>. Our team will verify availability and confirm shortly.</p>' +
        '<span class="bs-ref">Booking Reference: #' + bookingId + '</span>' +
      '</div>';
    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  /* submit */
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    clearMsg();

    if (!selectedIn || !selectedOut) {
      showMsg('Please select your check-in and check-out dates.', 'error');
      calApi.open();
      return;
    }

    const emailVal = document.getElementById('email').value;
    submitBtn.disabled = true;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span>Processing reservation...</span>';

    const formData = new FormData();
    formData.append('action', 'submit_booking');
    formData.append('nonce', sulanga_booking_vars.nonce);
    formData.append('checkin', SulangaCalendar.toISO(selectedIn));
    formData.append('checkout', SulangaCalendar.toISO(selectedOut));
    formData.append('guests', maxGuests);
    formData.append('bedrooms', bedrooms);
    formData.append('fullname', document.getElementById('fullname').value);
    formData.append('email', emailVal);
    formData.append('country_code', document.getElementById('phone-country').value);
    formData.append('phone', document.getElementById('phone-number').value);
    formData.append('requests', document.getElementById('special-requests').value);
    formData.append('nights', nights);
    formData.append('currency', currency);
    formData.append('total_estimate', toCurrency(RATE * nights)); // amount in the selected currency

    fetch(ajaxUrl, { method: 'POST', body: formData })
      .then(response => response.text())
      .then(text => {
        // Parse defensively in case stray output prepends/appends the JSON.
        let data;
        try {
          data = JSON.parse(text);
        } catch (err) {
          const s = text.indexOf('{'), e2 = text.lastIndexOf('}');
          if (s !== -1 && e2 !== -1) {
            try { data = JSON.parse(text.slice(s, e2 + 1)); } catch (e3) { data = null; }
          }
        }

        if (data && data.success) {
          showSuccess(data.data.booking_id, emailVal);
          return;
        }

        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        showMsg(
          (data && data.data && data.data.message) ||
          'We could not process your booking. Please try again or contact reservations directly.',
          'error'
        );
        // Likely a freshly-taken date — refresh availability so the calendar updates.
        SulangaCalendar.refresh(ajaxUrl, function () { calApi.rerender(); });
      })
      .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        console.error('Error submitting booking:', error);
        showMsg('There was a network problem submitting your booking. Please check your connection and try again.', 'error');
      });
  });
});
