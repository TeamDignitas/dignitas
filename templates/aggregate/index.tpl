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
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner pb-5">

            {foreach $statements as $i => $page}
              <div class="carousel-item mb-3 {if !$i}active{/if}">
                <div class="container px-0">

                  <div class="row mb-5">
                    <div class="col-4">
                      {include "bits/carouselStatement.tpl" statement=$page[0]}
                    </div>

                    <div class="col-4">
                      {if count($page) >= 2}
                        {include "bits/carouselStatement.tpl" statement=$page[1]}
                      {/if}
                    </div>
                  </div>

                  {if count($page) >= 3}
                    <div class="row mb-5">
                      <div class="col-4">
                        {include "bits/carouselStatement.tpl" statement=$page[2]}
                      </div>

                      <div class="col-4">
                        {if count($page) == 4}
                          {include "bits/carouselStatement.tpl" statement=$page[3]}
                        {/if}
                      </div>
                    </div>
                  {/if}
                </div>
              </div>
            {/foreach}

            <ol class="carousel-indicators">
              {foreach $statements as $i => $ignored}
                <li
                  data-target="#carouselExampleIndicators"
                  data-slide-to="{$i}"
                  {if !$i}class="active"{/if}>
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
