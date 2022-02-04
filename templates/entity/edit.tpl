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
  <div class="container my-5">
    <h1 class="mb-5">{$smarty.capture.title}</h1>

    {if !$entity->isEditable()}
      {notice icon=info}
        {$entity->getEditMessage()}
      {/notice}
    {/if}

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="{$entity->id}">
      <input type="hidden" name="referrer" value="{$referrer}">

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-name' label="{t}label-name{/t}"}
          <input
            name="name"
            value="{$entity->name|esc}"
            id="field-name"
            class="form-control {if isset($errors.name)}is-invalid{/if}">
          {include "bs/feedback.tpl" errors=$errors.name|default:null}
        {/hf}

        {if User::isModerator()}
          {hf inputId='field-long-possessive' label="{t}phrase-long-possessive{/t}"}
            <input
              name="longPossessive"
              value="{$entity->longPossessive|esc}"
              id="field-long-possessive"
              class="form-control"
              placeholder="{t}label-optional{/t}">
          {/hf}

          {hf inputId='field-short-possessive' label="{t}phrase-short-possessive{/t}"}
            <input
              name="shortPossessive"
              value="{$entity->shortPossessive|esc}"
              id="field-short-possessive"
              class="form-control"
              placeholder="{t}label-optional{/t}">
          {/hf}
        {/if}

        {hf label="{t}label-alias{/t}"}
          <button id="add-alias" class="btn btn-outline-secondary btn-sm mb-2" type="button">
            {t}link-add-alias{/t}
          </button>

          <table class="table table-sm sortable">
            <thead
              id="alias-header"
              {if empty($aliases)}hidden{/if}>
              <tr>
                <th class="border-0">{t}label-order{/t}</th>
                <th class="border-0">{t}label-alias{/t}</th>
                <th class="border-0">{t}actions{/t}</th>
              </tr>
            </thead>
            <tbody id="alias-container">
              {include "bits/aliasEdit.tpl" id="stem-alias"}
              {foreach $aliases as $a}
                {include "bits/aliasEdit.tpl" alias=$a}
              {/foreach}
            </tbody>
          </table>
        {/hf}

      </fieldset>

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-entity-type-id' label="{t}label-type{/t}"}
          <select
            name="entityTypeId"
            id="field-entity-type-id"
            class="form-select {if isset($errors.entityTypeId)}is-invalid{/if}"
            data-change-msg="{t}info-change-entity-type-while-relations-exist{/t}">
            {foreach $entityTypes as $et}
              <option
                value="{$et->id}"
                data-has-color="{$et->hasColor}"
                {if $entity->entityTypeId == $et->id}selected{/if}>
                {$et->name|esc}
              </option>
            {/foreach}
          </select>
          {include "bs/feedback.tpl" errors=$errors.type|default:null}
        {/hf}

        <div id="color-wrapper" {if !$entity->hasColor()}hidden{/if}>
          {hf inputId='field-color' label="{t}label-color{/t}"}
            <input type="color"
              class="form-control form-control-color"
              id="field-color"
              name="color"
              value="{$entity->getColor()}">
          {/hf}
        </div>

        {hf label="{t}label-relations{/t}"}
          <button id="add-relation" class="btn btn-outline-secondary btn-sm mb-2" type="button">
            {t}label-add-relation{/t}
          </button>

          <table class="table table-sm table-rel sortable">
            <thead
              id="relation-header"
              {if empty($relations)}hidden{/if}>
              <tr>
                <th class="border-0">{t}label-order{/t}</th>
                <th class="border-0">{t}label-type{/t}</th>
                <th class="border-0">{t}label-target{/t}</th>
                <th class="border-0">{t}label-start-date{/t}</th>
                <th class="border-0">{t}label-end-date{/t}</th>
                <th class="border-0">{t}actions{/t}</th>
              </tr>
            </thead>
            <tbody id="relation-container">
              {$entityTypeId=$entity->getEntityType()->id|default:$entityTypes[0]->id}
              {include "bits/relationEdit.tpl" id="stem-relation"}
              {foreach $relations as $r}
                {include "bits/relationEdit.tpl" relation=$r}
              {/foreach}
            </tbody>
          </table>

          {include "bs/feedback.tpl" errors=$errors.relations|default:null}
        {/hf}
      </fieldset>

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-profile' label="{t}label-profile{/t}"}
          <textarea
            id="field-profile"
            name="profile"
            class="form-control has-unload-warning size-limit easy-mde {if isset($errors.profile)}is-invalid{/if}"
            maxlength="{Entity::PROFILE_MAX_LENGTH}"
            rows="5">{$entity->profile|esc}</textarea>

          {include "bs/feedback.tpl" errors=$errors.profile|default:null}
          <div class="d-flex flex-column flex-md-row justify-content-between">
            <span class="chars-remaining form-text"></span>
            {include "bits/markdownHelp.tpl"}
          </div>
        {/hf}

        {include "bits/linkEditor.tpl"
          labelText="{t}label-entity-links{/t}"
          addButtonText="{t}link-add-entity-link{/t}"
          errors=$errors.links|default:null}

      </fieldset>

      <fieldset class="mb-5 ms-3">
        {hf label="{t}label-region{/t}"}
          <select name="regionId" class="form-select">
            <option value="0"></option>
            {foreach $regions as $option}
              <option
                value="{$option->id}"
                {if $entity->regionId == $option->id}selected{/if}>
                {$option->name}
              </option>
            {/foreach}
          </select>
        {/hf}

        {hf label="{t}label-tags{/t}"}
          <select name="tagIds[]" class="form-select select2Tags" multiple>
            {foreach $tagIds as $tagId}
              <option value="{$tagId}" selected></option>
            {/foreach}
          </select>
        {/hf}

        {hf inputId='field-image' label="{t}label-image{/t}"}
          <input
            class="form-control {if isset($errors.image)}is-invalid{/if}"
            data-bs-toggle="tooltip"
            id="field-image"
            name="image"
            title="{t}tooltip-upload-entity-image{/t}"
            type="file">
          {include "bs/feedback.tpl" errors=$errors.image|default:null}

          {include 'bs/checkbox.tpl'
            divClass='mt-1'
            label="{t}label-delete-image{/t}"
            name='deleteImage'}

          {include "bits/image.tpl"
            obj=$entity
            geometry=Config::THUMB_ENTITY_LARGE
            spanClass="col-3"
            imgClass="rounded-circle float-end"}
        {/hf}
      </fieldset>

      {include "bs/actions.tpl"
        cancelLink=$referrer
        deleteButton=$entity->isDeletable()
        deleteButtonConfirm="{t}info-confirm-delete-entity{/t}"
        reopenButton=$entity->isReopenable()
        reopenButtonConfirm="{t}info-confirm-reopen-entity{/t}"}

    </form>
  </div>
{/block}
