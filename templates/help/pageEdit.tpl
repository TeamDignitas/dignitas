{extends "layout.tpl"}

{block "title"}{t}title-edit-help-page{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5 capitalize-first-word">{t}title-edit-help-page{/t}</h1>

    <form method="post">

      <fieldset class="related-fields mb-5 ml-3">
        <div class="form-group row">
          <label for="field-category-id" class="col-sm-12 col-lg-2 mt-2 pl-0">{t}label-category{/t}</label>
          <div class="col-sm-12 col-lg-10 px-0">
            <select
              name="categoryId"
              id="field-category-id"
              class="form-control {if isset($errors.categoryId)}is-invalid{/if}">
              {foreach HelpCategory::loadAll() as $cat}
                <option
                  value="{$cat->id}"
                  {if $cat->id == $page->categoryId}selected{/if}>
                  {$cat->getName()}
                </option>
              {/foreach}
            </select>
            {include "bits/fieldErrors.tpl" errors=$errors.categoryId|default:null}
          </div>
        </div>
      </fieldset>

      <div class="tabs-wrapper">
        {$locales=Config::LOCALES}

        <nav class="nav nav-pills">
          {foreach $translations as $locale => $ignored}
            {$loc=$locale|replace:'.utf8':''} {* no dots in selectors *}
            <a
              class="nav-link {if $locale == Config::DEFAULT_LOCALE}active{/if}"
              id="tab-{$loc}"
              data-toggle="tab"
              role="tab"
              href="#tab-contents-{$loc}">
              {t}{$locales.$locale}{/t}
              {if isset($errors.title.$locale) ||
                isset($errors.path.$locale) ||
                isset($errors.contents.$locale)}
                <span class="text-danger">
                  {include "bits/icon.tpl" i=error}
                </span>
              {/if}
            </a>
          {/foreach}
        </nav>

        <div class="tab-content my-4">
          {foreach $translations as $locale => $hpt}
            {$loc=$locale|replace:'.utf8':''} {* no dots in selectors *}
            <div
              id="tab-contents-{$loc}"
              class="tab-pane fade {if $locale == Config::DEFAULT_LOCALE}show active{/if}"
              data-trigger="#tab-{$loc}"
              role="tabpanel">

              <fieldset class="related-fields mb-5 ml-3">
                <div class="form-group row">
                  <label for="field-title" class="control-label col-sm-12 col-lg-2 mt-2 pl-0">
                    {t}label-title{/t}
                  </label>
                  <div class="col-sm-12 col-lg-10 px-0">
                    <input type="text"
                      class="form-control {if isset($errors.title.$locale)}is-invalid{/if}"
                      id="field-title"
                      name="title-{$locale}"
                      value="{$hpt->title|escape}">
                    {include "bits/fieldErrors.tpl" errors=$errors.title.$locale|default:null}
                  </div>
                </div>

                <div class="form-group row">
                  <label for="field-path" class="control-label col-sm-12 col-lg-2 mt-2 pl-0">
                    {t}label-help-page-path{/t}
                  </label>
                  <div class="col-sm-12 col-lg-10 px-0">
                    <input type="text"
                      class="form-control {if isset($errors.path.$locale)}is-invalid{/if}"
                      id="field-path"
                      name="path-{$locale}"
                      value="{$hpt->path|escape}"
                      placeholder="{t}info-help-page-path{/t}">
                    {include "bits/fieldErrors.tpl" errors=$errors.path.$locale|default:null}
                  </div>
                </div>

              </fieldset>

              <fieldset class="related-fields mb-5 ml-3">
                <div class="form-group row">
                  <label for="field-contents" class="col-sm-12 col-lg-2 mt-2 pl-0">
                    {t}label-contents{/t}
                  </label>

                  <div class="col-sm-12 col-lg-10 px-0">
                    <textarea
                      id="field-contents"
                      class="form-control has-unload-warning easy-mde {if isset($errors.contents.$locale)}is-invalid{/if}"
                      name="contents-{$locale}">{$hpt->contents|escape}</textarea>
                    {include "bits/markdownHelp.tpl"}
                    {include "bits/fieldErrors.tpl" errors=$errors.contents.$locale|default:null}
                  </div>
                </div>
              </fieldset>
            </div>
          {/foreach}
        </div>
      </div>

      <div class="mt-4 text-end">

        {if $page->id}
          <button
            name="deleteButton"
            type="submit"
            class="btn btn-sm btn-outline-danger col-sm-12 col-md-2 mr-2 mb-2"
            data-confirm="{t}info-confirm-delete-help-page{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </button>
        {/if}

        <a href="{$page->getViewUrl()}" class="btn btn-sm btn-outline-secondary col-sm-12 col-md-2 mr-2 mb-2">
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
