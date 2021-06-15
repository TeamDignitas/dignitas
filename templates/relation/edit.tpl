{extends "layout.tpl"}

{block "title"}{cap}{t}title-edit-relation{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-relation{/t}</h1>

    <h5 class="relation-source mb-3">
      <span>{include "bits/entityLink.tpl" e=$fromEntity}</span>
      {include "bits/relation.tpl" r=$relation showEditLink=false class="d-inline"}
    </h5>

    <form class="my-5 mx-3" method="post">
      <input type="hidden" name="id" value="{$relation->id}">

      <div class="link-editor-wrapper">
        {include "bits/linkEditor.tpl"
          addButtonText="{t}link-add-relation-link{/t}"
          labelText="{t}label-relation-links{/t}"
          errors=$errors.links|default:null}
      </div>

      <div class="mt-4 text-end">
        <a href="{Router::link('entity/view')}/{$fromEntity->id}"
          class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>

    </form>
  </div>
{/block}
