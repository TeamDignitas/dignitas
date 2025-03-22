<div id="donation-widgets" class="container my-2">
  <div class="row row-cols-1 row-cols-lg-2 g-3">
    {if Config::DONATE_WIDGET_STRIPE}
      <div class="col">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{cap}{t}title-donate-stripe{/t}{/cap}</h5>
            <p class="card-text">
              <form>
                {* first row: "once" and "monthly" buttons *}
                <div class="my-2 d-flex">
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

                {* second row: fixed amount buttons plus an "other amount" button *}
                <div class="my-2 d-flex">
                  {foreach Config::DONATE_STRIPE_AMOUNTS as $row}
                    {if $row.default}{$cls="btn-primary"}{else}{$cls="btn-outline-primary"}{/if}
                    <button
                      class="donate-btn-amount btn btn-sm {$cls} flex-fill mx-1"
                      data-default="{$row.default}"
                      data-url-once="{$row.url_once}"
                      data-url-monthly="{$row.url_monthly}"
                      type="button">
                      {$row.amount}
                    </button>
                  {/foreach}

                  <button
                    class="donate-btn-amount btn btn-sm btn-outline-primary flex-fill mx-1"
                    data-url-once="{Config::DONATE_STRIPE_URL_OTHER_AMOUNT}"
                    id="donate-btn-other-amount"
                    type="button">
                    {t}label-donate-other-amount{/t}
                  </button>
                </div>

                {* identify default row *}
                {foreach Config::DONATE_STRIPE_AMOUNTS as $row}
                  {if $row.default}
                    {$defaultRow=$row}
                  {/if}
                {/foreach}

                {* third row: link to Stripe *}
                <div class="my-2 d-flex">
                  <a
                    class="btn btn-sm btn-primary flex-fill mx-1"
                    href="{$defaultRow.url_once}"
                    id="donate-link-stripe"
                    target="_blank">
                    {t}link-donate{/t}
                    <span id="donate-amount">
                      {$defaultRow.amount}
                    </span>
                    <span id="donate-label-monthly" class="d-none">
                      {t}label-donate-monthly{/t}
                    </span>
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
