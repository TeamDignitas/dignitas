{**
   Mandatory arguments:
   $review, the review being performed;
   $object, the object being reviewed.
  **}
<div class="card bg-light mb-5">
  <div class="card-body pb-1">
    <form method="post">

      <div class="form-group">
        <button
          name="removeButton"
          class="btn btn-sm btn-outline-secondary"
          type="submit">
          <i class="icon icon-cancel"></i>
          {$review->getVoteName(Flag::VOTE_REMOVE)}
        </button>

        <button
          name="keepButton"
          class="btn btn-sm btn-outline-primary"
          type="submit">
          <i class="icon icon-ok"></i>
          {$review->getVoteName(Flag::VOTE_KEEP)}
        </button>

        <a href="{$object->getEditUrl()}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-pencil"></i>
          {t}link-edit{/t}
        </a>

        <button
          name="nextButton"
          type="submit"
          class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-right-open"></i>
          {t}link-next{/t}
        </button>
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
