{extends "layout.tpl"}

{block "title"}{cap}{t}title-dashboard{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-dashboard{/t}{/cap}</h1>

    <div class="row justify-content-center g-3">

      {if User::may(User::PRIV_ADD_STATEMENT)}
        {include "bs/dashcard.tpl"
          icon='mode_edit'
          link=Router::link('statement/edit')
          text="{t}link-add-statement{/t}"}
      {/if}

      {if User::may(User::PRIV_ADD_ENTITY)}
        {include "bs/dashcard.tpl"
          icon='person_add_alt_1'
          link=Router::link('entity/edit')
          text="{t}link-add-entity{/t}"}
      {/if}

      {if User::isModerator()}
        {include "bs/dashcard.tpl"
          icon='insert_link'
          link=Router::link('domain/list')
          text="{t}link-domains{/t}"}

        {include "bs/dashcard.tpl"
          icon='text_snippet'
          link=Router::link('cannedResponse/list')
          text="{t}link-canned-responses{/t}"}

        {include "bs/dashcard.tpl"
          icon='insert_invitation'
          link=Router::link('invite/list')
          text="{t}link-invites{/t}"}

        {include "bs/dashcard.tpl"
          icon='groups'
          link=Router::link('entityType/list')
          text="{t}link-entity-types{/t}"}

        {include "bs/dashcard.tpl"
          icon='compare_arrows'
          link=Router::link('relationType/list')
          text="{t}link-relation-types{/t}"}

        {include "bs/dashcard.tpl"
          icon='integration_instructions'
          link=Router::link('staticResource/list')
          text="{t}link-static-resources{/t}"}
      {/if}

      {include "bs/dashcard.tpl"
        icon='local_offer'
        link=Router::link('tag/list')
        text="{t}link-tags{/t}"}

      {include "bs/dashcard.tpl"
        icon='map'
        link=Router::link('region/list')
        text="{t}link-regions{/t}"}

    </div>

    {if User::may(User::PRIV_REVIEW) && !empty($activeReviewReasons)}
      <h4 class="mt-5">{cap}{t}title-review-queues{/t}{/cap}</h4>

      <div class="row g-3">
        {foreach $activeReviewReasons as $r}
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card py-3 text-center dashcard">
              <div class="card-body">
                <a
                  href="{Router::link('review/view')}/{Review::getUrlName($r)}"
                  class="stretched-link">
                  {cap}{Review::getDescription($r)}{/cap}
                </a>
              </div>
            </div>
          </div>
        {/foreach}
      </div>
    {/if}

    {if User::isModerator()}
      {if $numBadVerdicts}
        <h4 class="mt-5">{cap}{t}title-reports{/t}{/cap}</h4>

        <div class="row g-3">
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card py-3 text-center dashcard">
              <div class="card-body">
                <a
                  href="{Router::link('statement/verdictReport')}"
                  class="stretched-link">
                  {cap}{t}link-verdict-report{/t}{/cap}
                </a>
                ({$numBadVerdicts})
              </div>
            </div>
          </div>
        </div>
      {/if}
    {/if}
  </div>
{/block}
