(function () {
  'use strict';
  var peso = function (n) { return '₱' + Number(n).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2}); };

  /* mobile menu */
  var toggle = document.querySelector('.menu-toggle'), sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) toggle.addEventListener('click', function () {
    var open = sidebar.classList.toggle('open');
    toggle.setAttribute('aria-expanded', open);
  });

  /* Stock-In / Stock-Out form validation */
  var form = document.querySelector('form[data-validate]');
  if (form) {
    var isOut = form.dataset.validate === 'stock-out';
    var book = form.elements.book_id, qty = form.elements.quantity, hint = document.getElementById('stock-hint'), total = document.getElementById('sale-total');
    var selected = function () { return book.options[book.selectedIndex]; };

    var setError = function (field, text) {
      var out = form.querySelector('[data-error-for="' + field.name + '"]');
      if (out) out.textContent = text || '';
      field.classList.toggle('invalid', !!text);
      field.setAttribute('aria-invalid', text ? 'true' : 'false');
      return !text;
    };
    var refresh = function () {
      var o = selected();
      if (!o || !o.value) { hint.textContent = 'Pick a book to see its current stock.'; if (total) total.textContent = peso(0); return; }
      hint.textContent = 'In stock: ' + o.dataset.stock + ' (minimum ' + o.dataset.min + ')';
      if (isOut) {
        qty.max = o.dataset.stock;
        total.textContent = peso((parseInt(qty.value, 10) || 0) * Number(o.dataset.price));
      }
    };
    var check = function () {
      var ok = true, o = selected(), q = parseInt(qty.value, 10);
      ok = setError(book, book.value ? '' : 'Choose a book.') && ok;
      var qMsg = '';
      if (!qty.value || isNaN(q) || q < 1) qMsg = 'Enter a quantity of 1 or more.';
      else if (isOut && o && o.value && q > Number(o.dataset.stock)) qMsg = 'Only ' + o.dataset.stock + ' in stock. Lower the quantity.';
      ok = setError(qty, qMsg) && ok;
      var cost = form.elements.unit_cost;
      if (cost) ok = setError(cost, Number(cost.value) > 0 ? '' : 'Enter the unit cost per copy.') && ok;
      var date = form.elements.date;
      if (date) ok = setError(date, date.value ? '' : 'Choose a date.') && ok;
      return ok;
    };
    book.addEventListener('change', function () { refresh(); });
    qty.addEventListener('input', function () { refresh(); });
    form.addEventListener('submit', function (e) {
      if (!check()) { e.preventDefault(); var bad = form.querySelector('.invalid'); if (bad) bad.focus(); }
    });
    form.addEventListener('reset', function () {
      setTimeout(function () { form.querySelectorAll('.invalid').forEach(function (f) { setError(f, ''); }); refresh(); }, 0);
    });
    refresh();
  }

  /* Transaction history filters (type + date range) */
  var table = document.getElementById('tx-table');
  if (table) {
    var type = document.getElementById('f-type'), from = document.getElementById('f-from'), to = document.getElementById('f-to');
    var rows = table.querySelectorAll('tbody tr'), count = document.getElementById('tx-count'), empty = document.getElementById('tx-empty');
    var apply = function () {
      var shown = 0;
      rows.forEach(function (r) {
        var ok = (type.value === 'all' || r.dataset.type === type.value) &&
                 (!from.value || r.dataset.date >= from.value) &&
                 (!to.value || r.dataset.date <= to.value);
        r.hidden = !ok; if (ok) shown++;
      });
      count.textContent = 'Showing ' + shown + ' of ' + rows.length + ' transactions';
      empty.hidden = shown !== 0;
      table.style.display = shown ? '' : 'none';
      to.setCustomValidity(from.value && to.value && to.value < from.value ? 'End date is before the start date.' : '');
    };
    [type, from, to].forEach(function (el) { el.addEventListener('input', apply); });
    document.getElementById('f-reset').addEventListener('click', function () { type.value = 'all'; from.value = ''; to.value = ''; apply(); });
    apply();
  }
})();
