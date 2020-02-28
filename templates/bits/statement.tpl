{* mandatory argument: $statement *}
{$editLink=$editLink|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}
{$addComment=$addComment|default:false}
{$statusInfo=$statement->getStatusInfo()}

{$entity=$statement->getEntity()}

<div class="row statement-title-sources">

  <div class="col-md-12 pl-0">
    <h1 class="statement-title">
      {$statement->summary|escape}
      {if $statusInfo}
        [{$statusInfo['status']}]
      {/if}
    </h1>

    <h6 class="col-md-12 statement-authors-date px-0">
      {include "bits/entityLink.tpl" e=$statement->getEntity()},
      {$statement->dateMade|ld}

      {if count($statement->getLinks())}
        <span class="text-muted mb-3 pl-3 sources">
          {t}statement-links{/t}:
          <ul class="list-inline list-inline-bullet d-inline">
            {foreach $statement->getLinks() as $l}
              <li class="list-inline-item">
                {include "bits/link.tpl"}
              </li>
            {/foreach}
          </ul>
        </span>
      {/if}
    </h6>

  </div>
  <hr class="w-100 title-divider mt-0"/>
</div>

<article class="row mt-3 statement-body">
  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_STATEMENT
      object=$statement
      upvotePriv=User::PRIV_UPVOTE_STATEMENT
      downvotePriv=User::PRIV_DOWNVOTE_STATEMENT}
  {/if}

  <div class="voteMain col-md-7 col-sm-10 pl-0">
    {$statusInfo=$statement->getStatusInfo()}

    {if isset($pendingEditReview)}
      <div class="alert alert-warning mx-5">
        {t 1=$pendingEditReview->getUrl()}link-statement-review-pending-edit{/t}
      </div>
    {/if}

    {if $statusInfo}
      <div class="alert {$statusInfo['cssClass']} overflow-hidden">
        {$statusInfo['details']}
        {if $statusInfo['dup']}
          {include "bits/statementLink.tpl"
            statement=$statusInfo['dup']
            class="alert-link"}
        {/if}
        {if $statement->reason == Ct::REASON_BY_USER}
          {include "bits/userLink.tpl" u=$statement->getStatusUser()}
        {elseif $statement->reason != Ct::REASON_BY_OWNER}
          <hr>
          {include "bits/reviewFlagList.tpl" flags=$statement->getReviewFlags()}
        {/if}
      </div>
    {/if}

    <h6 class="text-uppercase font-weight-bold">{t}title-context{/t}</h6>

    {$statement->context|md}

    <h6 class="text-uppercase font-weight-bold">{t}title-goal{/t}</h6>

    {$statement->goal|escape}

    <div class="statement-read-only row ml-0">
      {foreach $statement->getTags() as $t}
        {include "bits/tag.tpl"}
      {/foreach}
      <div class="text-muted col-md-12 text-right mb-2">
        {t}title-added-by{/t}
        {include 'bits/userLink.tpl' u=$statement->getUser()}
        {include 'bits/moment.tpl' t=$statement->createDate}
      </div>
    </div>

    <div class="clearfix statement-actions row mb-2">
      <div class="col-md-12 mt-1 text-right">
        {if $statement->hasRevisions()}
          <a href="{Router::link('statement/history')}/{$statement->id}" class="btn btn-sm btn-outline-secondary">
            {t}link-show-revisions{/t}
          </a>
        {/if}

        {if $editLink}
          {include "bits/editButton.tpl" obj=$statement}
        {/if}

        {if $flagBox && ($statement->isFlaggable() || $statement->isFlagged())}
          {include "bits/flagLinks.tpl" obj=$statement class="btn btn-sm btn-outline-secondary"}
        {/if}

        {foreach Comment::getFor($statement) as $comment}
          {include 'bits/comment.tpl'}
        {/foreach}

        {if $addComment}
          {include "bits/addCommentLink.tpl" object=$statement}
        {/if}
      </div>
    </div>

  </div>

  <div class="statement-box-area col-md-3 offset-md-1 pr-0">
    <aside class="statement-box card false-statement verdict-{$statement->verdict}">
      <h6 class="card-title mt-3 text-center">
        {$statement->getEntity()},
        {$statement->dateMade|ld}
      </h6>
      <span class="mx-auto">
        {include "bits/image.tpl"
          obj=$entity
          geometry=Config::THUMB_ENTITY_LARGE
          spanClass=""
          imgClass="pic person-photo rounded-circle img-fluid"}
      </span>
      <h4 class="card-body mx-auto">
        {t}label-verdict{/t}:
        {$statement->getVerdictName()}
      </h4>
    </aside>
  </div>

</article>
