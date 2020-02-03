{* mandatory argument: $statement *}
{$editLink=$editLink|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}
{$addComment=$addComment|default:false}

{$entity=$statement->getEntity()}

<div class="voteContainer statement">
  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_STATEMENT
      object=$statement
      upvotePriv=User::PRIV_UPVOTE_STATEMENT
      downvotePriv=User::PRIV_DOWNVOTE_STATEMENT}
  {/if}

  <div class="voteMain">
    {include "bits/statementMain.tpl"}
  </div>
</div>
