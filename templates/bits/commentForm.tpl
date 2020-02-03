<form id="commentForm" class="row">
  <input type="hidden" name="objectType">
  <input type="hidden" name="objectId">

  <div class="col-md-9">
    <textarea name="contents" class="form-control" rows="2"></textarea>
    {include "bits/markdownHelp.tpl"}
  </div>

  <div class="col-md-3">
    <button type="submit" class="btn btn-primary commentSaveButton">
      <i class="icon icon-floppy"></i>
      {t}save{/t}
    </button>
  </div>
</form>
