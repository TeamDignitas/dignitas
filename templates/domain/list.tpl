{extends "layout.tpl"}

{block "title"}{cap}{t}title-domains{/t}{/cap}{/block}

{block "content"}

  <h3>{cap}{t}title-domains{/t}{/cap}</h3>

  <a class="btn btn-light" href="{Router::link('domain/edit')}">
    <i class="icon icon-plus"></i>
    {t}link-add-domain{/t}
  </a>

  {if count($domains)}
    <table class="table table-sm table-hover mt-2">
      <thead>
        <tr>
          <th>{t}label-icon{/t}</th>
          <th>{t}label-name{/t}</th>
          <th>{t}label-display-value{/t}</th>
          <th>{t}actions{/t}</th>
        </tr>
      </thead>
      <tbody>
        {foreach $domains as $d}
          <tr>
            <td>
              {include "bits/image.tpl"
                obj=$d
                geometry=Config::THUMB_DOMAIN}
            </td>
            <td>{$d->name|escape}</td>
            <td>{$d->displayValue|escape}</td>
            <td><a href="{Router::getEditLink($d)}">{t}link-edit{/t}</a></td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  {/if}

{/block}
