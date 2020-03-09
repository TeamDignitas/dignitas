{* Mandatory argument: $object, the object being reviewed. *}
<div class="card mb-2">
  <div class="card-body pb-1">
    <form method="post">

      <div class="form-group">
        <button
          name="removeButton"
          class="btn btn-info"
          type="submit">
          <i class="icon icon-cancel"></i>
          {t}link-remove{/t}
        </button>

        <button
          name="keepButton"
          class="btn btn-info"
          type="submit">
          <i class="icon icon-ok"></i>
          {t}link-keep{/t}
        </button>

        <a href="{Router::getEditLink($object)}" class="btn btn-info">
          <i class="icon icon-edit"></i>
          {t}link-edit{/t}
        </a>

        <button
          name="nextButton"
          type="submit"
          class="btn btn-info">
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
