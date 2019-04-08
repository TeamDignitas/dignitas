{extends "layout.tpl"}

{block "title"}{cap}{t}add a statement{/t}{/cap}{/block}

{block "content"}
  <h3>{t}add a statement{/t}</h3>

  <form method="post">
    <div class="form-group">
      <label for="fieldEntityId">{t}author{/t}</label>
      <input
        name="entityId"
        id="fieldEntityId"
        class="form-control">
    </div>

    <div class="form-group">
      <label for="fieldContents">{t}contents{/t}</label>
      <textarea
        name="contents"
        id="fieldContents"
        class="form-control"
        rows="10"></textarea>
    </div>

    <button name="saveButton" type="submit" class="btn btn-primary">
      <i class="icon icon-floppy"></i>
      {t}save{/t}
    </button>
  </form>
{/block}
