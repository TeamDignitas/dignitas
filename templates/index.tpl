{extends "layout.tpl"}

{block "title"}to be determined{/block}

{block "content"}

  {***************************** search form *****************************}
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <form id="searchForm" action="{Router::link('aggregate/search')}">
        <div class="input-group">
          <select
            id="searchField"
            class="form-control"
            name="q"
            multiple
            aria-label="search">
          </select>
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
              <i class="icon icon-search"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <h3>{t}recent statements{/t}</h3>

  {include "bits/statementList.tpl"}

  <h3>{t}entities{/t}</h3>

  {foreach $entities as $e}
    <div class="clearfix">
      {include "bits/image.tpl"
        obj=$e
        size=Config::THUMB_ENTITY_SMALL
        imgClass="pic float-right"}

      {include "bits/entityLink.tpl" e=$e}
      <div>{$e->getTypeName()}</div>
    </div>
    <hr>
  {/foreach}

  <div>
    {if User::may(User::PRIV_ADD_STATEMENT)}
      <a href="{Router::link('statement/edit')}" class="btn btn-link">
        {t}add a statement{/t}
      </a>
    {/if}

    {if User::may(User::PRIV_ADD_ENTITY)}
      <a href="{Router::link('entity/edit')}" class="btn btn-link">
        {t}add an author{/t}
      </a>
    {/if}
  </div>

{/block}
