{* Mandatory argument: $object, the object being reviewed. *}
<div id="reviewActions" class="card">
  <div class="card-body">
    <form>

      <a href="{Router::getEditLink($object)}" class="btn btn-light">
        <i class="icon icon-edit"></i>
        {t}edit{/t}
      </a>

      {$disabledDone=!$object->getVote() && !$object->isFlagged()}
      <button
        id="doneButton"
        name="doneButton"
        type="submit"
        class="btn btn-success"
        {if $disabledDone}disabled{/if}
      >
        <i class="icon icon-ok"></i>
        {t}I'm done{/t}
      </button>

    </form>
  </div>
</div>
