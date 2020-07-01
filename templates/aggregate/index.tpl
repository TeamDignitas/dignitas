{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  {foreach $staticResourcesTop as $sr}
    {$sr->getContents()}
  {/foreach}

  <div class="dark-background">
    <div class="f-container py-5">
      <div class="col-lg-3 col-sm-12 mt-3 mb-5">
        <div>
          <span class="col-6 text-uppercase ivory meta-heading pl-0">Contribuie și tu</span>
          <span class="col-2 ivory meta-line"></span>
        </div>
      </div>

      <div class="col-lg-9 col-sm-12 mt-3">
        {if User::may(User::PRIV_ADD_STATEMENT)}
          <a href="{Router::link('statement/edit')}" class="btn btn-primary mr-3 mb-2 px-5">
            <i class="icon icon-pencil pr-2"></i>{t}link-add-statement{/t}
          </a>
        {/if}

        {if User::may(User::PRIV_ADD_ENTITY)}
          <a href="{Router::link('entity/edit')}" class="btn btn-outline-primary mb-2 px-5">
            <i class="icon icon-user-plus pr-2"></i>{t}link-add-entity{/t}
          </a>
        {/if}
      </div>
    </div>
  </div>

  <div class="f-container py-5">
    <div class="col-lg-3 col-sm-12 mt-3 mb-5">
      <div>
        <span class="col-6 text-uppercase meta-heading pl-0">{t}title-recent-statements{/t}</span>
        <span class="col-2 meta-line"></span>
      </div>
    </div>

    <div class="col-lg-9 col-sm-12 mt-3">
      <div class="statements-carousel pb-4">
        <div id="statementCarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner pb-5">

            {$numCols=12/Config::CAROUSEL_COLUMNS}
            {foreach $carousel as $page => $rows}
              <div class="carousel-item mb-3 {if !$page}active{/if}">
                <div class="container px-0">

                  {foreach $rows as $row}
                    <div class="row">
                      {foreach $row as $stmt}
                        <div class="col-md-{$numCols} col-sm-12 mb-3">
                          {include "bits/carouselStatement.tpl" statement=$stmt}
                        </div>
                      {/foreach}
                    </div>
                  {/foreach}

                </div>
              </div>
            {/foreach}

            <ol class="carousel-indicators">
              {foreach $carousel as $page => $ignored}
                <li
                  data-target="#statementCarousel"
                  data-slide-to="{$page}"
                  {if !$page}class="active"{/if}>
                </li>
              {/foreach}
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>

  {foreach $staticResourcesBottom as $sr}
    {$sr->getContents()}
  {/foreach}

{/block}
