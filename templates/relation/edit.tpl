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

      {capture "labelText" assign=labelText}{t}label-relation-links{/t}{/capture}
      {capture "addButtonText" assign=addButtonText}{t}link-add-relation-link{/t}{/capture}
      {include "bits/linkEditor.tpl" errors=$errors.links|default:null}

      <div class="form-group mt-4 text-right">
        <button name="saveButton" type="submit" class="btn btn-primary">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>

        <a href="{Router::link('entity/view')}/{$fromEntity->id}" class="btn btn-link">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>
      </div>

    </form>
  </div>
{/block}
