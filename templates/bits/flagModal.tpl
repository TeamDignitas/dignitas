<div id="flagModal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
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
                data-related="#duplicateSearch"
              >
              {t}label-duplicate{/t}
            </label>
            <p class="text-muted" data-flag-visibility="{Flag::TYPE_STATEMENT}">
              {t}info-reason-statement-duplicate{/t}
            </p>
            <p class="text-muted" data-flag-visibility="{Flag::TYPE_ENTITY}">
              {t}info-reason-entity-duplicate{/t}
            </p>
            <div class="form-group flag-related" id="duplicateSearch">
              <select
                id="flagDuplicateId"
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

          <div class="form-check" data-flag-visibility="{Flag::TYPE_STATEMENT}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_UNVERIFIABLE}">
              {t}label-unverifiable{/t}
            </label>
            <p class="text-muted">
              {t}info-reason-unverifiable{/t}
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
                data-related="#detailsWrapper"
              >
              {t}label-other-reason{/t}
            </label>
            <p class="text-muted flag-related" id="detailsWrapper">
              <input
                id="flagDetails"
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
          <button id="flagButton" type="button" class="btn btn-primary">
            <i class="icon icon-flag"></i>
            <span data-flag-visibility="{Flag::TYPE_ANSWER}">
              {t}link-flag-answer{/t}
            </span>
            <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
              {t}link-flag-statement{/t}
            </span>
            <span data-flag-visibility="{Flag::TYPE_ENTITY}">
              {t}link-flag-entity{/t}
            </span>
            <span data-flag-visibility="{Flag::TYPE_COMMENT}">
              {t}link-flag-comment{/t}
            </span>
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

<div id="confirmModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        {* message to be supplied by the backend *}
      </div>
    </div>
  </div>
</div>
