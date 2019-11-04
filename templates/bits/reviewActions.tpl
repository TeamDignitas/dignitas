{* Mandatory argument: $object, the object being reviewed. *}
<div id="reviewActions" class="card">
  <div class="card-body">
    <form>

      <button
        name="looksOkButton"
        class="btn btn-info"
        type="submit">
        <i class="icon icon-thumbs-up-alt"></i>
        {t}looks OK{/t}
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

    </form>
  </div>
</div>
