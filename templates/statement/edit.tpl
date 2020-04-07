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
  <h2 class="mb-4">{$smarty.capture.title}</h2>

  {if !$statement->isEditable()}
    <div class="alert alert-warning">
      {$statement->getEditMessage()}
    </div>
  {/if}

  <form method="post">
    <input type="hidden" name="id" value="{$statement->id}">
    <input type="hidden" name="referrer" value="{$referrer}">
    <div class="form-group">
      <label for="field-entity-id">{t}label-entity{/t}</label>
      <select
        name="entityId"
        id="field-entity-id"
        class="form-control {if isset($errors.entityId)}is-invalid{/if}">
        {if $statement->entityId}
          <option value="{$statement->entityId}"></option>
        {/if}
      </select>
      {include "bits/fieldErrors.tpl" errors=$errors.entityId|default:null}
    </div>

    <div class="form-group">
      <label for="field-date-made">{t}label-statement-date{/t}</label>
      <input
        type="date"
        name="dateMade"
        id="field-date-made"
        value="{$statement->dateMade}"
        class="form-control {if isset($errors.dateMade)}is-invalid{/if}"
        required>
      {include "bits/fieldErrors.tpl" errors=$errors.dateMade|default:null}
    </div>

    <div class="form-group">
      <label for="field-summary">{t}label-summary{/t}</label>
      <input
        type="text"
        name="summary"
        id="field-summary"
        value="{$statement->summary|escape}"
        class="form-control has-unload-warning {if isset($errors.summary)}is-invalid{/if}"
        required>
      {include "bits/fieldErrors.tpl" errors=$errors.summary|default:null}
    </div>

    <div class="form-group">
      <label for="field-context">{t}label-context{/t}</label>
      <textarea
        name="context"
        id="field-context"
        class="form-control has-unload-warning easy-mde {if isset($errors.context)}is-invalid{/if}"
        rows="10">{$statement->context|escape}</textarea>
      {include "bits/fieldErrors.tpl" errors=$errors.context|default:null}
      {include "bits/markdownHelp.tpl"}
    </div>

    <div class="form-group">
      <label for="field-goal">{t}label-goal{/t}</label>
      <input
        type="text"
        name="goal"
        id="field-goal"
        value="{$statement->goal|escape}"
        class="form-control has-unload-warning {if isset($errors.goal)}is-invalid{/if}"
        required>
      {include "bits/fieldErrors.tpl" errors=$errors.goal|default:null}
    </div>

    {capture "labelText" assign=labelText}{t}label-statement-links{/t}{/capture}
    {capture "addButtonText" assign=addButtonText}{t}link-add-statement-link{/t}{/capture}
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

    {if User::isModerator()}
      <div class="form-group">
        <label for="field-verdict">{t}label-verdict{/t}</label>
        <select
          id="field-verdict"
          name="verdict"
          class="form-control has-unload-warning">
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

    <div>
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
{/block}
