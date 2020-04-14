{extends "layout.tpl"}

{block "title"}{cap}{t}title-edit-relation{/t}{/cap}{/block}

{block "content"}
  <h3>{t}title-edit-relation{/t}</h3>

  <h5>{include "bits/entityLink.tpl" e=$fromEntity}</h5>
  <h5>{include "bits/relation.tpl" r=$relation showEditLink=false}</h5>

  <form method="post">
    <input type="hidden" name="id" value="{$relation->id}">

    {capture "labelText" assign=labelText}{t}label-relation-links{/t}{/capture}
    {capture "addButtonText" assign=addButtonText}{t}link-add-relation-link{/t}{/capture}
    {include "bits/linkEditor.tpl" errors=$errors.links|default:null}

    <hr>

    <div>
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
{/block}
