{extends "layout.tpl"}

{block "title"}{cap}{t}title-canned-responses{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-canned-responses{/t}{/cap}</h1>

    <a href="{Router::link('cannedResponse/edit')}" class="btn btn-sm btn-primary">
      <i class="icon icon-plus"></i>
      {t}link-add-canned-response{/t}
    </a>

    <form method="post">
      <table class="table table-hover mt-5 sortable border-bottom">
        <thead>
          <tr class="d-flex small">
            <th class="col-sm-3 col-md-2 border-0">{t}title-order{/t}</th>
            <th class="col-sm-7 col-md-8 border-0">{t}title-comment-text{/t}</th>
            <th class="col-sm-2 col-md-2 border-0">{t}title-actions{/t}</th>
          </tr>
        </thead>
        <tbody>
          {foreach $cannedResponses as $cr}
            <tr class="d-flex small">
              <td class="col-sm-3 col-md-2">
                <input type="hidden" name="cannedResponseIds[]" value="{$cr->id}">
                <label class="icon icon-move"></label>
              </td>

              <td class="col-sm-7 col-md-8">
                {$cr->contents|md}
              </td>

              <td class="col-sm-2 col-md-2">
                <a
                  href="{Router::link('cannedResponse/edit')}/{$cr->id}"
                  class="btn btn-outline-secondary mt-1"
                  title="{t}link-edit{/t}">
                  <i class="icon icon-pencil"></i>
                </a>
                {include "bits/historyButton.tpl" obj=$cr}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>

      <div class="mt-4 text-right">
        <a href="{Router::link('cannedResponse/list')}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>
        <button type="submit" class="btn btn-sm btn-outline-secondary" name="saveButton">
          <i class="icon icon-floppy"></i>
          {t}link-save-order{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
