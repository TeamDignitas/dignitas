<form id="form-comment" class="col-12 mt-1">
  <input type="hidden" name="objectType">
  <input type="hidden" name="objectId">

  <div class="form-group mb-2">
    <textarea
      name="contents"
      class="form-control size-limit"
      rows="2"
      maxlength="{Comment::MAX_LENGTH}"
    ></textarea>
    <span class="chars-remaining form-text small float-left"></span>
    {include "bits/markdownHelp.tpl"}
  </div>

  <div class="row answer-buttons mb-2">
    <div class="col-sm-12 col-md-6 dropdown-canned-responses">
      <button
        type="button"
        class="col-12 btn btn-sm btn-outline-secondary dropdown-toggle mb-1"
        data-toggle="dropdown">
        {t}link-pick-canned-response{/t}
      </button>
      <div class="dropdown-menu p-2 text-muted">
        {foreach CannedResponse::loadAll() as $i => $cr}
          {if $i}
            <div class="dropdown-divider"></div>
          {/if}
          <div class="canned-response-wrapper" data-raw="{$cr->contents|escape:html}">
            {$cr->contents|md}
          </div>
        {/foreach}
      </div>
    </div>

    <div class="col-sm-12 col-md-6 text-end">
      <button type="button" class="col-sm-12 col-md-5 btn btn-sm btn-outline-secondary comment-cancel mb-1">
        {include "bits/icon.tpl" i=cancel}
        {t}link-cancel{/t}
      </button>

      <button type="submit" class="col-sm-12 col-md-5 btn btn-sm btn-outline-primary comment-save mb-1">
        {include "bits/icon.tpl" i=save}
        {t}link-save{/t}
      </button>
    </div>
  </div>
</form>
