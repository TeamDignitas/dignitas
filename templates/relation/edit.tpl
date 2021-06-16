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

      {include "bs/actions.tpl"
        cancelLink="{Router::link('entity/view')}/{$fromEntity->id}"}

    </form>
  </div>
{/block}
