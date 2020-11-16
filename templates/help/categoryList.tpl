{extends "layout.tpl"}

{block "title"}{cap}{t}title-help-categories{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
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

      <div class="mt-4 text-right">
        <a href="{Router::link('help/index')}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>

        <button type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2" name="saveButton">
          <i class="icon icon-floppy"></i>
          {t}link-save-order{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
