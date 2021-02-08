{* mandatory argument: $statement *}
{$addComment=$addComment|default:false}
{$editLink=$editLink|default:false}
{$ellipsisMenu=$ellipsisMenu|default:true}
{$permalink=$permalink|default:false}
{$statusInfo=$statement->getStatusInfo()}
{$voteBox=$voteBox|default:true}

{$entity=$statement->getEntity()}

<div class="row statement-title-sources">

  <h1 class="statement-title py-4">
    {$statement->summary|escape}
    {if $statusInfo}
      [{$statusInfo['status']}]
    {/if}
    {if $permalink}
      <a
        href="{$statement->getViewUrl()}"
        title="{t}link-visit-statement-page{/t}">
        <i class="icon icon-link"></i>
      </a>
    {/if}
  </h1>

  <div class="col-12 statement-authors-date px-0 mb-1">
    <span class="mr-5">
      {include "bits/entityLink.tpl" e=$statement->getEntity()},
      {$statement->dateMade|ld}
    </span>

    {if count($statement->getLinks())}
      <span class="text-muted mb-3 sources">
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
  </div>

  <hr class="w-100 title-divider mt-0"/>
</div>

<article class="row mt-3 statement-body">
  {if $voteBox}
    {capture "tooltipDownvote"}
    {t
      count=-Config::REP_DOWNVOTE_STATEMENT
      1=-Config::REP_DOWNVOTE_STATEMENT
      plural="tooltip-downvote-statement-plural-%1"}
    tooltip-downvote-statement-singular-%1
    {/t}
    {/capture}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_STATEMENT
      object=$statement
      upvotePriv=User::PRIV_UPVOTE_STATEMENT
      downvotePriv=User::PRIV_DOWNVOTE_STATEMENT
      tooltipUpvote="{t}tooltip-upvote-statement{/t}"
      tooltipDownvote="{$smarty.capture.tooltipDownvote}"
    }
  {/if}

  <div class="col-sm-12 col-md-7 px-0">
    {$statusInfo=$statement->getStatusInfo()}

    {if isset($pendingEditReview)}
      <div class="alert alert-warning mx-5">
        {t 1=$pendingEditReview->getUrl()}link-statement-review-pending-edit{/t}
      </div>
    {/if}

    {if $statusInfo}
      <div class="alert {$statusInfo['cssClass']} small overflow-hidden">
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
          {include "bits/reviewFlagList.tpl" review=$statement->getRemovalReview()}
        {/if}
      </div>
    {/if}

    <h6 class="text-uppercase font-weight-bold">{t}title-context{/t}</h6>

    {$statement->context|md}

    <h6 class="text-uppercase font-weight-bold">{t}title-goal{/t}</h6>

    {$statement->goal|escape}

    <div class="statement-read-only row ml-0 mt-2">
      {foreach $statement->getTags() as $t}
        {include "bits/tag.tpl" link=true tooltip="{t}info-tag-view-statements{/t}"}
      {/foreach}
      <div class="text-muted col-md-12 mb-2 mt-1 pl-0">
        {t}title-added-by{/t}
        {include 'bits/userLink.tpl' u=$statement->getUser()}
        {include 'bits/moment.tpl' t=$statement->createDate}
      </div>
    </div>

    {$comments=Comment::getFor($statement)}
    <div class="clearfix mb-2 mt-1 text-right">
      {* when there are no comments, the add comment button sits on the same
         row as the other buttons *}
      {if empty($comments) && $addComment}
        {include "bits/addCommentLink.tpl" object=$statement}
      {/if}

      {if $ellipsisMenu}
        <button
          class="btn"
          type="button"
          id="statement-menu-{$statement->id}"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false">
          <i class="icon icon-ellipsis-vert"></i>
        </button>

        <div class="dropdown-menu ellipsis-menu" aria-labelledby="statement-menu-{$statement->id}">
          {if $editLink}
            {include "bits/editButton.tpl" obj=$statement class="dropdown-item"}
          {/if}
          {include "bits/subscribeLinks.tpl" obj=$statement class="dropdown-item"}
          {include "bits/flagLinks.tpl" obj=$statement class="dropdown-item"}
          {include "bits/historyButton.tpl" obj=$statement class="dropdown-item"}
        </div>
      {/if}
    </div>

    {if !empty($comments)}
      <div class="comment-list">
        {foreach $comments as $comment}
          {include 'bits/comment.tpl'}
        {/foreach}
      </div>

      {* when there are comments, the add comment button sits on a separate row *}
      {if $addComment}
        <div class="clearfix mb-2 mt-1 text-right">
          {include "bits/addCommentLink.tpl" object=$statement}
        </div>
      {/if}
    {/if}


  </div>

  <div class="verdict-area col-sm-12 col-md-3 offset-md-1 pr-0">
    <aside class="card px-2 bg-verdict-{$statement->verdict}">
      <h5 class="card-title mt-4 mb-1 text-center font-weight-bold">
        {$statement->getEntity()}
      </h5>
      <span class="card-date mb-3">{$statement->dateMade|ld}</span>
      <span class="mx-auto">
        {include "bits/image.tpl"
          obj=$entity
          geometry=Config::THUMB_ENTITY_LARGE
          spanClass=""
          imgClass="pic rounded-circle img-fluid no-outline"}
      </span>
      <h6 class="card-body mx-auto text-center">
        <div class="capitalize-first-word">{$statement->getVerdictLabel()}:</div>
        <span class="text-uppercase">{$statement->getVerdictName()}</span>
      </h6>
    </aside>
  </div>

</article>
