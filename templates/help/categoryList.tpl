{extends "layout.tpl"}

{block "title"}{cap}{t}title-help-categories{/t}{/cap}{/block}

{block "content"}

  <h1 class="mb-5">{cap}{t}title-help-categories{/t}{/cap}</h1>

  <form method="post">
    <table class="table table-hover mt-3 sortable">
      <tbody>
        {foreach $categories as $c}
          <tr class="d-flex">
            <td class="col-1">
              <input type="hidden" name="categoryIds[]" value="{$c->id}">
              <label class="icon icon-move mb-0"></label>
            </td>

            <td class="col-11">
              {$c->name}
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>

    <div class="mt-4">
      <button type="submit" class="btn btn-sm btn-outline-primary" name="saveButton">
        <i class="icon icon-floppy"></i>
        {t}link-save-help-category-order{/t}
      </button>
      <a href="{Router::link('help/index')}" class="btn btn-sm btn-outline-secondary">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>
    </div>
  </form>

{/block}
