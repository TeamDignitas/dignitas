{extends "layout.tpl"}

{block "title"}{cap}{t}title-help-categories{/t}{/cap}{/block}

{block "content"}

  <h3>{cap}{t}title-help-categories{/t}{/cap}</h3>

  <form method="post">
    <table class="table table-hover mt-3">
      <tbody id="categoryContainer">
        {foreach $categories as $c}
          <tr class="d-flex">
            <td class="col-1">
              <input type="hidden" name="categoryIds[]" value="{$c->id}">
              <label class="icon icon-move"></label>
            </td>

            <td class="col-11">
              <a
                href="{Router::link('help/categoryEdit')}/{$c->id}">
                {$c->name}
              </a>
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>

    <div>
      <button type="submit" class="btn btn-primary" name="saveButton">
        <i class="icon icon-floppy"></i>
        {t}link-save-help-category-order{/t}
      </button>
      <a class="btn btn-secondary" href="{Router::link('help/categoryEdit')}">
        <i class="icon icon-plus"></i>
        {t}link-add-category{/t}
      </a>
    </div>
  </form>

{/block}
