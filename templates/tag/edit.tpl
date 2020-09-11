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
            <div>
              <input type="text"
                class="form-control {if isset($errors.value)}is-invalid{/if}"
                id="value"
                name="value"
                value="{$t->value}">
              {include "bits/fieldErrors.tpl" errors=$errors.value|default:null}
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label for="tooltip" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-details{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div>
              <input type="text"
                class="form-control"
                id="tooltip"
                name="tooltip"
                value="{$t->tooltip}"
                placeholder="{t}label-optional-tooltip{/t}">
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label for="parent-id" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-parent-tag{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div>
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
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="color" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-text-color{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div class="input-group colorpicker-component">
              <span class="input-group-prepend input-group-text colorpicker-input-addon">
                <i></i>
              </span>
              <input type="text"
                class="form-control"
                id="color"
                name="color"
                value="{$t->getColor()}">
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label for="background" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-background-color{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div class="input-group colorpicker-component">
              <span class="input-group-prepend input-group-text colorpicker-input-addon">
                <i></i>
              </span>
              <input type="text"
                class="form-control"
                id="background"
                name="background"
                value="{$t->getBackground()}">
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label for="icon" class="col-sm-12 col-lg-2 mt-2 px-0 control-label">
            {t}label-icon{/t}
          </label>
          <div class="col-sm-12 col-lg-10 px-0">
            <div class="input-group">
              {if $t->icon}
                <span class="input-group-prepend input-group-text">
                  <i class="icon icon-{$t->icon}"></i>
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
              {t
                1="https://github.com/TeamDignitas/dignitas/blob/master/www/css/third-party/fontello/css/icons.css"
                2="http://fontello.com/"}
              info-tag-icon-name
              {/t}
            </small>

          </div>
        </div>
      </fieldset>

      <div class="mt-4 row">
        <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary col-sm-3 col-lg-2 mr-2 mb-2">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>

        {if $t->id}
          <a class="btn btn-sm btn-outline-secondary col-sm-3 col-lg-2 mr-2 mb-2" href="{Router::link('tag/view')}/{$t->id}">
            <i class="icon icon-cancel"></i>
            {t}link-cancel{/t}
          </a>
        {/if}

        {if $t->isDeletable()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-3 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-tag{/t}"
            {if !$canDelete}
            disabled
            title="{t}info-cannot-delete-tag{/t}"
            {/if}
          >
            <i class="icon icon-trash"></i>
            {t}link-delete{/t}
          </button>
        {/if}
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

    {* frequent colors to be used by the color pickers *}
    {foreach $frequentColors as $color => $list}
      <div id="frequent-{$color}">
        {foreach $list as $color}
          <div>{$color}</div>
        {/foreach}
      </div>
    {/foreach}
  </div>
{/block}
