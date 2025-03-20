$(function() {
  const CL_ACTIVE = 'btn-primary';
  const CL_INACTIVE = 'btn-outline-primary';
  const MINIMUM_AMOUNT = 10;

  function init() {
    $('#donate-btn-once').on('click', selectOnce);
    $('#donate-btn-monthly').on('click', selectMonthly);
    $('.donate-btn-amount').on('click', selectAmount);
  }

  function selectOnce() {
    $('#donate-btn-once')
      .removeClass(CL_INACTIVE)
      .addClass(CL_ACTIVE);
    $('#donate-btn-monthly')
      .removeClass(CL_ACTIVE)
      .addClass(CL_INACTIVE);
    $('#donate-label-monthly').addClass('d-none');
    $('#donate-btn-other-amount').prop('disabled', false);
    updateLink();
  }

  function selectMonthly() {
    $('#donate-btn-once')
      .removeClass(CL_ACTIVE)
      .addClass(CL_INACTIVE);
    $('#donate-btn-monthly')
      .removeClass(CL_INACTIVE)
      .addClass(CL_ACTIVE);
    $('#donate-label-monthly').removeClass('d-none');

    let other = $('#donate-btn-other-amount');
    other.prop('disabled', true);
    if (other.hasClass(CL_ACTIVE)) {
      selectAmountBtn($('.donate-btn-amount[data-default="1"]'));
    }
    updateLink();
  }

  function selectAmountBtn(btn) {
    btn.siblings('.btn')
      .removeClass(CL_ACTIVE)
      .addClass(CL_INACTIVE);
    btn.removeClass(CL_INACTIVE).addClass(CL_ACTIVE);
    $('#donate-amount').text(btn.text());
    updateLink();
  }

  function selectAmount() {
    selectAmountBtn($(this));
  }

  function updateLink() {
    let monthly = $('#donate-btn-monthly').hasClass(CL_ACTIVE);
    let btn = $('.donate-btn-amount.' + CL_ACTIVE);
    let url = monthly ? btn.data('urlMonthly') : btn.data('urlOnce');
    $('#donate-link-stripe').attr('href', url);
  }

  init();
});
