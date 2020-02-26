<form id="commentForm" class="row mt-1">
  <input type="hidden" name="objectType">
  <input type="hidden" name="objectId">

  <div class="col-md-12">
    <textarea
      name="contents"
      class="form-control"
      rows="2"
      maxlength="{Comment::MAX_LENGTH}"
    ></textarea>
    <small class="form-text text-muted float-left">
      <span class="charsRemaining">{Comment::MAX_LENGTH}</span>
      {t}label-characters-remaining{/t}
    </small>
    {include "bits/markdownHelp.tpl"}
  </div>

  <div class="col-md-12 pl-0">
    <button type="submit" class="btn btn-primary commentSaveButton btn-sm">
      <i class="icon icon-floppy"></i>
      {t}link-save{/t}
    </button>
  </div>
</form>
