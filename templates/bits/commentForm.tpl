<form id="commentForm" class="row">
  <input type="hidden" name="objectType">
  <input type="hidden" name="objectId">

  <div class="col-md-9">
    <textarea
      name="contents"
      class="form-control"
      rows="2"
      maxlength="{Comment::MAX_LENGTH}"
    ></textarea>
    <small class="form-text text-muted float-left">
      <span class="charsRemaining">{Comment::MAX_LENGTH}</span>
      {t}characters remaining{/t}
    </small>
    {include "bits/markdownHelp.tpl"}
  </div>

  <div class="col-md-3">
    <button type="submit" class="btn btn-primary commentSaveButton">
      <i class="icon icon-floppy"></i>
      {t}save{/t}
    </button>
  </div>
</form>
