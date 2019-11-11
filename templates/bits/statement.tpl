{* mandatory argument: $statement *}
{$editLink=$editLink|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}

<div class="clearfix">
  {include "bits/image.tpl"
    obj=$statement->getEntity()
    geometry=Config::THUMB_ENTITY_LARGE
    spanClass="col-3"
    imgClass="pic float-right ml-5"}

  <h3>{$statement->summary|escape}</h3>

  <p>
    — {include "bits/entityLink.tpl" e=$statement->getEntity()},
    {$statement->dateMade|ld}
  </p>

  {if $statement->status == Statement::STATUS_DELETED}
    <p class="text-danger">
      {t}This statement was deleted and is only visible to privileged users.{/t}
    </p>
  {/if}

  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_STATEMENT
      object=$statement
      upvotePriv=User::PRIV_UPVOTE_STATEMENT
      downvotePriv=User::PRIV_DOWNVOTE_STATEMENT}
  {/if}

  {if count($statement->getSources())}
    <div id="sources" class="my-2">
      {t}sources{/t}:
      <ul class="list-inline">
        {foreach $statement->getSources() as $s}
          <li class="list-inline-item">
            <a href="{$s->url}">{$s->getDisplayName()}</a>
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
  {if $editLink && $statement->isEditable()}
    <a href="{Router::link('statement/edit')}/{$statement->id}" class="btn btn-light">
      <i class="icon icon-edit"></i>
      {t}edit{/t}
    </a>
  {/if}

  {if $flagBox && ($statement->isFlaggable() || $statement->isFlagged())}
    <a
      id="flagStatementLink"
      href="#"
      class="btn text-muted btn-link"
      data-toggle="modal"
      data-target="#flagModal"
      data-object-type="{Flag::TYPE_STATEMENT}"
      data-object-id="{$statement->id}"
      data-unflag-link="#unflagStatementLink"
      {if $statement->isFlagged()}hidden{/if}
    >
      <i class="icon icon-flag"></i>
      {t}flag{/t}
    </a>
    <a
      id="unflagStatementLink"
      href="#"
      class="btn text-muted btn-link unflag"
      data-object-type="{Flag::TYPE_STATEMENT}"
      data-object-id="{$statement->id}"
      data-flag-link="#flagStatementLink"
      {if !$statement->isFlagged()}hidden{/if}
    >
      <i class="icon icon-flag-empty"></i>
      {t}unflag{/t}
    </a>
  {/if}

  <small class="btn text-muted float-right">
    {t}added by{/t}
    {include 'bits/userLink.tpl' u=$statement->getUser()}
    {$statement->createDate|moment}
  </small>
</div>
