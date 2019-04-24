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
      <label for="fieldContents">{t}contents{/t}</label>
      <textarea
        name="contents"
        id="fieldContents"
        class="form-control hasUnloadWarning {if isset($errors.contents)}is-invalid{/if}"
        rows="10">{$statement->contents}</textarea>
      {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
      <small class="form-text text-muted float-right">
        {t}Markdown help{/t}: <a href="https://commonmark.org/help/">CommonMark</a>
        •
        <a href="https://guides.github.com/features/mastering-markdown/">GitHub Flavored</a>
      </small>
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

    <h4>{t}preview{/t}</h4>

    <div id="markdownPreview">
      {$statement->contents|md}
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
