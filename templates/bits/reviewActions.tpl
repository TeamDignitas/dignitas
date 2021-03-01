{**
   Mandatory arguments:
   $review, the review being performed;
   $object, the object being reviewed.
  **}
<div class="card bg-light mb-5">
  <div class="card-body pb-1">
    <form method="post">

      <div class="d-flex flex-sm-row flex-column">
        <button
          name="removeButton"
          class="btn btn-sm btn-outline-danger mr-2 mb-2"
          {if User::isModerator()}
          data-confirm="{t}info-confirm-moderator-review-remove{/t}"
          {/if}
          type="submit">
          {include "bits/icon.tpl" i=cancel}
          {$review->getVoteName(Flag::VOTE_REMOVE)}
        </button>

        <button
          name="keepButton"
          class="btn btn-sm btn-primary mr-2 mb-2"
          type="submit"
          data-toggle="tooltip"
          title="{t}tooltip-review-keep{/t}">
          {include "bits/icon.tpl" i=done}
          {$review->getVoteName(Flag::VOTE_KEEP)}
        </button>

        {* fit all these on one row on narrow displays *}
        <div class="flex-grow-1 d-flex">
          <button
            class="btn btn-sm mb-2 flex-shrink-1"
            type="button"
            id="review-ellipsis"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            {include "bits/icon.tpl" i=more_vert}
          </button>

          <div class="dropdown-menu ellipsis-menu" aria-labelledby="review-ellipsis">
            {if $object->getEditUrl()} {* comments aren't editable and have no history *}
              {include "bits/editButton.tpl" obj=$object class="dropdown-item"}
              {include "bits/historyButton.tpl" obj=$object class="dropdown-item"}
            {/if}
            {include "bits/flagLinks.tpl" obj=$object class="dropdown-item"}
          </div>

          <div class="flex-grow-1"></div>

          <button
            name="nextButton"
            type="submit"
            class="btn btn-sm btn-outline-secondary mr-2 mb-2 flex-shrink-1"
            data-toggle="tooltip"
            title="{t}tooltip-review-next{/t}"
          >
            {include "bits/icon.tpl" i=chevron_right}
          </button>
        </div>
      </div>

      <div class="form-group">
        <input
          type="text"
          name="details"
          value="{$details|default:''|escape}"
          class="form-control"
          placeholder="{t}label-details-optional{/t}">
      </div>

    </form>
  </div>
</div>
