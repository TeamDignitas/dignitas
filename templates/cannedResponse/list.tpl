{extends "layout.tpl"}

{block "title"}{cap}{t}title-canned-responses{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-canned-responses{/t}{/cap}</h1>

    <a href="{Router::link('cannedResponse/edit')}" class="btn btn-sm btn-primary col-12 col-md-3">
      {include "bits/icon.tpl" i=add_circle}
      {t}link-add-canned-response{/t}
    </a>

    <form method="post">
      <table class="table table-hover mt-5 sortable">
        <thead>
          <tr class="d-flex small">
            <th class="col-sm-3 col-md-2">{t}title-order{/t}</th>
            <th class="col-sm-7 col-md-8">{t}title-comment-text{/t}</th>
            <th class="col-sm-2 col-md-2">{t}title-actions{/t}</th>
          </tr>
        </thead>
        <tbody>
          {foreach $cannedResponses as $cr}
            <tr class="d-flex small">
              <td class="col-sm-3 col-md-2">
                <input type="hidden" name="cannedResponseIds[]" value="{$cr->id}">
                {include "bits/icon.tpl" i=drag_indicator class="drag-indicator"}
              </td>

              <td class="col-sm-7 col-md-8">
                {$cr->contents|md}
              </td>

              <td class="col-sm-2 col-md-2">
                <a
                  href="{Router::link('cannedResponse/edit')}/{$cr->id}"
                  class="btn mt-1"
                  title="{t}link-edit{/t}">
                  {include "bits/icon.tpl" i=mode_edit}
                </a>
                {include "bits/historyButton.tpl" obj=$cr iconOnly=true}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>

      <div class="mt-4 text-end">
        <a href="{Router::link('cannedResponse/list')}" class="btn btn-sm btn-outline-secondary col-12 col-md-2 mb-2 me-1">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>
        <button type="submit" class="btn btn-sm btn-outline-primary col-12 col-md-2 mb-2" name="saveButton">
          {include "bits/icon.tpl" i=save}
          {t}link-save-order{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
