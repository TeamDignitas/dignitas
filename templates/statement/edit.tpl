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

  <form method="post">
    <input type="hidden" name="id" value="{$statement->id}">
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

    <h6>{t}preview{/t}</h6>

    <div id="markdownPreview">
      {$statement->context|md}
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

    <div class="form-group">
      <label>{t}source URLs{/t}</label>

      <table class="table table-sm">
        <tbody id="sourceContainer">
          {include "bits/sourceEdit.tpl" id="stem"}
          {foreach $sources as $s}
            {include "bits/sourceEdit.tpl" source=$s}
          {/foreach}
        </tbody>
      </table>

      {include "bits/fieldErrors.tpl" errors=$errors.sources|default:null}

      <div>
        <button id="addSourceButton" class="btn btn-light btn-sm" type="button">
          <i class="icon icon-plus"></i>
          {t}add a source{/t}
        </button>
      </div>
    </div>

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

      <a href="" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}cancel{/t}
      </a>

      {if $statement->id}
        <a href="{Router::link('statement/view')}/{$statement->id}" class="btn btn-light">
          <i class="icon icon-left"></i>
          {t}back to statement{/t}
        </a>
      {/if}

      {if $statement->id && User::may(User::PRIV_DELETE_STATEMENT)}
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
