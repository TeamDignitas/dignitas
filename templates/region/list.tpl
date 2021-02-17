{extends "layout.tpl"}

{block "title"}{cap}{t}title-regions{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-regions{/t}{/cap}</h1>

    {if User::may(User::PRIV_ADD_TAG)}
      <a class="btn btn-sm btn-primary col-sm-12 col-md-3" href="{Router::link('region/edit')}">
        {include "bits/icon.tpl" i=add_circle}
        {t}link-add-region{/t}
      </a>
    {/if}

    <div id="region-tree" class="voffset3 mt-4">
      {include "bits/regionTree.tpl" link=User::may(User::PRIV_EDIT_TAG)}
    </div>

    {if $maxDepth !== null}
      <h1 class="mb-4">{cap}{t}title-region-nomenclature{/t}{/cap}</h1>

      <form method="post">
        {for $d=0 to $maxDepth}
          {foreach Config::LOCALES as $locale => $localeName}
            <div class="form-group">
              <label>
                {t}info-region-depth{/t} {$d}, {$localeName}
              </label>
              <input
                type="text"
                class="form-control"
                name="name[]"
                value="{$nomenclature[$d][$locale]}"
              >
            </div>
          {/foreach}
        {/for}

        <div class="mt-4 text-right">
          <button
            name="saveButton"
            type="submit"
            class="btn btn-sm btn-outline-primary col-sm-3 col-lg-2 mr-2 mb-2">
            {include "bits/icon.tpl" i=save}
            {t}link-save{/t}
          </button>
        </div>

      </form>
    {/if}
  </div>

{/block}
