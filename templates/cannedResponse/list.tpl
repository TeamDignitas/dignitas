{extends "layout.tpl"}

{block "title"}{cap}{t}title-canned-responses{/t}{/cap}{/block}

{block "content"}

  <h3>{cap}{t}title-canned-responses{/t}{/cap}</h3>

  <form method="post">
    <table class="table table-hover mt-3 sortable">
      <tbody>
        {foreach $cannedResponses as $cr}
          <tr class="d-flex">
            <td class="col-1">
              <input type="hidden" name="cannedResponseIds[]" value="{$cr->id}">
              <label class="icon icon-move"></label>
            </td>

            <td class="col">
              {$cr->contents|md}
            </td>

            <td class="col-auto">
              <a
                href="{Router::link('cannedResponse/edit')}/{$cr->id}"
                class="btn btn-sm btn-outline-secondary">
                <i class="icon icon-edit"></i>
                {t}link-edit{/t}
              </a>
              {if $cr->hasRevisions()}
                <a
                  class="btn btn-sm btn-outline-secondary"
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
      <button type="submit" class="btn btn-primary" name="saveButton">
        <i class="icon icon-floppy"></i>
        {t}link-save-canned-response-order{/t}
      </button>
      <a href="{Router::link('cannedResponse/list')}" class="btn btn-light">
        <i class="icon icon-cancel"></i>
        {t}link-cancel{/t}
      </a>
      <a href="{Router::link('cannedResponse/edit')}" class="btn btn-secondary">
        <i class="icon icon-plus"></i>
        {t}link-add-canned-response{/t}
      </a>
    </div>
  </form>

{/block}
