<form id="form-comment" class="col-12 mt-1">
  <input type="hidden" name="objectType">
  <input type="hidden" name="objectId">

  <div class="mb-2">
    <textarea
      name="contents"
      class="form-control size-limit"
      rows="2"
      maxlength="{Comment::MAX_LENGTH}"
    ></textarea>
    <div class="clearfix">
      <span class="chars-remaining form-text"></span>
      {include "bits/markdownHelp.tpl"}
    </div>
  </div>

  <div class="answer-buttons mb-2 d-flex flex-column flex-md-row">
    <button
      type="button"
      class="btn btn-sm btn-outline-secondary dropdown-toggle toggle-canned-responses mb-1 me-0 me-md-auto"
      data-bs-toggle="dropdown">
      {t}link-pick-canned-response{/t}
    </button>
    <div class="dropdown-menu dropdown-canned-responses p-2 text-muted">
      {foreach CannedResponse::loadAll() as $i => $cr}
        {if $i}
          <div class="dropdown-divider"></div>
        {/if}
        <div class="canned-response-wrapper" data-raw="{$cr->contents|escape:html}">
          {$cr->contents|md}
        </div>
      {/foreach}
    </div>

    <button type="button" class="btn btn-sm btn-outline-secondary comment-cancel mb-1 me-0 me-md-1">
      {include "bits/icon.tpl" i=cancel}
      {t}link-cancel{/t}
    </button>

    <button type="submit" class="btn btn-sm btn-outline-primary comment-save mb-1">
      {include "bits/icon.tpl" i=save}
      {t}link-save{/t}
    </button>
  </div>
</form>
