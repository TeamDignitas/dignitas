{* mandatory argument: $answer *}
{$highlighted=$highlighted|default:false}
{$voteBox=$voteBox|default:true}
{$addComment=$addComment|default:false}

<div class="row vote-container answer {if $highlighted}highlighted{/if}">
  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_ANSWER
      object=$answer
      upvotePriv=User::PRIV_UPVOTE_ANSWER
      downvotePriv=User::PRIV_DOWNVOTE_ANSWER}
  {/if}

  <div class="col-md-7 col-sm-10 mb-1 px-0">
    {include "bits/answerMain.tpl"}
    <hr class="w-100 title-divider mt-3 mb-0"/>
  </div>
</div>
