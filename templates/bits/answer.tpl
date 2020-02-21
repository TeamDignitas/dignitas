{* mandatory argument: $answer *}
{$highlighted=$highlighted|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}
{$showComments=$showComments|default:true}
{$addComment=$addComment|default:false}

<div class="row voteContainer answer {if $highlighted}highlighted{/if}">
  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_ANSWER
      object=$answer
      upvotePriv=User::PRIV_UPVOTE_ANSWER
      downvotePriv=User::PRIV_DOWNVOTE_ANSWER}
  {/if}

  <div class="voteMain col-md-7 mb-3">
    {include "bits/answerMain.tpl"}
    <hr class="w-100 title-divider mt-1"/>
  </div>
</div>
