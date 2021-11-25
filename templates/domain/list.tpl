{extends "layout.tpl"}

{block "title"}{cap}{t}title-domains{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-domains{/t}{/cap}</h1>

    <a class="btn btn-sm btn-primary col-12 col-md-3" href="{Router::link('domain/edit')}">
      {include "bits/icon.tpl" i=add_circle}
      {t}link-add-domain{/t}
    </a>

    {if count($domains)}
      <div class="gtable container">
        <div class="row gtable-header">
          <div class="col-10 col-sm-5">{t}label-name{/t}</div>
          <div class="col-2 col-sm-1">{t}label-icon{/t}</div>
          <div class="col-10 col-sm-5">{t}label-display-value{/t}</div>
          <div class="col-2 col-sm-1">{t}actions{/t}</div>
        </div>

        {foreach $domains as $d}
          <div class="row gtable-row">
            <div class="col-10 col-sm-5">
              {$d->name|escape}
            </div>
            <div class="col-2 col-sm-1">
              {include "bits/image.tpl"
                obj=$d
                geometry=Config::THUMB_DOMAIN}
            </div>
            <div class="col-10 col-sm-5">
              {$d->displayValue|escape}
            </div>
            <div class="col-2 col-sm-1">
              <a
                href="{$d->getEditUrl()}"
                class="btn"
                title="{t}link-edit{/t}">
                {include "bits/icon.tpl" i=mode_edit}
              </a>
            </div>
          </div>
        {/foreach}
      </div>
    {/if}
  </div>
{/block}
