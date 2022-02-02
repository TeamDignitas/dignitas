{extends "layout.tpl"}

{block "title"}{t}title-edit-domain{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-edit-domain{/t}</h1>

    <form method="post" enctype="multipart/form-data">

      <fieldset class="mb-5 ms-3">
        {hf inputId='field-name' label="{t}label-name{/t}"}
          <input type="text"
            class="form-control {if isset($errors.name)}is-invalid{/if}"
            id="field-name"
            name="name"
            value="{$domain->name|esc}"
            placeholder="{t}info-domain-name{/t}">
          {include "bs/feedback.tpl" errors=$errors.name|default:null}
        {/hf}

        {hf inputId='field-display-value' label="{t}label-display-value{/t}"}
          <input type="text"
            class="form-control {if isset($errors.displayValue)}is-invalid{/if}"
            id="field-display-value"
            name="displayValue"
            value="{$domain->displayValue|esc}"
            placeholder="{t}info-domain-display-value{/t}">
          {include "bs/feedback.tpl" errors=$errors.displayValue|default:null}
        {/hf}

        {hf inputId='field-image' label="{t}label-image{/t}"}
          <div>
            {include "bits/image.tpl"
              obj=$domain
              geometry=Config::THUMB_DOMAIN}
          </div>

          <input
            class="form-control {if isset($errors.image)}is-invalid{/if}"
            id="field-image"
            name="image"
            type="file">
          {include "bs/feedback.tpl" errors=$errors.image|default:null}

          {include 'bs/checkbox.tpl'
            divClass='mt-1'
            label="{t}label-delete-image{/t}"
            name='deleteImage'}
        {/hf}
      </fieldset>

      {include "bs/actions.tpl"
        cancelLink=Router::link('domain/list')
        cloneButton=$domain->id
        deleteButton=$canDelete
        deleteButtonConfirm="{t}info-confirm-delete-domain{/t}"}

    </form>
  </div>
{/block}
