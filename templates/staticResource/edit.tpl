{extends "layout.tpl"}

{block "title"}{t}title-edit-static-resource{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-static-resource{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-name' label="{t}label-name{/t}"}
          <input type="text"
            class="form-control {if isset($errors.name)}is-invalid{/if}"
            id="field-name"
            name="name"
            value="{$sr->name|esc}">
          {include "bs/feedback.tpl" errors=$errors.name|default:null}
          <div class="form-text">
            {t}info-static-resource-name{/t}
          </div>
        {/hf}

        {hf inputId='field-locale' label="{t}label-locale{/t}"}
          <select
            name="locale"
            id="field-locale"
            class="form-select">
            <option value="">
              {t}label-all-locales{/t}
            </option>
            {foreach Config::LOCALES as $code => $name}
              <option
                value="{$code}"
                {if $sr->locale == $code}selected{/if}>
                {$name|esc}
              </option>
            {/foreach}
          </select>
        {/hf}
      </fieldset>

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-contents' label="{t}label-contents{/t}"}
          {strip}
          <textarea
            id="field-contents"
            name="contents"
            class="form-control has-unload-warning size-limit easy-mde"
            data-mime="{$sr->getMimeType()}"
            rows="10">{$sr->getEditableContents()|esc}</textarea>
          {/strip}

          <label class="form-label my-2" for="field-file">
            {t}info-choose-static-resource-file{/t}:
          </label>

          <input
            class="form-control {if isset($errors.file)}is-invalid{/if}"
            id="field-file"
            name="file"
            type="file">
          {include "bs/feedback.tpl" errors=$errors.file|default:null}
        {/hf}
      </fieldset>

      {include "bs/actions.tpl"
        cancelLink=Router::link('staticResource/list')
        deleteButton=$sr->id
        deleteButtonConfirm="{t}info-confirm-delete-static-resource{/t}"}
    </form>
  </div>
{/block}
