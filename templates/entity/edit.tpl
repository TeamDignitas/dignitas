{extends "layout.tpl"}

{capture "title"}
{if $entity->id}
  {t}edit author{/t}
{else}
  {t}add an author{/t}
{/if}
{/capture}

{block "title"}{cap}{$smarty.capture.title}{/cap}{/block}

{block "content"}
  <h3>{$smarty.capture.title}</h3>

  <form method="post">
    <input type="hidden" name="id" value="{$entity->id}">
    <div class="form-group">
      <label for="fieldName">{t}name{/t}</label>
      <input
        name="name"
        value="{$entity->name}"
        id="fieldName"
        class="form-control {if isset($errors.name)}is-invalid{/if}">
      {include "bits/fieldErrors.tpl" errors=$errors.name|default:null}
    </div>

    <div class="form-group">
      <label for="fieldType">{t}type{/t}</label>
      <select
        name="type"
        id="fieldType"
        class="form-control {if isset($errors.type)}is-invalid{/if}">
        {foreach Entity::TYPES as $t}
          <option value="{$t}" {if $entity->type == $t}selected{/if}>
            {Entity::typeName($t)}
          </option>
        {/foreach}
      </select>
      {include "bits/fieldErrors.tpl" errors=$errors.type|default:null}
    </div>

    <div class="form-group">
      <label>{t}relationships{/t}</label>

      <table class="table table-sm">
        <thead
          id="relationHeader"
          {if empty($relations)}hidden{/if}>
          <tr>
            <th class="colOrder">{t}order{/t}</th>
            <th class="colType">{t}type{/t}</th>
            <th class="colTarget">{t}target{/t}</th>
            <th class="colDatePicker">{t}start date{/t}</th>
            <th class="colDatePicker">{t}end date{/t}</th>
            <th class="colActions">{t}actions{/t}</th>
          </tr>
        </thead>
        <tbody id="relationContainer">
          {include "bits/relationEdit.tpl" id="stem"}
          {foreach $relations as $r}
            {include "bits/relationEdit.tpl" relation=$r}
          {/foreach}
        </tbody>
      </table>

      {include "bits/fieldErrors.tpl" errors=$errors.relations|default:null}

      <div>
        <button id="addRelationButton" class="btn btn-light btn-sm" type="button">
          <i class="icon icon-plus"></i>
          {t}add a relationship{/t}
        </button>
      </div>
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

      {if $entity->id}
        <button
          name="deleteButton"
          type="submit"
          class="btn btn-danger float-right deleteButton"
          data-confirm="{t}Are you sure you want to delete this entity?{/t}">
          <i class="icon icon-trash"></i>
          {t}delete{/t}
        </button>
      {/if}
    </div>

  </form>
{/block}
