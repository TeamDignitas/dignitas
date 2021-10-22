{* mandatory argument: $statement *}
{$addComment=$addComment|default:false}
{$editLink=$editLink|default:false}
{$ellipsisMenu=$ellipsisMenu|default:true}
{$permalink=$permalink|default:false}
{$statusInfo=$statement->getStatusInfo()}
{$voteBox=$voteBox|default:true}

{$entity=$statement->getEntity()}

<h1 class="row statement-title py-4 px-0">
  {$statement->summary|escape}
  {if $statusInfo}
    [{$statusInfo['status']}]
  {/if}
  {if $permalink}
    <a
      href="{$statement->getViewUrl()}"
      title="{t}link-visit-statement-page{/t}">
      {include "bits/icon.tpl" i=insert_link}
    </a>
  {/if}
</h1>

<div class="row statement-authors-date px-0 mb-1">
  <span class="col-12 col-md-3 ps-0">
    {include "bits/entityLink.tpl" e=$statement->getEntity()}
  </span>
  <span class="col-12 col-md-3 ps-0">
    {$statement->dateMade|ld}
  </span>

  {if count($statement->getLinks())}
    <span class="col-12 col-md-6 ps-0 text-muted sources">
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

<hr class="row title-divider mt-0"/>

<article class="row mt-5 me-0">
  {if $statement->status == Ct::STATUS_DRAFT}
    {include "bits/draftIndicator.tpl"}
  {elseif $voteBox}
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

  <div class="col-12 col-md-7 px-0">
    {$statusInfo=$statement->getStatusInfo()}

    {if isset($pendingEditReview)}
      {notice icon=info}
        {t 1=$pendingEditReview->getUrl()}link-statement-review-pending-edit{/t}
      {/notice}
    {/if}

    {if $statusInfo}
      {notice icon=warning}
        {$statusInfo['details']}
        {if $statusInfo['dup']}
          {include "bits/statementLink.tpl" statement=$statusInfo['dup']}
        {/if}
        {if $statement->reason == Ct::REASON_BY_USER}
          {include "bits/userLink.tpl" u=$statement->getStatusUser()}
        {elseif $statement->reason != Ct::REASON_BY_OWNER}
          <hr>
          {include "bits/reviewFlagList.tpl" review=$statement->getRemovalReview()}
        {/if}
      {/notice}
    {/if}

    <h6 class="text-uppercase fw-bold">{t}title-context{/t}</h6>

    <div class="archivable">
      {$statement->context|md}
    </div>

    <h6 class="text-uppercase fw-bold">{t}title-goal{/t}</h6>

    {$statement->goal|escape}

    <div class="statement-read-only ms-0 mt-2">
      {if $statement->regionId}
        {$region=$statement->getRegion()}
        {* mimic tag presentation *}
        <span class="tag me-1">
          <a
            href="{Router::link('region/view')}/{$region->id}"
            class="badge rounded-pill bg-secondary py-1 px-2">
            {$region->name}
          </a>
        </span>
      {/if}

      {foreach $statement->getTags() as $t}
        {include "bits/tag.tpl" link=true tooltip="{t}info-tag-view-statements{/t}"}
      {/foreach}
      <div class="text-muted mb-2 mt-1 ps-0">
        {t}title-added-by{/t}
        {include 'bits/userLink.tpl' u=$statement->getUser()}
        {include 'bits/moment.tpl' t=$statement->createDate}
      </div>
    </div>

    {$comments=Comment::getFor($statement)}
    <div class="clearfix mb-2 mt-1 text-end">
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
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false">
          {include "bits/icon.tpl" i=more_vert}
        </button>

        <div class="dropdown-menu ellipsis-menu" aria-labelledby="statement-menu-{$statement->id}">
          {if $editLink}
            {include "bits/editButton.tpl" obj=$statement class="dropdown-item"}
          {/if}
          {include "bits/subscribeLinks.tpl" obj=$statement class="dropdown-item"}
          {include "bits/flagLinks.tpl" obj=$statement class="dropdown-item"}
          {include "bits/historyButton.tpl" obj=$statement class="dropdown-item"}
          {include "bits/viewMarkdownButton.tpl" obj=$statement}
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
        <div class="clearfix mb-2 mt-1 text-end">
          {include "bits/addCommentLink.tpl" object=$statement}
        </div>
      {/if}
    {/if}


  </div>

  <div class="verdict-area col-12 col-md-3 offset-md-1 pe-0">
    <aside class="card px-2 bg-verdict-{$statement->verdict}">
      <h5 class="card-title mt-4 mb-1 text-center fw-bold">
        {$statement->getEntity()}
      </h5>
      <span class="card-date mb-3">{$statement->dateMade|ld}</span>
      <span class="mx-auto">
        {include "bits/image.tpl"
          obj=$entity
          geometry=Config::THUMB_ENTITY_LARGE
          imgClass="rounded-circle img-fluid"}
      </span>
      <h6 class="card-body mx-auto text-center">
        <div class="capitalize-first-word">{$statement->getVerdictLabel()}:</div>
        <span class="text-uppercase">{$statement->getVerdictName()}</span>
      </h6>
    </aside>
  </div>

</article>
