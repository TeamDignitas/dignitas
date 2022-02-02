{extends "layout.tpl"}

{block "title"}{cap}{t}title-static-resources{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-static-resources{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('staticResource/edit')}">
      {include "bits/icon.tpl" i=add_circle}
      {t}link-add-static-resource{/t}
    </a>

    {if count($staticResources)}
      <table class="table table-hover mt-5 mb-4">
        <thead>
          <tr class="small">
            <th>{t}label-name{/t}</th>
            <th>{t}label-locale{/t}</th>
            <th>{t}label-url{/t}</th>
            <th>{t}actions{/t}</th>
          </tr>
        </thead>
        <tbody>
          {foreach $staticResources as $sr}
            <tr class="small">
              <td class="align-middle">{$sr->name|esc}</td>
              <td class="align-middle">
                {if $sr->locale}
                  {LocaleUtil::getDisplayName($sr->locale)|esc}
                {else}
                  {t}label-all-locales{/t}
                {/if}
              </td>
              <td>
                <a href="{$sr->getUrl()}" class="btn btn-sm btn-link">
                  {t}label-url{/t}
                </a>
              </td>
              <td>
                <a
                  href="{$sr->getEditUrl()}"
                  class="btn btn-sm"
                  title="{t}link-edit{/t}">
                  {include "bits/icon.tpl" i=mode_edit}
                </a>
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}
  </div>
{/block}
