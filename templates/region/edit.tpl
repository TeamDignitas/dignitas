{extends "layout.tpl"}

{capture "title"}
  {if $r->id}
    {t}title-edit-region{/t}
  {else}
    {t}title-add-region{/t}
  {/if}
{/capture}

{block "title"}{cap}{$smarty.capture.title}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{$smarty.capture.title}
      {include "bits/regionAncestors.tpl" region=$r}
    </h1>

    <form class="mb-5" method="post">
      <input type="hidden" name="id" value="{$r->id}">

      {hf inputId='name' label="{t}label-name{/t}"}
        <input type="text"
          class="form-control {if isset($errors.name)}is-invalid{/if}"
          id="name"
          name="name"
          value="{$r->name}"
          autofocus>
        {include "bs/feedback.tpl" errors=$errors.name|default:null}
      {/hf}

      {hf inputId='parent-id' label="{t}label-parent-region{/t}"}
        <select
          id="parent-id"
          name="parentId"
          class="form-select {if isset($errors.parentId)}is-invalid{/if}">
          <option value="0"></option>
          {foreach $regions as $option}
            <option
              value="{$option->id}"
              {if $r->parentId == $option->id}selected{/if}>
              {$option->name}
            </option>
          {/foreach}
        </select>
        {include "bs/feedback.tpl" errors=$errors.parentId|default:null}
      {/hf}

      <div class="mt-4 text-end">

        {if $r->isDeletable()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-3 col-lg-2 me-2 mb-2"
            data-confirm="{t}info-confirm-delete-region{/t}"
            {if !$canDelete}
            disabled
            title="{t}info-cannot-delete-region{/t}"
            {/if}
          >
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a
          class="btn btn-sm btn-outline-secondary col-sm-3 col-lg-2 me-2 mb-2"
          {if $r->id}
          href="{Router::link('region/view')}/{$r->id}"
          {else}
          href="{Router::link('region/list')}"
          {/if}
        >
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button
          name="saveButton"
          type="submit"
          class="btn btn-sm btn-primary col-sm-3 col-lg-2 me-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>
    </form>

    {if count($children)}
      <h3 class="capitalize-first-word mt-5">{t}title-direct-descendants{/t}</h3>

      {foreach $children as $c}
        <div>
          {include "bits/region.tpl" r=$c link=true}
        </div>
      {/foreach}
    {/if}

    {if count($homonyms)}
      <h3>{t}title-duplicate-regions{/t}</h3>

      {foreach $homonyms as $h}
        <div>
          {include "bits/regionAncestors.tpl" region=$h link=true}
        </div>
      {/foreach}
    {/if}

  </div>
{/block}
