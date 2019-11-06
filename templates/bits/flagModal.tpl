<div id="flagModal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <span data-flag-visibility="{Flag::TYPE_ANSWER}">
            {t}Flag this answer for the following reason:{/t}
          </span>
          <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
            {t}Flag this statement for the following reason:{/t}
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
                value="{Flag::REASON_SPAM}">
              {t}spam{/t}
            </label>
            <p class="text-muted">
              {t}This post appears to promote a product or service.{/t}
            </p>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Flag::REASON_ABUSE}">
              {t}insults or rudeness{/t}
            </label>
            <p class="text-muted">
              {t}This post is insulting or rude toward other users of Dignitas.{/t}
            </p>
          </div>

          <div class="form-check" data-flag-visibility="{Flag::TYPE_STATEMENT}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Flag::REASON_DUPLICATE}"
                data-related="#duplicateSearch"
              >
              {t}duplicate of...{/t}
            </label>
            <p class="text-muted">
              {t}This statement already appears in Dignitas.{/t}
            </p>
            <div class="form-group flagRelated" id="duplicateSearch">
              <select
                id="flagDuplicateId"
                class="form-control"
                name="flagDuplicateId"
                data-placeholder="{t}type in some keywords...{/t}">
              </select>
            </div>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Flag::REASON_OFF_TOPIC}">
              {t}off-topic{/t}
            </label>
            <p class="text-muted">
              <span data-flag-visibility="{Flag::TYPE_ANSWER}">
                {t}This answer does not address the topic of the original statement.{/t}
              </span>
              <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
                {t}This statement is outside the intended scope of Dignitas or
                is too broad to prove or disprove.{/t}
              </span>
            </p>
          </div>

          <div class="form-check" data-flag-visibility="{Flag::TYPE_STATEMENT}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Flag::REASON_UNVERIFIABLE}">
              {t}unverifiable{/t}
            </label>
            <p class="text-muted">
              {t}It is not obvious that the author really made the statement.{/t}
            </p>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Flag::REASON_LOW_QUALITY}">
              {t}low quality{/t}
            </label>
            <p class="text-muted">
              {t}This post has severe formatting or content problems that
              cannot be salvaged through editing.{/t}
            </p>
          </div>

          <div class="form-check">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Flag::REASON_OTHER}"
                data-related="#detailsWrapper"
              >
              {t}other reason{/t}
            </label>
            <p class="text-muted flagRelated" id="detailsWrapper">
              <input
                id="flagDetails"
                class="form-control"
                type="text"
                name="flagDetails"
                placeholder="{t}please provide details...{/t}">
            </p>
          </div>

          {if User::may(User::PRIV_CLOSE_REOPEN_VOTE)}
            <hr>

            <div class="row">
              <div class="col-3">
                <label for="flagProposal" class="col-form-label">{t}I propose{/t}</label>
              </div>
              <div class="col-9">
                <select
                  id="flagProposal"
                  name="flagProposal"
                  class="form-control">
                  <option
                    value="{Flag::PROP_CLOSE}"
                    data-option-visibility="{Flag::TYPE_STATEMENT}">
                    {t}closing this statement{/t}
                  </option>
                  <option
                    value="{Flag::PROP_DELETE}"
                    data-option-visibility="{Flag::TYPE_STATEMENT}">
                    {t}deleting this statement{/t}
                  </option>
                  <option
                    value="{Flag::PROP_DELETE}"
                    data-option-visibility="{Flag::TYPE_ANSWER}">
                    {t}deleting this answer{/t}
                  </option>
                </select>
              </div>
            </div>
          {else}
            <input
              type="hidden"
              id="flagProposal"
              name="flagProposal"
              value="{Flag::PROP_NOTHING}">
          {/if}

        </form>

      </div>

      <div class="modal-footer">
        <div class="text-center">
          <button id="flagButton" type="button" class="btn btn-primary">
            <i class="icon icon-flag"></i>
            <span data-flag-visibility="{Flag::TYPE_ANSWER}">
              {t}flag answer{/t}
            </span>
            <span data-flag-visibility="{Flag::TYPE_STATEMENT}">
              {t}flag statement{/t}
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
