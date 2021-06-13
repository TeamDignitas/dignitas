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
      {include "bits/tagAncestors.tpl" tag=$t}
    </h1>

    <form class="form-horizontal mb-5" method="post">
      <input type="hidden" name="id" value="{$t->id}">

      <fieldset class="related-fields mb-5 ms-3">
        {hf inputId='value' label="{t}label-name{/t}"}
          <input type="text"
            class="form-control {if isset($errors.value)}is-invalid{/if}"
            id="value"
            name="value"
            value="{$t->value}">
          {include "bits/fieldErrors.tpl" errors=$errors.value|default:null}
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
          {include "bits/fieldErrors.tpl" errors=$errors.parentId|default:null}
        {/hf}
      </fieldset>

      <fieldset class="related-fields mb-5 ms-3">
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

        {hf inputId='icon' label="{t}label-icon{/t}"}
          <div class="input-group">
            {if $t->icon}
              {include "bits/icon.tpl" i=$t->icon class="input-group-text"}
            {/if}
            <input type="text"
              class="form-control"
              id="icon"
              name="icon"
              value="{$t->icon}">
          </div>

          <small class="form-text text-muted">
            {t}info-tag-icon-name{/t}
          </small>

          {include 'bs/checkbox.tpl'
            checked=$t->iconOnly
            divClass='mt-1'
            label="{t}label-icon-only{/t}"
            name='iconOnly'}

        {/hf}
        </div>
      </fieldset>

      <div class="mt-4 text-end">
        {if $t->isDeletable()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 me-2 mb-2"
            data-confirm="{t}info-confirm-delete-tag{/t}"
            {if !$canDelete}
            disabled
            title="{t}info-cannot-delete-tag{/t}"
            {/if}
          >
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        {if $t->id}
          <button
            name="cloneButton"
            type="submit"
            class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2">
            {include "bits/icon.tpl" i=content_copy}
            {t}link-clone{/t}
          </button>
        {/if}

        <a
          class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2"
          {if $t->id}
          href="{Router::link('tag/view')}/{$t->id}"
          {else}
          href="{Router::link('tag/list')}"
          {/if}
        >
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>
    </form>

    {if count($children)}
      <h3 class="capitalize-first-word mt-5">{t}title-direct-descendants{/t}</h3>

      {foreach $children as $c}
        {include "bits/tag.tpl" t=$c link=true}
      {/foreach}
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
