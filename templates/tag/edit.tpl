{extends "layout.tpl"}

{capture "title"}
  {if $t->id}
    {t}title-edit-tag{/t}
  {else}
    {t}title-add-tag{/t}
  {/if}
{/capture}

{block "title"}{cap}{$smarty.capture.title}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{$smarty.capture.title}
      {if $t->id}
        {include "bits/tagAncestors.tpl" tag=$t}
      {/if}
    </h1>

    <form class="mb-5" method="post">
      <input type="hidden" name="id" value="{$t->id}">

      <fieldset class="mb-5 ms-3">
        {hf inputId='value' label="{t}label-name{/t}" required=true}
          <input type="text"
            class="form-control {if isset($errors.value)}is-invalid{/if}"
            id="value"
            name="value"
            value="{$t->value}">
          {include "bs/feedback.tpl" errors=$errors.value|default:null}
        {/hf}

        {hf inputId='tooltip' label="{t}label-details{/t}"}
          <input type="text"
            class="form-control"
            id="tooltip"
            name="tooltip"
            value="{$t->tooltip}"
            placeholder="{t}label-optional-tooltip{/t}">
        {/hf}

        {hf inputId='parent-id' label="{t}label-parent-tag{/t}"}
          <select
            id="parent-id"
            name="parentId"
            class="form-select {if isset($errors.parentId)}is-invalid{/if}">
            {if $t->parentId}
              <option value="{$t->parentId}" selected></option>
            {/if}
          </select>
          {include "bs/feedback.tpl" errors=$errors.parentId|default:null}
        {/hf}
      </fieldset>

      <fieldset class="mb-5 ms-3">
        {hf inputId='color' label="{t}label-text-color{/t}"}
          <input type="color"
            class="form-control form-control-color"
            id="color"
            name="color"
            value="{$t->getColor()}">
          {include "bits/frequentColors.tpl"
            colors=$frequentColors.color
            target="#color"}
        {/hf}

        {hf inputId='background' label="{t}label-background-color{/t}"}
          <input type="color"
            class="form-control form-control-color"
            id="background"
            name="background"
            value="{$t->getBackground()}">
          {include "bits/frequentColors.tpl"
            colors=$frequentColors.background
            target="#background"}
        {/hf}

        {hf label="{t}label-icon{/t}"}
          {include "bs/iconField.tpl"
            icon=$t->icon
            mb=2
            name='icon'
            value=$t->icon}

          <div class="form-text">
            {t}info-tag-icon-name{/t}
          </div>

          {include 'bs/checkbox.tpl'
            checked=$t->iconOnly
            divClass='mt-1'
            label="{t}label-icon-only{/t}"
            name='iconOnly'}

        {/hf}
      </fieldset>

      {capture 'cancelLink'}
        {if $t->id}
          {$t->getViewUrl()}
        {else}
          {Router::link('tag/list')}
        {/if}
      {/capture}
      {include "bs/actions.tpl"
        cancelLink=$smarty.capture.cancelLink
        cloneButton=$t->id
        deleteButton=$t->isDeletable()
        deleteButtonConfirm="{t}info-confirm-delete-canned-response{/t}"
        deleteButtonDisabled=!$canDelete
        deleteButtonTitle="{t}info-cannot-delete-tag{/t}"}

    </form>

    {if count($t->children)}
      <h3 class="capitalize-first-word mt-5">{t}title-descendants{/t}</h3>

      <div id="tag-tree" class="mt-4">
        {include "bits/tagTree.tpl" tags=$t->children link=true}
      </div>
    {/if}

    {if count($homonyms)}
      <h3>{t}title-duplicate-tags{/t}</h3>

      {foreach $homonyms as $h}
        <div>
          {include "bits/tagAncestors.tpl" tag=$h}
        </div>
      {/foreach}
    {/if}
  </div>
{/block}
