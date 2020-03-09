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
  <h3>{$smarty.capture.title}</h3>

  {if !$statement->isEditable()}
    <div class="alert alert-warning">
      {$statement->getEditMessage()}
    </div>
  {/if}

  <form method="post">
    <input type="hidden" name="id" value="{$statement->id}">
    <input type="hidden" name="referrer" value="{$referrer}">
    <div class="form-group">
      <label for="fieldEntityId">{t}label-entity{/t}</label>
      <select
        name="entityId"
        id="fieldEntityId"
        class="form-control {if isset($errors.entityId)}is-invalid{/if}">
        {if $statement->entityId}
          <option value="{$statement->entityId}"></option>
        {/if}
      </select>
      {include "bits/fieldErrors.tpl" errors=$errors.entityId|default:null}
    </div>

    <div class="form-group">
      <label for="fieldDateMade">{t}label-statement-date{/t}</label>
      <input
        type="date"
        name="dateMade"
        id="fieldDateMade"
        value="{$statement->dateMade}"
        class="form-control {if isset($errors.dateMade)}is-invalid{/if}"
        required>
      {include "bits/fieldErrors.tpl" errors=$errors.dateMade|default:null}
    </div>

    <div class="form-group">
      <label for="fieldSummary">{t}label-summary{/t}</label>
      <input
        type="text"
        name="summary"
        id="fieldSummary"
        value="{$statement->summary|escape}"
        class="form-control has-unload-warning {if isset($errors.summary)}is-invalid{/if}"
        required>
      {include "bits/fieldErrors.tpl" errors=$errors.summary|default:null}
    </div>

    <div class="form-group">
      <label for="fieldContext">{t}label-context{/t}</label>
      <textarea
        name="context"
        id="fieldContext"
        class="form-control has-unload-warning simple-mde {if isset($errors.context)}is-invalid{/if}"
        rows="10">{$statement->context|escape}</textarea>
      {include "bits/fieldErrors.tpl" errors=$errors.context|default:null}
      {include "bits/markdownHelp.tpl"}
    </div>

    <div class="form-group">
      <label for="fieldGoal">{t}label-goal{/t}</label>
      <input
        type="text"
        name="goal"
        id="fieldGoal"
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
        <label for="fieldVerdict">{t}label-verdict{/t}</label>
        <select
          id="fieldVerdict"
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
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}link-save{/t}
      </button>

      <a href="{$referrer}" class="btn btn-link">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>

      {if $statement->isDeletable()}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right"
          data-confirm="{t}info-confirm-delete-statement{/t}">
          <i class="icon icon-trash"></i>
          {t}link-delete{/t}
        </button>
      {/if}
    </div>
  </form>
{/block}
