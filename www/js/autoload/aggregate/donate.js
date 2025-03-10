$(function() {
  const CL_ACTIVE = 'btn-primary';
  const CL_INACTIVE = 'btn-outline-primary';
  const MINIMUM_AMOUNT = 10;

  function init() {
    preventSubmit();
    $('#donate-btn-once').on('click', selectOnce);
    $('#donate-btn-monthly').on('click', selectMonthly);
    $('.donate-btn-amount').on('click', selectAmount);
    $('#donate-btn-other-amount').on('click', selectOtherAmount);
    $('#donate-field-amount').on('input', amountTyped);
    $('#donate-link-stripe').on('click', stripeLinkClicked);
  }

  // The Stripe donation form is meant as a configurator for a link that we
  // ultimately follow. It is not meant to be submitted.
  function preventSubmit() {
    $('#stripe-form').on('submit', function(e) {
      e.preventDefault();
    });
  }

  function selectOnce() {
    $('#donate-btn-once').removeClass('btn-outline-primary');
    $('#donate-btn-once').addClass('btn-primary');
    $('#donate-btn-monthly').removeClass('btn-primary');
    $('#donate-btn-monthly').addClass('btn-outline-primary');
    $('#donate-label-monthly').addClass('d-none');
  }

  function selectMonthly() {
    $('#donate-btn-once').removeClass('btn-primary');
    $('#donate-btn-once').addClass('btn-outline-primary');
    $('#donate-btn-monthly').removeClass('btn-outline-primary');
    $('#donate-btn-monthly').addClass('btn-primary');
    $('#donate-label-monthly').removeClass('d-none');
  }

  function selectAmount() {
    $(this).siblings('.btn')
      .removeClass(CL_ACTIVE)
      .addClass(CL_INACTIVE);
    $(this).removeClass(CL_INACTIVE).addClass(CL_ACTIVE);
    $('#donate-btn-other-amount').show();
    $('#donate-field-amount').addClass('d-none').val('');
    $('#donate-amount').text($(this).data('amount'));
  }

  function selectOtherAmount() {
    $(this).siblings('.btn')
      .removeClass(CL_ACTIVE)
      .addClass(CL_INACTIVE);
    $(this).removeClass(CL_INACTIVE).addClass(CL_ACTIVE);
    $(this).hide();
    $('#donate-field-amount').removeClass('d-none').focus();
    $('#donate-amount').text('');
  }

  function amountTyped() {
    $('#donate-amount').text($(this).val());
  }

  function stripeLinkClicked() {
    let monthly = $('#donate-btn-monthly').hasClass(CL_ACTIVE);
    let amount = $('#donate-amount').text();
    if (amount < MINIMUM_AMOUNT) {
      let currency = $('#donate-currency').val();
      let msg = _('donate-minimum-amount', MINIMUM_AMOUNT, currency);
      alert(msg);
    } else {
      let msg = 'TODO redirect to Stripe, ' +
          (monthly ? 'monthly, ' : 'one-time, ') +
          amount + ' lei';
      alert(msg);
    }
    return false;
  }

  init();
});
