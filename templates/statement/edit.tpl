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
  <div class="container mt-4">
    <h1 class="mb-5">{$smarty.capture.title}</h1>

    {if !$statement->isEditable()}
      <div class="alert alert-warning">
        {$statement->getEditMessage()}
      </div>
    {/if}

    <form method="post">
      <input type="hidden" name="id" value="{$statement->id}">
      <input type="hidden" name="referrer" value="{$referrer}">

      <fieldset class="related-fields mb-5">
        <div class="form-group row py-1 pr-1">
          <label for="field-entity-id" class="col-2 ml-0 mt-2">{t}label-entity{/t}</label>
          <select
            name="entityId"
            id="field-entity-id"
            class="form-control {if isset($errors.entityId)}is-invalid{/if} col-10">
            {if $statement->entityId}
              <option value="{$statement->entityId}"></option>
            {/if}
          </select>
          {include "bits/fieldErrors.tpl" errors=$errors.entityId|default:null}
        </div>

        <div class="form-group row py-1 pr-1 mb-0">
          <label for="field-date-made" class="col-2 ml-0 mt-2">{t}label-statement-date{/t}</label>
          <input
            type="date"
            name="dateMade"
            id="field-date-made"
            value="{$statement->dateMade}"
            class="form-control {if isset($errors.dateMade)}is-invalid{/if} col-10"
            required>
          {include "bits/fieldErrors.tpl" errors=$errors.dateMade|default:null}
        </div>
      </fieldset>

      <fieldset class="related-fields mb-5">
        <div class="form-group row py-1 pr-1">
          <label for="field-summary" class="col-2 ml-0 mt-2">{t}label-summary{/t}</label>
          <input
            type="text"
            name="summary"
            id="field-summary"
            value="{$statement->summary|escape}"
            class="form-control has-unload-warning {if isset($errors.summary)}is-invalid{/if} col-10"
            required>
          {include "bits/fieldErrors.tpl" errors=$errors.summary|default:null}
        </div>

        <div class="form-group row py-1 pr-1">
          <label for="field-context" class="col-2 ml-0 mt-2">{t}label-context{/t}</label>
          <div class="col-10 px-0">
            <textarea
              name="context"
              id="field-context"
              class="form-control has-unload-warning easy-mde {if isset($errors.context)}is-invalid{/if}"
              rows="10">{$statement->context|escape}</textarea>
            {include "bits/fieldErrors.tpl" errors=$errors.context|default:null}
            {include "bits/markdownHelp.tpl"}
          </div>
        </div>

        <div class="form-group row py-1 pr-1">
          <label for="field-goal" class="col-2 ml-0 mt-2">{t}label-goal{/t}</label>
          <input
            type="text"
            name="goal"
            id="field-goal"
            value="{$statement->goal|escape}"
            class="form-control has-unload-warning {if isset($errors.goal)}is-invalid{/if} col-10"
            required>
          {include "bits/fieldErrors.tpl" errors=$errors.goal|default:null}
        </div>

        {capture "labelText" assign=labelText}{t}label-statement-links{/t}{/capture}
        {capture "addButtonText" assign=addButtonText}{t}link-add-statement-link{/t}{/capture}
        {include "bits/linkEditor.tpl"
          errors=$errors.links|default:null
        }
      </fieldset>

      <fieldset class="related-fields mb-5">
        <div class="form-group row py-1 pr-1">
          <label class="col-2 ml-0 mt-2">{t}label-tags{/t}</label>
          <div class="col-10 px-0">
            <select name="tagIds[]" class="form-control select2Tags col-10" multiple>
              {foreach $tagIds as $tagId}
                <option value="{$tagId}" selected></option>
              {/foreach}
            </select>
          </div>
        </div>

        {if User::isModerator()}
          <div class="form-group row py-1 pr-1 mb-0">
            <label for="field-verdict" class="col-2 ml-0 mt-2">{t}label-verdict{/t}</label>
            <select
              id="field-verdict"
              name="verdict"
              class="form-control has-unload-warning col-10">
              {for $v = 0 to Ct::NUM_VERDICTS - 1}
                <option
                  value="{$v}"
                  {if $v == $statement->verdict}selected{/if}>
                  {Statement::verdictName($v)}
                </option>
              {/for}
            </select>
          </div>
        {/if}
      </fieldset>

      <div class="mt-4">
        <button name="saveButton" type="submit" class="btn btn-sm btn-outline-primary">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>

        <a href="{$referrer}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>

        <div class="float-right">
          {if $statement->isDeletable()}
            <button
              name="deleteButton"
              type="submit"
              class="btn btn-sm btn-outline-danger"
              data-confirm="{t}info-confirm-delete-statement{/t}">
              <i class="icon icon-trash"></i>
              {t}link-delete{/t}
            </button>
          {/if}

          {if $statement->isReopenable()}
            <button
              name="reopenButton"
              type="submit"
              class="btn btn-sm btn-outline-secondary"
              data-confirm="{t}info-confirm-reopen-statement{/t}">
              {t}link-reopen{/t}
            </button>
          {/if}
        </div>
      </div>
    </form>
  </div>
{/block}
