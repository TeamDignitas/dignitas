{extends "layout.tpl"}

{block "title"}{cap}{t}title-domains{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-domains{/t}{/cap}</h1>

    <a class="btn btn-sm btn-outline-primary" href="{Router::link('domain/edit')}">
      <i class="icon icon-plus"></i>
      {t}link-add-domain{/t}
    </a>

    {if count($domains)}
      <table class="table table-hover mt-5 mb-4">
        <thead>
          <tr class="small">
            <th class="border-0">{t}label-icon{/t}</th>
            <th class="border-0">{t}label-name{/t}</th>
            <th class="border-0">{t}label-display-value{/t}</th>
            <th class="border-0">{t}actions{/t}</th>
          </tr>
        </thead>
        <tbody>
          {foreach $domains as $d}
            <tr class="small">
              <td>
                {include "bits/image.tpl"
                  obj=$d
                  geometry=Config::THUMB_DOMAIN}
              </td>
              <td class="align-middle">{$d->name|escape}</td>
              <td class="align-middle">{$d->displayValue|escape}</td>
              <td><a href="{$d->getEditUrl()}" class="btn btn-sm btn-outline-secondary">{t}link-edit{/t}</a></td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}
  </div>
{/block}
