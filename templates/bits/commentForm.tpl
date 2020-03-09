<form id="form-comment" class="mt-1">
  <input type="hidden" name="objectType">
  <input type="hidden" name="objectId">

  <div class="form-group mb-2">
    <textarea
      name="contents"
      class="form-control size-limit"
      rows="2"
      maxlength="{Comment::MAX_LENGTH}"
    ></textarea>
    <small class="form-text text-muted float-left">
      <span class="chars-remaining">{Comment::MAX_LENGTH}</span>
      {t}label-characters-remaining{/t}
    </small>
    {include "bits/markdownHelp.tpl"}
  </div>

  <div class="text-right answer-buttons mb-2">
    <button type="submit" class="btn btn-sm btn-outline-primary comment-save">
      <i class="icon icon-floppy"></i>
      {t}link-save{/t}
    </button>

    <button type="button" class="btn btn-sm btn-outline-secondary comment-cancel">
      <i class="icon icon-cancel"></i>
      {t}link-cancel{/t}
    </button>
  </div>
</form>
