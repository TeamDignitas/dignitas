{extends "layout.tpl"}

{block "title"}{cap}{t}edit relation{/t}{/cap}{/block}

{block "content"}
  <h3>{t}edit relation{/t}</h3>

  <h5>{include "bits/entityLink.tpl" e=$fromEntity}</h5>
  <h5>{include "bits/relation.tpl" r=$relation showSourceLink=false}</h5>

  <form method="post">
    <input type="hidden" name="id" value="{$relation->id}">

    {capture "labelText" assign=labelText}{t}source URLs{/t}{/capture}
    {capture "addButtonText" assign=addButtonText}{t}add a source{/t}{/capture}
    {include "bits/urlEditor.tpl"
      items=$sources
      errors=$errors.sources|default:null
    }

    <hr>

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}save{/t}
      </button>

      <a href="{Router::link('entity/view')}/{$fromEntity->id}" class="btn btn-link">
        <i class="icon icon-cancel"></i>
        {t}cancel{/t}
      </a>
    </div>

  </form>
{/block}
