{extends "layout.tpl"}

{block "title"}{cap}{t}title-canned-responses{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-4">
    <h1 class="mb-4">{cap}{t}title-canned-responses{/t}{/cap}</h1>

    <a href="{Router::link('cannedResponse/edit')}" class="btn btn-sm btn-outline-primary">
      <i class="icon icon-plus"></i>
      {t}link-add-canned-response{/t}
    </a>

    <form method="post">
      <table class="table table-hover mt-5 sortable">
        <thead>
          <tr class="d-flex">
            <th class="col-1 border-0">{t}title-order{/t}</th>
            <th class="col-9 border-0">{t}title-comment-text{/t}</th>
            <th class="col-2 border-0">{t}title-actions{/t}</th>
          </tr>
        </thead>
        <tbody>
          {foreach $cannedResponses as $cr}
            <tr class="d-flex">
              <td class="col-1">
                <input type="hidden" name="cannedResponseIds[]" value="{$cr->id}">
                <label class="icon icon-move"></label>
              </td>

              <td class="col-9">
                {$cr->contents|md}
              </td>

              <td class="col-2">
                <a
                  href="{Router::link('cannedResponse/edit')}/{$cr->id}"
                  class="btn btn-sm btn-outline-secondary">
                  <i class="icon icon-edit"></i>
                  {t}link-edit{/t}
                </a>
                {if $cr->hasRevisions()}
                  <a
                    class="btn btn-sm btn-outline-secondary mt-1"
                    href="{Router::link('cannedResponse/history')}/{$cr->id}">
                    {t}link-show-revisions{/t}
                  </a>
                {/if}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>

      <div>
        <button type="submit" class="btn btn-sm btn-outline-secondary" name="saveButton">
          <i class="icon icon-floppy"></i>
          {t}link-save-order{/t}
        </button>
        <a href="{Router::link('cannedResponse/list')}" class="btn btn-sm btn-outline-secondary">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>
      </div>
    </form>
  </div>
{/block}
