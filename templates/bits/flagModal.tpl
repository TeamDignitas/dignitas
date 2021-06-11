<div id="modal-flag" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <span data-flag-visibility="{Flag::TYPE_ANSWER}">
            {t}title-flag-answer{/t}
          </span>
          <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
            {t}title-flag-statement{/t}
          </span>
          <span data-flag-visibility="{Flag::TYPE_ENTITY}">
            {t}title-flag-entity{/t}
          </span>
          <span data-flag-visibility="{Flag::TYPE_COMMENT}">
            {t}title-flag-comment{/t}
          </span>
        </h5>
      </div>

      <div class="modal-body">
        <form method="post" role="form">
          <div class="form-check">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_SPAM}">
              {t}label-spam{/t}
            </label>
            <p class="text-muted">
              {t}info-reason-spam{/t}
            </p>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_ABUSE}">
              {t}label-abuse{/t}
            </label>
            <p class="text-muted">
              {t}info-reason-abuse{/t}
            </p>
          </div>

          <div
            class="form-check"
            data-flag-visibility="{Flag::TYPE_STATEMENT} {Flag::TYPE_ENTITY}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_DUPLICATE}"
                data-related="#duplicate-search"
              >
              {t}label-duplicate{/t}
            </label>
            <p class="text-muted" data-flag-visibility="{Flag::TYPE_STATEMENT}">
              {t}info-reason-statement-duplicate{/t}
            </p>
            <p class="text-muted" data-flag-visibility="{Flag::TYPE_ENTITY}">
              {t}info-reason-entity-duplicate{/t}
            </p>
            <div class="form-group flag-related" id="duplicate-search">
              <select
                id="flag-duplicate-id"
                class="form-control"
                name="flagDuplicateId"
                data-placeholder="{t}label-type-something{/t}">
              </select>
            </div>
          </div>

          <div
            class="form-check"
            data-flag-visibility="{Flag::TYPE_ANSWER} {Flag::TYPE_STATEMENT} {Flag::TYPE_ENTITY}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_OFF_TOPIC}">
              {t}label-off-topic{/t}
            </label>
            <p class="text-muted">
              <span data-flag-visibility="{Flag::TYPE_ANSWER}">
                {t}info-reason-asnwer-off-topic{/t}
              </span>
              <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
                {t}info-reason-statement-off-topic{/t}
              </span>
              <span data-flag-visibility="{Flag::TYPE_ENTITY}">
                {t}info-reason-entity-off-topic{/t}
              </span>
            </p>
          </div>

          <div class="form-check" data-flag-visibility="{Flag::TYPE_STATEMENT} {Flag::TYPE_ENTITY}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_UNVERIFIABLE}">
              <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
                {t}label-statement-unverifiable{/t}
              </span>
              <span data-flag-visibility="{Flag::TYPE_ENTITY}">
                {t}label-entity-unverifiable{/t}
              </span>
            </label>
            <p class="text-muted">
              <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
                {t}info-reason-statement-unverifiable{/t}
              </span>
              <span data-flag-visibility="{Flag::TYPE_ENTITY}">
                {t}info-reason-entity-unverifiable{/t}
              </span>
            </p>
          </div>

          <div
            class="form-check"
            data-flag-visibility="{Flag::TYPE_STATEMENT} {Flag::TYPE_ANSWER}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_LOW_QUALITY}">
              {t}label-low-quality{/t}
            </label>
            <p class="text-muted">
              {t}info-reason-low-quality{/t}
            </p>
          </div>

          <div
            class="form-check"
            data-flag-visibility="{Flag::TYPE_COMMENT}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_NOT_NEEDED}">
              {t}label-not-needed{/t}
            </label>
            <p class="text-muted">
              {t}info-reason-not-needed{/t}
            </p>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_OTHER}"
                data-related="#details-wrapper"
              >
              {t}label-other-reason{/t}
            </label>
            <p class="text-muted flag-related" id="details-wrapper">
              <input
                id="flag-details"
                class="form-control"
                type="text"
                name="flagDetails"
                placeholder="{t}label-provide-details{/t}">
            </p>
          </div>

        </form>

      </div>

      <div class="modal-footer">
        <div class="text-center">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
            {include "bits/icon.tpl" i=cancel}
            {t}link-cancel{/t}
          </button>
          <button id="button-flag" type="button" class="btn btn-sm btn-primary">
            {include "bits/icon.tpl" i=flag}
            {t}link-flag{/t}
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

<div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        {* message to be supplied by the backend *}
      </div>
    </div>
  </div>
</div>
