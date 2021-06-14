{extends "layout.tpl"}

{block "title"}{t}title-edit-help-category{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5 capitalize-first-word">{t}title-edit-help-category{/t}</h1>

    <form method="post">

      <div class="tabs-wrapper">
        {$locales=Config::LOCALES}

        <nav class="nav nav-pills">
          {foreach $translations as $locale => $ignored}
            {$loc=$locale|replace:'.utf8':''} {* no dots in selectors *}
            <a
              class="nav-link {if $locale == Config::DEFAULT_LOCALE}active{/if}"
              id="tab-{$loc}"
              data-bs-toggle="tab"
              role="tab"
              href="#tab-contents-{$loc}">
              {t}{$locales.$locale}{/t}
              {if isset($errors.name.$locale) || isset($errors.path.$locale)}
                <span class="text-danger">
                  {include "bits/icon.tpl" i=error}
                </span>
              {/if}
            </a>
          {/foreach}
        </nav>

        <div class="tab-content my-4">
          {foreach $translations as $locale => $hct}
            {$loc=$locale|replace:'.utf8':''} {* no dots in selectors *}
            <div
              id="tab-contents-{$loc}"
              class="tab-pane fade {if $locale == Config::DEFAULT_LOCALE}show active{/if}"
              role="tabpanel">

              <fieldset class="related-fields mb-5 ms-3">
                {hf inputId='field-name' label="{t}label-name{/t}"}
                  <input type="text"
                    class="form-control {if isset($errors.name.$locale)}is-invalid{/if}"
                    id="field-name"
                    name="name-{$locale}"
                    value="{$hct->name|escape}">
                  {include "bs/feedback.tpl" errors=$errors.name.$locale|default:null}
                {/hf}

                {hf inputId='field-path' label="{t}label-help-category-path{/t}"}
                  <input type="text"
                    class="form-control {if isset($errors.path.$locale)}is-invalid{/if}"
                    id="field-path"
                    name="path-{$locale}"
                    value="{$hct->path|escape}"
                    placeholder="{t}info-help-category-path{/t}">
                  {include "bs/feedback.tpl" errors=$errors.path.$locale|default:null}
                {/hf}
              </fieldset>
            </div>
          {/foreach}
        </div>

      </div>

      <fieldset class="mt-5">
        <legend>{cap}{t}title-help-pages-in-category{/t}{/cap}</legend>

        <table class="table table-hover mt-3 sortable">
          <tbody>
            {foreach $cat->getPages() as $p}
              <tr class="d-flex">
                <td class="col-1">
                  <input type="hidden" name="pageIds[]" value="{$p->id}">
                  {include "bits/icon.tpl" i=drag_indicator class="drag-indicator pt-0"}
                </td>

                <td class="col-11">
                  {$p->getTitle()}
                </td>
              </tr>
            {/foreach}
          </tbody>
        </table>
      </fieldset>

      <div class="mb-4 text-end">
        {if $canDelete}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-12 col-md-2 me-2 mb-2"
            data-confirm="{t}info-confirm-delete-help-category{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{$cat->getViewUrl()}" class="btn btn-sm btn-outline-secondary col-sm-12 col-md-2 me-2 mb-2">
          {include "bits/icon.tpl" i=cancel}
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-12 col-md-2 mb-2">
          {include "bits/icon.tpl" i=save}
          {t}link-save{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
