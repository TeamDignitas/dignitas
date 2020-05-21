{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  {foreach $staticResourcesTop as $sr}
    {$sr->getContents()}
  {/foreach}

  <div class="container">
    <div class="statements-carousel pb-4">
      <h3 class="mt-4 mb-4 capitalize-first-word serif-text">{t}title-recent-statements{/t}</h3>

      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner pb-5">

          {foreach $statements as $i => $page}
            <div class="carousel-item mb-3 {if !$i}active{/if}">
              <div class="container px-0">

                <div class="row mb-5">
                  <div class="col-6">
                    {include "bits/carouselStatement.tpl" statement=$page[0]}
                  </div>

                  <div class="col-6">
                    {if count($page) >= 2}
                      {include "bits/carouselStatement.tpl" statement=$page[1]}
                    {/if}
                  </div>
                </div>

                {if count($page) >= 3}
                  <div class="row mb-5">
                    <div class="col-6">
                      {include "bits/carouselStatement.tpl" statement=$page[2]}
                    </div>

                    <div class="col-6">
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

  {foreach $staticResourcesBottom as $sr}
    {$sr->getContents()}
  {/foreach}

{/block}
