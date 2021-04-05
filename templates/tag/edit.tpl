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

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="value" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-name{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <input type="text"
              class="form-control {if isset($errors.value)}is-invalid{/if}"
              id="value"
              name="value"
              value="{$t->value}">
            {include "bits/fieldErrors.tpl" errors=$errors.value|default:null}
          </div>
        </div>

        <div class="form-group row">
          <label for="tooltip" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-details{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <input type="text"
              class="form-control"
              id="tooltip"
              name="tooltip"
              value="{$t->tooltip}"
              placeholder="{t}label-optional-tooltip{/t}">
          </div>
        </div>

        <div class="form-group row">
          <label for="parent-id" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-parent-tag{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <select
              id="parent-id"
              name="parentId"
              class="form-control {if isset($errors.parentId)}is-invalid{/if}">
              {if $t->parentId}
                <option value="{$t->parentId}" selected></option>
              {/if}
            </select>
            {include "bits/fieldErrors.tpl" errors=$errors.parentId|default:null}
          </div>
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="color" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-text-color{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div>
              <input type="color"
                class="form-control"
                id="color"
                name="color"
                value="{$t->getColor()}">
            </div>
            {include "bits/frequentColors.tpl"
              colors=$frequentColors.color
              target="#color"}
          </div>
        </div>

        <div class="form-group row">
          <label for="background" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-background-color{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div>
              <input type="color"
                class="form-control"
                id="background"
                name="background"
                value="{$t->getBackground()}">
            </div>
            {include "bits/frequentColors.tpl"
              colors=$frequentColors.background
              target="#background"}
          </div>
        </div>

        <div class="form-group row">
          <label for="icon" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-icon{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div class="input-group">
              {if $t->icon}
                <span class="input-group-prepend">
                  {include "bits/icon.tpl" i=$t->icon class="input-group-text"}
                </span>
              {/if}
              <input type="text"
                class="form-control"
                id="icon"
                name="icon"
                value="{$t->icon}">
            </div>

            <div class="checkbox mt-1">
              <label>
                <input type="checkbox"
                  name="iconOnly"
                  value="1"
                  {if $t->iconOnly}checked{/if}>
                {t}label-icon-only{/t}
              </label>
            </div>

            <small class="form-text text-muted">
              {t}info-tag-icon-name{/t}
            </small>

          </div>
        </div>
      </fieldset>

      <div class="mt-4 text-right">
        {if $t->isDeletable()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2"
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
            class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
            {include "bits/icon.tpl" i=content_copy}
            {t}link-clone{/t}
          </button>
        {/if}

        <a
          class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2"
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
