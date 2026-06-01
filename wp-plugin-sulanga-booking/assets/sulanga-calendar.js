/**
 * Sulanga Calendar — a reusable two-month range date picker.
 *
 * Shared by the booking form and the homepage booking bar so both use identical
 * availability logic. Reserved date ranges (loaded once from the server) are
 * shown disabled and cannot be selected, and a range cannot span a reserved
 * night.
 *
 * Usage:
 *   SulangaCalendar.load(ajaxUrl);                       // preload reserved dates
 *   var api = SulangaCalendar.attach({
 *     checkin:  inputEl,
 *     checkout: inputEl,
 *     ajaxUrl:  '...admin-ajax.php',
 *     onChange: function (inDate, outDate) { ... }       // Date objects or null
 *   });
 */
window.SulangaCalendar = (function () {
  'use strict';

  /* ---------- shared reserved-range store ---------- */
  var reserved = [];          // [{ in: Date, out: Date }]
  var loaded = false;
  var started = false;
  var waiters = [];

  var pad = function (n) { return String(n).padStart(2, '0'); };
  var toISO = function (d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); };
  var parseISO = function (s) { var p = String(s).split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); };
  var startOfDay = function (d) { return new Date(d.getFullYear(), d.getMonth(), d.getDate()); };
  var addDays = function (d, n) { var x = new Date(d); x.setDate(x.getDate() + n); return x; };
  var sameDay = function (a, b) { return a && b && a.getTime() === b.getTime(); };
  var fmtNice = function (d) { return d.toLocaleDateString('en-US', { month: 'long', day: '2-digit' }); };

  var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var DOW = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

  function isOccupied(date) {
    var t = date.getTime();
    return reserved.some(function (r) { return t >= r.in.getTime() && t < r.out.getTime(); });
  }
  function firstOccupiedAfter(date) {
    var best = null;
    reserved.forEach(function (r) {
      if (r.in.getTime() > date.getTime() && (!best || r.in.getTime() < best.getTime())) { best = r.in; }
    });
    return best;
  }

  function load(ajaxUrl, cb) {
    if (loaded) { if (cb) cb(); return; }
    if (cb) waiters.push(cb);
    if (started) return;
    started = true;
    fetch(ajaxUrl, { method: 'POST', body: new URLSearchParams({ action: 'get_booked_dates' }) })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        if (d && d.success && d.data && d.data.ranges) {
          reserved = d.data.ranges.map(function (x) { return { in: parseISO(x.checkin), out: parseISO(x.checkout) }; });
        }
      })
      .catch(function () { /* non-fatal: server still enforces availability */ })
      .then(function () { loaded = true; waiters.forEach(function (f) { f(); }); waiters = []; });
  }

  // Force a re-fetch (e.g. after a booking is made).
  function refresh(ajaxUrl, cb) {
    loaded = false; started = false; waiters = [];
    load(ajaxUrl, cb);
  }

  /* ---------- the picker instance ---------- */
  function attach(opts) {
    var ci = opts.checkin;
    var co = opts.checkout;
    var onChange = opts.onChange || function () {};
    var today = startOfDay(new Date());

    var selIn = null;
    var selOut = null;
    var hoverDate = null;
    var view = new Date(today.getFullYear(), today.getMonth(), 1);

    // Popover element placed right after whichever field row the inputs share.
    var host = ci.closest('.field-row') || ci.closest('.booking-row') || ci.parentNode;
    var cal = document.createElement('div');
    cal.className = 'sb-cal';
    cal.hidden = true;
    host.insertAdjacentElement('afterend', cal);

    function maxCheckout() { return selIn ? firstOccupiedAfter(selIn) : null; }

    function dayClass(date) {
      var cls = ['sb-day'];
      var disabled = false;
      var choosingOut = selIn && !selOut;
      var cap = choosingOut ? maxCheckout() : null;

      if (date.getTime() < today.getTime()) { cls.push('is-disabled'); disabled = true; }
      else if (isOccupied(date)) { cls.push('is-reserved'); disabled = true; }
      else if (choosingOut) {
        if (date.getTime() <= selIn.getTime()) { cls.push('is-disabled'); disabled = true; }
        else if (cap && date.getTime() > cap.getTime()) { cls.push('is-disabled'); disabled = true; }
      }

      if (sameDay(date, today)) cls.push('is-today');

      // Range highlight (committed range, or live hover preview while choosing).
      var rangeEnd = selOut || (choosingOut && hoverDate && hoverDate > selIn ? hoverDate : null);
      if (selIn && rangeEnd) {
        if (sameDay(date, selIn)) cls.push('is-in');
        if (sameDay(date, rangeEnd)) cls.push('is-out');
        if (date > selIn && date < rangeEnd) cls.push('is-range');
      } else if (sameDay(date, selIn)) {
        cls.push('is-in', 'is-out');
      }
      return { cls: cls.join(' '), disabled: disabled };
    }

    function monthHTML(year, month) {
      var first = new Date(year, month, 1);
      var lead = first.getDay();
      var days = new Date(year, month + 1, 0).getDate();
      var html = '<div class="sb-cal-month"><div class="sb-cal-title">' + MONTHS[month] + ' ' + year + '</div><div class="sb-cal-grid">';
      DOW.forEach(function (d) { html += '<span class="sb-dow">' + d + '</span>'; });
      for (var i = 0; i < lead; i++) html += '<span class="sb-day is-empty"></span>';
      for (var day = 1; day <= days; day++) {
        var date = new Date(year, month, day);
        var info = dayClass(date);
        html += '<button type="button" class="' + info.cls + '"' +
          (info.disabled ? ' disabled' : ' data-date="' + toISO(date) + '"') + '>' + day + '</button>';
      }
      return html + '</div></div>';
    }

    function render() {
      var y1 = view.getFullYear(), m1 = view.getMonth();
      var n = new Date(y1, m1 + 1, 1);
      var prevDisabled = (y1 === today.getFullYear() && m1 === today.getMonth());
      cal.innerHTML =
        '<div class="sb-cal-body">' +
          '<button type="button" class="sb-cal-arrow" data-nav="-1"' + (prevDisabled ? ' disabled' : '') + ' aria-label="Previous">&#8249;</button>' +
          '<div class="sb-cal-months">' + monthHTML(y1, m1) + monthHTML(n.getFullYear(), n.getMonth()) + '</div>' +
          '<button type="button" class="sb-cal-arrow" data-nav="1" aria-label="Next">&#8250;</button>' +
        '</div>' +
        '<div class="sb-cal-foot">' +
          '<span class="sb-cal-legend"><i></i> Reserved (unavailable)</span>' +
          '<span><button type="button" class="sb-cal-clear">Clear</button>' +
          '<button type="button" class="sb-cal-done">Done</button></span>' +
        '</div>';
    }

    function commit() {
      ci.value = selIn ? fmtNice(selIn) : '';
      co.value = selOut ? fmtNice(selOut) : '';
      onChange(selIn, selOut);
    }

    function pick(date) {
      if (!selIn || selOut || date.getTime() <= selIn.getTime()) {
        selIn = date; selOut = null; hoverDate = null;
        commit(); render();
        return;
      }
      var cap = maxCheckout();
      if (cap && date.getTime() > cap.getTime()) { // would span a reserved night
        selIn = date; selOut = null; hoverDate = null;
        commit(); render();
        return;
      }
      selOut = date; hoverDate = null;
      commit(); render();
      // Stay open after completing the range so the user can adjust freely;
      // the calendar closes via the Done button or by clicking outside.
    }

    function open() {
      if (selIn) { view = new Date(selIn.getFullYear(), selIn.getMonth(), 1); }
      render(); cal.hidden = false;
    }
    function close() { cal.hidden = true; }

    /* events */
    cal.addEventListener('click', function (e) {
      // Keep clicks inside the calendar from reaching the document "click
      // outside to close" handler. Without this, re-rendering on a nav/day
      // click detaches the clicked node, the outside-handler no longer finds
      // it inside the calendar, and it wrongly closes — making the arrows and
      // range selection appear broken.
      e.stopPropagation();
      var nav = e.target.closest('[data-nav]');
      if (nav) {
        view.setMonth(view.getMonth() + parseInt(nav.getAttribute('data-nav'), 10));
        render();
        return;
      }
      if (e.target.closest('.sb-cal-clear')) { selIn = null; selOut = null; hoverDate = null; commit(); render(); return; }
      if (e.target.closest('.sb-cal-done')) { close(); return; }
      var d = e.target.closest('.sb-day[data-date]');
      if (d) pick(parseISO(d.getAttribute('data-date')));
    });

    cal.addEventListener('mouseover', function (e) {
      if (!(selIn && !selOut)) return;
      var d = e.target.closest('.sb-day[data-date]');
      if (!d) return;
      var date = parseISO(d.getAttribute('data-date'));
      if (!hoverDate || hoverDate.getTime() !== date.getTime()) { hoverDate = date; render(); }
    });

    function openFrom() { open(); }
    ci.addEventListener('click', openFrom);
    co.addEventListener('click', openFrom);
    ci.addEventListener('focus', openFrom);
    co.addEventListener('focus', openFrom);

    document.addEventListener('click', function (e) {
      if (cal.hidden) return;
      if (cal.contains(e.target) || e.target === ci || e.target === co) return;
      close();
    });

    var api = {
      open: open,
      close: close,
      getRange: function () { return { in: selIn, out: selOut }; },
      setRange: function (inDate, outDate) {
        // Only accept if available and the range doesn't span a reserved night.
        if (inDate && inDate >= today && !isOccupied(inDate)) {
          selIn = startOfDay(inDate);
          var cap = firstOccupiedAfter(selIn);
          if (outDate && outDate > selIn && (!cap || outDate <= cap)) { selOut = startOfDay(outDate); }
          else { selOut = null; }
          view = new Date(selIn.getFullYear(), selIn.getMonth(), 1);
          commit();
        }
      },
      rerender: function () { if (!cal.hidden) render(); }
    };
    return api;
  }

  return {
    attach: attach,
    load: load,
    refresh: refresh,
    isReserved: isOccupied,
    nextReservedAfter: firstOccupiedAfter,
    toISO: toISO,
    parseISO: parseISO,
    addDays: addDays
  };
})();
