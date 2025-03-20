<div class="container my-2">
  <div class="row row-cols-1 row-cols-lg-2 g-3">
    {if Config::DONATE_WIDGET_STRIPE}
      <div class="col">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{cap}{t}title-donate-stripe{/t}{/cap}</h5>
            <p class="card-text">
              <form id="stripe-form">
                <input
                  id="donate-currency"
                  name="currency"
                  type="hidden"
                  value="{Config::DONATE_STRIPE_CURRENCY}">

                <div class="donate-form-row my-2 d-flex">
                  <button
                    class="btn btn-sm btn-primary flex-fill mx-1"
                    id="donate-btn-once"
                    type="button">
                    {t}label-donate-once{/t}
                  </button>
                  <button
                    class="btn btn-sm btn-outline-primary flex-fill mx-1"
                    id="donate-btn-monthly"
                    type="button">
                    {t}label-donate-monthly{/t}
                  </button>
                </div>

                <div class="donate-form-row my-2 d-flex">
                  {foreach Config::DONATE_STRIPE_AMOUNTS as $amt}
                    {$active=($amt==Config::DONATE_STRIPE_DEFAULT_AMOUNT)}
                    {if $active}{$cls="btn-primary"}{else}{$cls="btn-outline-primary"}{/if}
                    <button
                      class="donate-btn-amount btn btn-sm {$cls} flex-fill mx-1"
                      data-amount="{$amt}"
                      type="button">
                      {$amt} {Config::DONATE_STRIPE_CURRENCY}
                    </button>
                  {/foreach}
                  <button
                    class="btn btn-sm btn-outline-primary flex-fill mx-1"
                    id="donate-btn-other-amount"
                    type="button">
                    {t}label-donate-other-amount{/t}
                  </button>

                  <input
                    class="d-none flex-fill form-control form-control-sm mx-1"
                    id="donate-field-amount"
                    min="10"
                    step="10"
                    type="number">

                </div>

                <div class="donate-form-row my-2 d-flex">
                  <a
                    class="btn btn-sm btn-primary flex-fill mx-1"
                    href="https://google.com/"
                    id="donate-link-stripe" >
                    {t}link-donate{/t}
                    <span id="donate-amount">{Config::DONATE_STRIPE_DEFAULT_AMOUNT}</span>
                    {Config::DONATE_STRIPE_CURRENCY}
                    <span id="donate-label-monthly" class="d-none">{t}label-donate-monthly{/t}</span>
                  </a>
                </div>

              </form>
            </p>
          </div>
        </div>
      </div>
    {/if}

    {if Config::DONATE_WIDGET_BANK_TRANSFER}
      <div class="col">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{cap}{t}title-donate-bank-transfer{/t}{/cap}</h5>
            <p class="card-text">
              <dl class="row">
                <dt class="col-12">{Config::DONATE_COMPANY_NAME}</dt>
                <dd></dd>
                <dt class="col-xl-3">{cap}{t}label-donate-fiscal-code{/t}{/cap}:</dt>
                <dd class="col-xl-9">{Config::DONATE_FISCAL_CODE}</dd>
                <dt class="col-xl-3">{cap}{t}label-donate-ron-account{/t}{/cap}:</dt>
                <dd class="col-xl-9">{Config::DONATE_IBAN}</dd>
                <dt class="col-xl-3">{cap}{t}label-donate-account-opened{/t}{/cap}:</dt>
                <dd class="col-xl-9">{Config::DONATE_BANK}</dd>
                <dt class="col-xl-3">{cap}{t}label-donate-swift-code{/t}{/cap}:</dt>
                <dd class="col-xl-9">{Config::DONATE_SWIFT_CODE}</dd>
              </dl>
            </p>
          </div>
        </div>
      </div>
    {/if}
  </div>
</div>
