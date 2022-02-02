{extends "layout.tpl"}

{block "title"}{t}title-edit-canned-response{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{t}title-edit-canned-response{/t}</h1>

    <form method="post">

      {field inputId="field-contents" label="{t}label-contents{/t}"}
        <textarea
          id="field-contents"
          name="contents"
          class="form-control has-unload-warning size-limit easy-mde {if isset($errors.contents)}is-invalid{/if}"
          maxlength="{Comment::MAX_LENGTH}"
        >{$cannedResponse->contents|esc}</textarea>

        {include "bs/feedback.tpl" errors=$errors.contents|default:null}
        <div class="d-flex flex-column flex-md-row justify-content-between">
          <span class="chars-remaining form-text"></span>
          {include "bits/markdownHelp.tpl"}
        </div>
      {/field}

      {include "bs/actions.tpl"
        cancelLink=Router::link('cannedResponse/list')
        deleteButton=$cannedResponse->id
        deleteButtonConfirm="{t}info-confirm-delete-canned-response{/t}"}

    </form>
  </div>
{/block}
