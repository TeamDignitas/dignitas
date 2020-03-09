{extends "layout.tpl"}

{capture "title"}
{if $entity->id}
  {t}title-edit-entity{/t}
{else}
  {t}title-add-entity{/t}
{/if}
{/capture}

{block "title"}{cap}{$smarty.capture.title}{/cap}{/block}

{block "content"}
  <h3>{$smarty.capture.title}</h3>

  {if !$entity->isEditable()}
    <div class="alert alert-warning">
      {$entity->getEditMessage()}
    </div>
  {/if}

  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{$entity->id}">
    <input type="hidden" name="referrer" value="{$referrer}">
    <div class="form-group">
      <label for="field-name">{t}label-name{/t}</label>
      <input
        name="name"
        value="{$entity->name|escape}"
        id="field-name"
        class="form-control {if isset($errors.name)}is-invalid{/if}">
      {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
    </div>

    <div class="form-group">
      <label>{t}label-alias{/t}</label>

      <table class="table table-sm sortable">
        <thead
          id="alias-header"
          {if empty($aliases)}hidden{/if}>
          <tr>
            <th>{t}label-order{/t}</th>
            <th>{t}label-alias{/t}</th>
            <th>{t}actions{/t}</th>
          </tr>
        </thead>
        <tbody id="alias-container">
          {include "bits/aliasEdit.tpl" id="stem-alias"}
          {foreach $aliases as $a}
            {include "bits/aliasEdit.tpl" alias=$a}
          {/foreach}
        </tbody>
      </table>

      <div>
        <button id="add-alias" class="btn btn-light btn-sm" type="button">
          <i class="icon icon-plus"></i>
          {t}link-add-alias{/t}
        </button>
      </div>
    </div>

    <div class="form-group">
      <label for="field-type">{t}label-type{/t}</label>
      <select
        name="type"
        id="field-type"
        class="form-control {if isset($errors.type)}is-invalid{/if}">
        {foreach Entity::TYPES as $t => $data}
          <option
            value="{$t}"
            data-has-color="{$data.hasColor}"
            {if $entity->type == $t}selected{/if}>
            {Entity::typeName($t)}
          </option>
        {/foreach}
      </select>
      {include "bits/fieldErrors.tpl" errors=$errors.type|default:null}
    </div>

    <div id="color-wrapper"
      class="form-group""
      {if !$entity->hasColor()}hidden{/if}>
      <label for="field-color">{t}label-color{/t}</label>
      <div class="input-group colorpicker-component">
        <span class="input-group-prepend input-group-text colorpicker-input-addon">
          <i></i>
        </span>
        <input type="text"
          class="form-control"
          id="field-color"
          name="color"
          value="{$entity->getColor()}">
      </div>
    </div>

    <div class="form-group">
      <label>{t}label-relations{/t}</label>

      <table class="table table-sm table-rel sortable">
        <thead
          id="relation-header"
          {if empty($relations)}hidden{/if}>
          <tr>
            <th>{t}label-order{/t}</th>
            <th>{t}label-type{/t}</th>
            <th>{t}label-target{/t}</th>
            <th>{t}label-start-date{/t}</th>
            <th>{t}label-end-date{/t}</th>
            <th>{t}actions{/t}</th>
          </tr>
        </thead>
        <tbody id="relation-container">
          {include "bits/relationEdit.tpl" id="stem-relation"}
          {foreach $relations as $r}
            {include "bits/relationEdit.tpl" relation=$r}
          {/foreach}
        </tbody>
      </table>

      {include "bits/fieldErrors.tpl" errors=$errors.relations|default:null}

      <div>
        <button id="add-relation" class="btn btn-light btn-sm" type="button">
          <i class="icon icon-plus"></i>
          {t}label-add-relation{/t}
        </button>
      </div>
    </div>

    <div class="form-group">
      <label for="field-profile">{t}label-profile{/t}</label>
      <textarea
        id="field-profile"
        name="profile"
        class="form-control has-unload-warning size-limit simple-mde"
        maxlength="{Entity::PROFILE_MAX_LENGTH}"
        rows="5">{$entity->profile|escape}</textarea>
      <small class="form-text text-muted float-left">
        <span class="chars-remaining">{$profileCharsRemaining}</span>
        {t}label-characters-remaining{/t}
      </small>
      {include "bits/markdownHelp.tpl"}
      {include "bits/fieldErrors.tpl" errors=$errors.profile|default:null}
    </div>

    {capture "labelText" assign=labelText}{t}label-entity-links{/t}{/capture}
    {capture "addButtonText" assign=addButtonText}{t}link-add-entity-link{/t}{/capture}
    {include "bits/linkEditor.tpl"
      errors=$errors.links|default:null
    }

    <div class="form-group">
      <label>{t}label-tags{/t}</label>

      <select name="tagIds[]" class="form-control select2Tags" multiple>
        {foreach $tagIds as $tagId}
          <option value="{$tagId}" selected></option>
        {/foreach}
      </select>
    </div>

    <div class="row">
      <div class="col">
        <label for="field-image">{t}label-image{/t}</label>

        <div class="custom-file">
          <input
            name="image"
            type="file"
            class="custom-file-input {if isset($errors.image)}is-invalid{/if}"
            id="field-image">
          <label class="custom-file-label" for="field-image">
            {t}info-upload-image{/t}
          </label>
        </div>
        {include "bits/fieldErrors.tpl" errors=$errors.image|default:null}

        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name="deleteImage" class="form-check-input">
            {t}label-delete-image{/t}
          </label>
        </div>
      </div>

      {include "bits/image.tpl"
        obj=$entity
        geometry=Config::THUMB_ENTITY_LARGE
        spanClass="col-3"
        imgClass="pic float-right"}
    </div>

    <hr>

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      <a href="{$referrer}" class="btn btn-link">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $entity->isDeletable()}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right"
          data-confirm="{t}info-confirm-delete-entity{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}
    </div>

  </form>
{/block}
