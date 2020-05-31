{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  {foreach $staticResourcesTop as $sr}
    {$sr->getContents()}
  {/foreach}

  <div class="f-container">
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
                    <div class="row mb-5">
                      {foreach $row as $stmt}
                        <div class="col-{$numCols}">
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
