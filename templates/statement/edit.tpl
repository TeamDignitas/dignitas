{extends "layout.tpl"}

{capture "title"}
{if $statement->id}
  {t}edit statement{/t}
{else}
  {t}add a statement{/t}
{/if}
{/capture}

{block "title"}{cap}{$smarty.capture.title}{/cap}{/block}

{block "content"}
  <h3>{$smarty.capture.title}</h3>

  {if !$statement->isEditable()}
    <div class="alert alert-warning">
      {t}You do not have enough reputation to make changes directly. You can
      suggest changes which will be placed in the review queue.{/t}
    </div>
  {/if}

  <form method="post">
    <input type="hidden" name="id" value="{$statement->id}">
    <input type="hidden" name="referrer" value="{$referrer}">
    <div class="form-group">
      <label for="fieldEntityId">{t}author{/t}</label>
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
      <label for="fieldDateMade">{t}statement date{/t}</label>
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
      <label for="fieldSummary">{t}summary{/t}</label>
      <input
        type="text"
        name="summary"
        id="fieldSummary"
        value="{$statement->summary|escape}"
        class="form-control hasUnloadWarning {if isset($errors.summary)}is-invalid{/if}"
        required>
      {include "bits/fieldErrors.tpl" errors=$errors.summary|default:null}
    </div>

    <div class="form-group">
      <label for="fieldContext">{t}context{/t}</label>
      <textarea
        name="context"
        id="fieldContext"
        class="form-control hasUnloadWarning {if isset($errors.context)}is-invalid{/if}"
        rows="10">{$statement->context|escape}</textarea>
      {include "bits/fieldErrors.tpl" errors=$errors.context|default:null}
      {include "bits/markdownHelp.tpl"}
    </div>

    <div class="form-group">
      <label for="fieldGoal">{t}goal{/t}</label>
      <input
        type="text"
        name="goal"
        id="fieldGoal"
        value="{$statement->goal|escape}"
        class="form-control hasUnloadWarning {if isset($errors.goal)}is-invalid{/if}"
        required>
      {include "bits/fieldErrors.tpl" errors=$errors.goal|default:null}
    </div>

    {capture "labelText" assign=labelText}{t}source URLs{/t}{/capture}
    {capture "addButtonText" assign=addButtonText}{t}add a source{/t}{/capture}
    {include "bits/linkEditor.tpl"
      errors=$errors.links|default:null
    }

    <div class="form-group">
      <label>{t}tags{/t}</label>

      <select name="tagIds[]" class="form-control select2Tags" multiple>
        {foreach $tagIds as $tagId}
          <option value="{$tagId}" selected></option>
        {/foreach}
      </select>
    </div>

    <div>
      <button name="saveButton" type="submit" class="btn btn-primary">
        <i class="icon icon-floppy"></i>
        {t}save{/t}
      </button>

      <a href="{$referrer}" class="btn btn-link">
        <i class="icon icon-cancel"></i>
        {t}cancel{/t}
      </a>

      {if $statement->isDeletable()}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right deleteButton"
          data-confirm="{t}Are you sure you want to delete this statement?{/t}">
          <i class="icon icon-trash"></i>
          {t}delete{/t}
        </button>
      {/if}
    </div>
  </form>
{/block}
