{* mandatory argument: $statement *}
{$editLink=$editLink|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}

{$statusInfo=$statement->getStatusInfo()}

<div class="clearfix">
  {include "bits/image.tpl"
    obj=$statement->getEntity()
    geometry=Config::THUMB_ENTITY_LARGE
    spanClass="col-3"
    imgClass="pic float-right ml-5"}

  <h3>
    {$statement->summary|escape}
    {if $statusInfo}
      [{$statusInfo['status']}]
    {/if}
  </h3>

  <p>
    — {include "bits/entityLink.tpl" e=$statement->getEntity()},
    {$statement->dateMade|ld}
  </p>

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

  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_STATEMENT
      object=$statement
      upvotePriv=User::PRIV_UPVOTE_STATEMENT
      downvotePriv=User::PRIV_DOWNVOTE_STATEMENT}
  {/if}

  {if count($statement->getSources())}
    <div class="my-2">
      {t}sources{/t}:
      <ul class="list-inline list-inline-bullet d-inline">
        {foreach $statement->getSources() as $s}
          <li class="list-inline-item">
            {include "bits/statementSource.tpl"}
          </li>
        {/foreach}
      </ul>
    </div>
  {/if}

  <h4>{t}context{/t}</h4>

  {$statement->context|md}

  <h4>{t}goal{/t}</h4>

  {$statement->goal|escape}

  <div>
    {foreach $statement->getTags() as $t}
      {include "bits/tag.tpl"}
    {/foreach}
  </div>
</div>

<div class="mt-3 clearfix">
  {if $editLink}
    {include "bits/editButton.tpl" obj=$statement}
  {/if}

  {if $flagBox && ($statement->isFlaggable() || $statement->isFlagged())}
    {include "bits/flagLinks.tpl" obj=$statement class="btn btn-link text-muted"}
  {/if}

  <small class="btn text-muted float-right">
    {t}added by{/t}
    {include 'bits/userLink.tpl' u=$statement->getUser()}
    {$statement->createDate|moment}

    {if $statement->hasRevisions() && $statement->modUserId != $statement->userId}
      •
      <a href="{Router::link('statement/history')}/{$statement->id}">
        {t}edited{/t}
      </a>
      {$statement->modDate|moment}
    {/if}
  </small>
</div>
