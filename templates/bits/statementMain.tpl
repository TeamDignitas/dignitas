{$statusInfo=$statement->getStatusInfo()}

{include "bits/image.tpl"
  obj=$entity
  geometry=Config::THUMB_ENTITY_MEDIUM
  spanClass="float-right"
  imgClass="pic"}

<h3>
  {$statement->summary|escape}
  {if $statusInfo}
    [{$statusInfo['status']}]
  {/if}
</h3>

<h5>
  {include "bits/entityLink.tpl" e=$statement->getEntity()},
  {$statement->dateMade|ld}
</h5>

{if count($statement->getLinks())}
  <div class="text-muted mb-3">
    {t}statement-links{/t}:
    <ul class="list-inline list-inline-bullet d-inline">
      {foreach $statement->getLinks() as $l}
        <li class="list-inline-item">
          {include "bits/link.tpl"}
        </li>
      {/foreach}
    </ul>
  </div>
{/if}

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

<h4>{t}title-context{/t}</h4>

{$statement->context|md}

<h4>{t}title-goal{/t}</h4>

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
    {t}title-added-by{/t}
    {include 'bits/userLink.tpl' u=$statement->getUser()}
    {include 'bits/moment.tpl' t=$statement->createDate}

    {if $statement->hasRevisions()}
      •
      <a href="{Router::link('statement/history')}/{$statement->id}">
        {t}link-show-revisions{/t}
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
