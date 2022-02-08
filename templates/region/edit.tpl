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
    <h1 class="mb-5">
      {$smarty.capture.title}
      {if $r->id}
        {include "bits/regionAncestors.tpl" region=$r}
      {/if}
    </h1>

    <form class="mb-5" method="post">
      <input type="hidden" name="id" value="{$r->id}">

      {hf inputId='name' label="{t}label-name{/t}" required=true}
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

      {capture 'cancelLink'}
        {if $r->id}
          {$r->getViewUrl()}
        {else}
          {Router::link('region/list')}
        {/if}
      {/capture}
      {include "bs/actions.tpl"
        cancelLink=$smarty.capture.cancelLink
        deleteButton=$r->isDeletable()
        deleteButtonConfirm="{t}info-confirm-delete-region{/t}"
        deleteButtonDisabled=!$canDelete
        deleteButtonTitle="{t}info-cannot-delete-region{/t}"}

    </form>

    {if count($children)}
      <h3 class="capitalize-first-word mt-5">{t}title-direct-descendants{/t}</h3>

      {foreach $children as $c}
        <div>
          {include "bits/region.tpl" r=$c}
        </div>
      {/foreach}
    {/if}

    {if count($homonyms)}
      <h3>{t}title-duplicate-regions{/t}</h3>

      {foreach $homonyms as $h}
        <div>
          {include "bits/regionAncestors.tpl" region=$h}
        </div>
      {/foreach}
    {/if}

  </div>
{/block}
