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
      {notice icon=info}
        {$statement->getEditMessage()}
      {/notice}
    {/if}

    <form method="post">
      <input type="hidden" name="id" value="{$statement->id}">
      <input type="hidden" name="referrer" value="{$referrer}">

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-entity-id' label="{t}label-entity{/t}" required=true}
          <select
            name="entityId"
            id="field-entity-id"
            class="form-select {if isset($errors.entityId)}is-invalid{/if}">
            {if $statement->entityId}
              <option value="{$statement->entityId}"></option>
            {/if}
          </select>
          {include "bs/feedback.tpl" errors=$errors.entityId|default:null}
          {if !$statement->entityId}
            <small class="text-muted">
              {t 1=Router::link('entity/edit')}
              info-author-not-found-add-author-%1
              {/t}
            </small>
          {/if}
        {/hf}

        {hf inputId='field-date-made' label="{t}label-statement-date{/t}" required=true}
          <input
            type="text"
            id="field-date-made"
            class="form-control datepicker {if isset($errors.dateMade)}is-invalid{/if}"
            data-allow-partial="false"
            required>
          <input type="hidden" name="dateMade" value="{$statement->dateMade}">
          {include "bs/feedback.tpl" errors=$errors.dateMade|default:null}
        {/hf}

        {hf inputId='field-type' label="{t}label-type{/t}" required=true}
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

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-summary' label="{t}label-summary{/t}" required=true}
          <textarea
            name="summary"
            id="field-summary"
            class="form-control single-line size-limit has-unload-warning {if isset($errors.summary)}is-invalid{/if}"
            maxlength="{Statement::MAX_SUMMARY_LENGTH}"
            rows="3"
            required>{$statement->summary|esc}</textarea>

          {include "bs/feedback.tpl" errors=$errors.summary|default:null}
          <span class="chars-remaining form-text"></span>
        {/hf}

        {hf inputId='field-context' label="{t}label-context{/t}" required=true}
          <textarea
            name="context"
            id="field-context"
            class="form-control has-unload-warning easy-mde {if isset($errors.context)}is-invalid{/if}"
            data-statement-id="{$statement->id}"
            rows="10">{$statement->context|esc}</textarea>
          {include "bs/feedback.tpl" errors=$errors.context|default:null}
          {include "bits/markdownHelp.tpl"}
        {/hf}

        {hf inputId='field-goal' label="{t}label-goal{/t}" required=true}
          <textarea
            name="goal"
            id="field-goal"
            class="form-control single-line size-limit has-unload-warning {if isset($errors.goal)}is-invalid{/if}"
            maxlength="{Statement::MAX_GOAL_LENGTH}"
            rows="3"
            required>{$statement->goal|esc}</textarea>

          {include "bs/feedback.tpl" errors=$errors.goal|default:null}

          <span class="chars-remaining form-text"></span>
        {/hf}

        {include "bits/linkEditor.tpl"
          labelText="{t}label-statement-links{/t}"
          addButtonText="{t}link-add-statement-link{/t}"
          errors=$errors.links|default:null}
      </fieldset>

      <fieldset class="mb-5 ms-3">
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

            {include "bs/feedback.tpl" errors=$errors.verdict|default:null}
          {/hf}
        {/if}
      </fieldset>

      {capture "saveButtonText"}
        {if $statement->isDraftOrNew()}
          {t}link-publish{/t}
        {else}
          {t}link-save{/t}
        {/if}
      {/capture}
      {include "bs/actions.tpl"
        cancelLink=$referrer
        deleteButton=$statement->isDeletable()
        deleteButtonConfirm="{t}info-confirm-delete-statement{/t}"
        reopenButton=$statement->isReopenable()
        reopenButtonConfirm="{t}info-confirm-reopen-statement{/t}"
        saveButtonConfirm=$statement->isDraft()
        saveButtonText=$smarty.capture.saveButtonText
        saveDraftButton=$statement->isDraftOrNew()}

    </form>
  </div>
{/block}
