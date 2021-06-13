{extends "layout.tpl"}

{capture "title"}
  {if $statement->id}
    {t}title-edit-statement{/t}
  {else}
    {t}title-add-statement{/t}
  {/if}
{/capture}

{block "title"}{cap}{$smarty.capture.title}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{$smarty.capture.title}</h1>

    {if !$statement->isEditable()}
      <div class="alert alert-warning">
        {$statement->getEditMessage()}
      </div>
    {/if}

    <form method="post">
      <input type="hidden" name="id" value="{$statement->id}">
      <input type="hidden" name="referrer" value="{$referrer}">

      <fieldset class="related-fields mb-5 ms-3">
        {hf inputId='field-entity-id' label="{t}label-entity{/t}"}
          <select
            name="entityId"
            id="field-entity-id"
            class="form-select {if isset($errors.entityId)}is-invalid{/if}">
            {if $statement->entityId}
              <option value="{$statement->entityId}"></option>
            {/if}
          </select>
          {include "bits/fieldErrors.tpl" errors=$errors.entityId|default:null}
          {if !$statement->entityId}
            <small class="text-muted">
              {t 1=Router::link('entity/edit')}
              info-author-not-found-add-author-%1
              {/t}
            </small>
          {/if}
        {/hf}

        {hf inputId='field-date-made' label="{t}label-statement-date{/t}"}
          <input
            type="text"
            id="field-date-made"
            class="form-control datepicker {if isset($errors.dateMade)}is-invalid{/if}"
            data-allow-partial="false"
            required>
          <input type="hidden" name="dateMade" value="{$statement->dateMade}">
          {include "bits/fieldErrors.tpl" errors=$errors.dateMade|default:null}
        {/hf}

        {hf inputId='field-type' label="{t}label-type{/t}"}
          <select
            id="field-type"
            name="type"
            {if $statement->needsTypeChangeWarning()}
            data-prev-value="{$statement->type}"
            data-confirm="{t}info-confirm-statement-type-change{/t}"
            {/if}
            class="form-select has-unload-warning">
            {for $t = 1 to Statement::NUM_TYPES - 1}
              <option
                value="{$t}"
                {if $t == $statement->type}selected{/if}>
                {Statement::typeName($t)}
              </option>
            {/for}
          </select>
        {/hf}
      </fieldset>

      <fieldset class="related-fields mb-5 ms-3">
        {hf inputId='field-summary' label="{t}label-summary{/t}"}
          <textarea
            name="summary"
            id="field-summary"
            class="form-control single-line size-limit has-unload-warning {if isset($errors.summary)}is-invalid{/if}"
            maxlength="{Statement::MAX_SUMMARY_LENGTH}"
            rows="3"
            required>{$statement->summary|escape}</textarea>

          {include "bits/fieldErrors.tpl" errors=$errors.summary|default:null}
          <span class="chars-remaining form-text small"></span>
        {/hf}

        {hf inputId='field-context' label="{t}label-context{/t}"}
          <textarea
            name="context"
            id="field-context"
            class="form-control has-unload-warning easy-mde {if isset($errors.context)}is-invalid{/if}"
            data-statement-id="{$statement->id}"
            rows="10">{$statement->context|escape}</textarea>
          {include "bits/fieldErrors.tpl" errors=$errors.context|default:null}
          {include "bits/markdownHelp.tpl"}
        {/hf}

        {hf inputId='field-goal' label="{t}label-goal{/t}"}
          <textarea
            name="goal"
            id="field-goal"
            class="form-control single-line size-limit has-unload-warning {if isset($errors.goal)}is-invalid{/if}"
            maxlength="{Statement::MAX_GOAL_LENGTH}"
            rows="3"
            required>{$statement->goal|escape}</textarea>

          {include "bits/fieldErrors.tpl" errors=$errors.goal|default:null}

          <span class="chars-remaining form-text small float-left"></span>
        {/hf}

        {include "bits/linkEditor.tpl"
          labelText="{t}label-statement-links{/t}"
          addButtonText="{t}link-add-statement-link{/t}"
          errors=$errors.links|default:null}
      </fieldset>

      <fieldset class="related-fields mb-5 ms-3">
        {hf inputId='field-involvements' label="{t}label-involvements{/t}"}
          <select
            name="involvedEntityIds[]"
            id="field-involvements"
            class="form-select select2Entities"
            data-placeholder="{t}info-involvements{/t}"
            multiple>
            {foreach $involvedEntityIds as $involvedEntityId}
              <option value="{$involvedEntityId}" selected></option>
            {/foreach}
          </select>
        {/hf}

        {hf label="{t}label-region{/t}"}
          <select name="regionId" class="form-select">
            <option value="0"></option>
            {foreach $regions as $option}
              <option
                value="{$option->id}"
                {if $statement->regionId == $option->id}selected{/if}>
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

        {if User::isModerator()}
          {hf inputId='field-verdict' label="{t}label-verdict{/t}"}
            <select
              id="field-verdict"
              name="verdict"
              class="form-select has-unload-warning {if isset($errors.verdict)}is-invalid{/if}">

              {foreach $statement->getVerdictChoices() as $v}
                <option
                  value="{$v}"
                  {if $v == $statement->verdict}selected{/if}>
                  {Statement::verdictName($v)}
                </option>
              {/foreach}
            </select>

            {include "bits/fieldErrors.tpl" errors=$errors.verdict|default:null}
          {/hf}
        {/if}
      </fieldset>

      <div class="mt-4 text-end">
        {if $statement->isDeletable()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 me-2 mb-2"
            data-confirm="{t}info-confirm-delete-statement{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        {if $statement->isReopenable()}
          <button
            name="reopenButton"
            type="submit"
            class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2"
            data-confirm="{t}info-confirm-reopen-statement{/t}">
            {t}link-reopen{/t}
          </button>
        {/if}

        <a href="{$referrer}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 me-2 mb-2">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
