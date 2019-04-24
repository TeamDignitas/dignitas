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
        rows="10">{$statement->context}</textarea>
      {include "bits/fieldErrors.tpl" errors=$errors.context|default:null}
      <small class="form-text text-muted float-right">
        {t}Markdown help{/t}: <a href="https://commonmark.org/help/">CommonMark</a>
        •
        <a href="https://guides.github.com/features/mastering-markdown/">GitHub Flavored</a>
      </small>
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

    <h4>{t}preview{/t}</h4>

    <div id="markdownPreview">
      {$statement->context|md}
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
