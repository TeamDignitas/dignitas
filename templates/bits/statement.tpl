{* mandatory argument: $statement *}
{$editLink=$editLink|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}
{$addComment=$addComment|default:false}
{$statusInfo=$statement->getStatusInfo()}

{$entity=$statement->getEntity()}

<div class="row statement">

  <div class="col-md-12">
    <h1 class="statement-title">
      {$statement->summary|escape}
      {if $statusInfo}
        [{$statusInfo['status']}]
      {/if}
    </h1>

    <h5 class="col-md-12 statement-authors-date">
      {include "bits/entityLink.tpl" e=$statement->getEntity()},
      {$statement->dateMade|ld}

      {if count($statement->getLinks())}
        <span class="text-muted mb-3 sources">
          {t}sources{/t}:
          <ul class="list-inline list-inline-bullet d-inline">
            {foreach $statement->getLinks() as $l}
              <li class="list-inline-item">
                {include "bits/link.tpl"}
              </li>
            {/foreach}
          </ul>
        </span>
      {/if}

    </h5>
  </div>
  <hr class="w-100"/>

  <article class="row">
    {if $voteBox}
      {include "bits/scoreAndVote.tpl"
        type=Vote::TYPE_STATEMENT
        object=$statement
        upvotePriv=User::PRIV_UPVOTE_STATEMENT
        downvotePriv=User::PRIV_DOWNVOTE_STATEMENT}
    {/if}

    <div class="voteMain col-md-7">
      {$statusInfo=$statement->getStatusInfo()}



      {if isset($pendingEditReview)}
        <div class="alert alert-warning mx-5">
          {t 1=$pendingEditReview->getUrl()}
          This statement has a pending edit. You can
          <a href="%1" class="alert-link">review it</a>.{/t}
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

      <h5 class="text-uppercase">{t}context{/t}</h4>

      {$statement->context|md}

      <h5 class="text-uppercase">{t}goal{/t}</h4>

      {$statement->goal|escape}

      <div>
        {foreach $statement->getTags() as $t}
          {include "bits/tag.tpl"}
        {/foreach}
      </div>

      <div class="my-3 clearfix">
        {if $editLink}
          {include "bits/editButton.tpl" obj=$statement}
        {/if}

        {if $flagBox && ($statement->isFlaggable() || $statement->isFlagged())}
          {include "bits/flagLinks.tpl" obj=$statement class="btn btn-link text-muted"}
        {/if}

        <small class="btn text-muted float-right">
          {t}added by{/t}
          {include 'bits/userLink.tpl' u=$statement->getUser()}
          {include 'bits/moment.tpl' t=$statement->createDate}

          {if $statement->hasRevisions()}
            •
            <a href="{Router::link('statement/history')}/{$statement->id}">
              {t}show revisions{/t}
            </a>
          {/if}
        </small>
      </div>

      {foreach Comment::getFor($statement) as $comment}
        {include 'bits/comment.tpl'}
      {/foreach}

      {if $addComment}
        {include "bits/addCommentLink.tpl" object=$statement}
      {/if}
    </div>

    <div class="statement-box-area col-md-3 offset-md-1">
      <aside class="statement-box card false-statement bg-danger text-white">
        <h5 class="card-title mt-3 mx-auto">
              {$statement->getEntity()},
              {$statement->dateMade|ld}
        </h5>
        <span class="mx-auto">
          {include "bits/image.tpl"
            obj=$entity
            geometry=Config::THUMB_ENTITY_LARGE
            spanClass=""
            imgClass="pic rounded-circle"}
        </span>
        <h4 class="card-body mx-auto">Afirmație FALSĂ</h4>
      </aside>
    </div>

  </article>
</div>
