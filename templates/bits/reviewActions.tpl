{* Mandatory argument: $object, the object being reviewed. *}
<div id="reviewActions" class="card">
  <div class="card-body pb-1">
    <form method="post">

      <div class="form-group">
        <button
          name="yeaButton"
          class="btn btn-info"
          type="submit">
          <i class="icon icon-ok"></i>
          {t}yea{/t}
        </button>

        <button
          name="nayButton"
          class="btn btn-info"
          type="submit">
          <i class="icon icon-cancel"></i>
          {t}nay{/t}
        </button>

        <a href="{Router::getEditLink($object)}" class="btn btn-info">
          <i class="icon icon-edit"></i>
          {t}edit{/t}
        </a>

        <button
          id="nextButton"
          name="nextButton"
          type="submit"
          class="btn btn-info">
          <i class="icon icon-right-open"></i>
          {t}next{/t}
        </button>
      </div>

      {if $review->reason == Review::REASON_OTHER}
        <div class="form-group">
          <input
            type="text"
            name="details"
            value="{$details|default:''|escape}"
            class="form-control"
            placeholder="{t}details (optional){/t}">
        </div>
      {/if}

    </form>
  </div>
</div>
