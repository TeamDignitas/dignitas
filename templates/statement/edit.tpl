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

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="field-entity-id" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-entity{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
            <select
              name="entityId"
              id="field-entity-id"
              class="form-control {if isset($errors.entityId)}is-invalid{/if}">
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
          </div>
        </div>

        <div class="form-group row">
          <label for="field-date-made" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-statement-date{/t}</label>
          <input
            type="text"
            id="field-date-made"
            class="form-control datepicker {if isset($errors.dateMade)}is-invalid{/if} col-sm-12 col-lg-10"
            data-allow-partial="false"
            required>
          <input type="hidden" name="dateMade" value="{$statement->dateMade}">
          {include "bits/fieldErrors.tpl" errors=$errors.dateMade|default:null}
        </div>

        <div class="form-group row">
          <label for="field-type" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-type{/t}</label>
          <select
            id="field-type"
            name="type"
            {if $statement->needsTypeChangeWarning()}
            data-prev-value="{$statement->type}"
            data-confirm="{t}info-confirm-statement-type-change{/t}"
            {/if}
            class="form-control has-unload-warning col-sm-12 col-lg-10">
            {for $t = 1 to Statement::NUM_TYPES - 1}
              <option
                value="{$t}"
                {if $t == $statement->type}selected{/if}>
                {Statement::typeName($t)}
              </option>
            {/for}
          </select>
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="field-summary" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-summary{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
            <textarea
              name="summary"
              id="field-summary"
              class="form-control single-line size-limit has-unload-warning {if isset($errors.summary)}is-invalid{/if}"
              maxlength="{Statement::MAX_SUMMARY_LENGTH}"
              rows="3"
              required>{$statement->summary|escape}</textarea>

            {include "bits/fieldErrors.tpl" errors=$errors.summary|default:null}

            <small class="form-text text-muted float-left">
              <span class="chars-remaining">
                {Statement::MAX_SUMMARY_LENGTH-mb_strlen($statement->summary)}
              </span>
              {t}label-characters-remaining{/t}
            </small>
          </div>
        </div>

        <div class="form-group row">
          <label for="field-context" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-context{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
            <textarea
              name="context"
              id="field-context"
              class="form-control has-unload-warning easy-mde {if isset($errors.context)}is-invalid{/if}"
              data-statement-id="{$statement->id}"
              rows="10">{$statement->context|escape}</textarea>
            {include "bits/fieldErrors.tpl" errors=$errors.context|default:null}
            {include "bits/markdownHelp.tpl"}
          </div>
        </div>

        <div class="form-group row">
          <label for="field-goal" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-goal{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
            <textarea
              name="goal"
              id="field-goal"
              class="form-control single-line size-limit has-unload-warning {if isset($errors.goal)}is-invalid{/if}"
              maxlength="{Statement::MAX_GOAL_LENGTH}"
              rows="3"
              required>{$statement->goal|escape}</textarea>

            {include "bits/fieldErrors.tpl" errors=$errors.goal|default:null}

            <small class="form-text text-muted float-left">
              <span class="chars-remaining">
                {Statement::MAX_GOAL_LENGTH-mb_strlen($statement->goal)}
              </span>
              {t}label-characters-remaining{/t}
            </small>
          </div>
        </div>

        {capture "labelText" assign=labelText}{t}label-statement-links{/t}{/capture}
        {capture "addButtonText" assign=addButtonText}{t}link-add-statement-link{/t}{/capture}
        {include "bits/linkEditor.tpl"
          errors=$errors.links|default:null
        }
      </fieldset>

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-tags{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
            <select name="tagIds[]" class="form-control select2Tags col-10" multiple>
              {foreach $tagIds as $tagId}
                <option value="{$tagId}" selected></option>
              {/foreach}
            </select>
          </div>
        </div>

        {if User::isModerator()}
          <div class="form-group row">
            <label for="field-verdict" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-verdict{/t}</label>
            <div class="col-sm-12 col-lg-10 px-0">

              <select
                id="field-verdict"
                name="verdict"
                class="form-control has-unload-warning {if isset($errors.verdict)}is-invalid{/if}">

                {foreach $statement->getVerdictChoices() as $v}
                  <option
                    value="{$v}"
                    {if $v == $statement->verdict}selected{/if}>
                    {Statement::verdictName($v)}
                  </option>
                {/foreach}
              </select>

              {include "bits/fieldErrors.tpl" errors=$errors.verdict|default:null}
            </div>
          </div>
        {/if}
      </fieldset>

      <div class="mt-4 text-right">
        {if $statement->isDeletable()}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-statement{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        {if $statement->isReopenable()}
          <button
            name="reopenButton"
            type="submit"
            class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-reopen-statement{/t}">
            {t}link-reopen{/t}
          </button>
        {/if}

        <a href="{$referrer}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
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
