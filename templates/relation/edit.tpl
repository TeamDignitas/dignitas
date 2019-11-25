{extends "layout.tpl"}

{block "title"}{cap}{t}edit relation{/t}{/cap}{/block}

{block "content"}
  <h3>{t}edit relation{/t}</h3>

  <h5>{include "bits/entityLink.tpl" e=$fromEntity}</h5>
  <h5>{include "bits/relation.tpl" r=$relation showSourceLink=false}</h5>

  <form method="post">
    <input type="hidden" name="id" value="{$relation->id}">

    <div class="form-group">
      <label>{t}source URLs{/t}</label>

      <table class="table table-sm">
        <tbody id="sourceContainer">
          {include "bits/sourceEdit.tpl" id="stem"}
          {foreach $sources as $s}
            {include "bits/sourceEdit.tpl" source=$s}
          {/foreach}
        </tbody>
      </table>

      {include "bits/fieldErrors.tpl" errors=$errors.sources|default:null}

      <div>
        <button id="addSourceButton" class="btn btn-light btn-sm" type="button">
          <i class="icon icon-plus"></i>
          {t}add a source{/t}
        </button>
      </div>
    </div>

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
