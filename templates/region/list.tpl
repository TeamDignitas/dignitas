{extends "layout.tpl"}

{block "title"}{cap}{t}title-regions{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-regions{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('region/edit')}">
      {include "bits/icon.tpl" i=add_circle}
      {t}link-add-region{/t}
    </a>

    <div id="region-tree" class="mt-4">
      {include "bits/regionTree.tpl"}
    </div>

    {if $maxDepth !== null}
      <h1 class="mb-4">{cap}{t}title-region-nomenclature{/t}{/cap}</h1>

      <form method="post">
        {for $d=0 to $maxDepth}
          {foreach Config::LOCALES as $locale => $localeName}
            {capture 'label'}{t}info-region-depth{/t} {$d}, {$localeName}{/capture}
            {field label=$smarty.capture.label}
              <input
                type="text"
                class="form-control"
                name="name[]"
                value="{$nomenclature[$d][$locale]}">
            {/field}
          {/foreach}
        {/for}

        {include "bs/actions.tpl"
          cancelLink=false
          saveButtonClass='btn-outline-primary'}

      </form>
    {/if}
  </div>

{/block}
