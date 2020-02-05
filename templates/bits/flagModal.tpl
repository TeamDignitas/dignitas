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
          <span data-flag-visibility="{Flag::TYPE_ENTITY}">
            {t}Flag this author for the following reason:{/t}
          </span>
          <span data-flag-visibility="{Flag::TYPE_COMMENT}">
            {t}Flag this comment for the following reason:{/t}
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
                value="{Ct::REASON_ABUSE}">
              {t}insults or rudeness{/t}
            </label>
            <p class="text-muted">
              {t}This post is insulting or rude toward other users of Dignitas.{/t}
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
              {t}duplicate of...{/t}
            </label>
            <p class="text-muted" data-flag-visibility="{Flag::TYPE_STATEMENT}">
              {t}This statement already appears in Dignitas.{/t}
            </p>
            <p class="text-muted" data-flag-visibility="{Flag::TYPE_ENTITY}">
              {t}This entity already appears in Dignitas.{/t}
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

          <div
            class="form-check"
            data-flag-visibility="{Flag::TYPE_ANSWER} {Flag::TYPE_STATEMENT} {Flag::TYPE_ENTITY}">
            <label class="form-check-label">
              <input
                class="form-check-input"
                type="radio"
                name="flagReason"
                value="{Ct::REASON_OFF_TOPIC}">
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
              <span data-flag-visibility="{Flag::TYPE_ENTITY}">
                {t}Dignitas does not concern itself with this author's statements.{/t}
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
              {t}unverifiable{/t}
            </label>
            <p class="text-muted">
              {t}It is not obvious that the author really made the statement.{/t}
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
              {t}low quality{/t}
            </label>
            <p class="text-muted">
              {t}This post has severe formatting or content problems that
              cannot be salvaged through editing.{/t}
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
              {t}no longer needed{/t}
            </label>
            <p class="text-muted">
              {t}This comment has been incorporated in the post, is outdated
              or not relevant to this post.{/t}
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
            <span data-flag-visibility="{Flag::TYPE_ENTITY}">
              {t}flag author{/t}
            </span>
            <span data-flag-visibility="{Flag::TYPE_COMMENT}">
              {t}flag comment{/t}
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
