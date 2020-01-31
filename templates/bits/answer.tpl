{* mandatory argument: $answer *}
{$highlighted=$highlighted|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}

<div class="answer {if $highlighted}highlighted{/if}">
  {if $voteBox}
    <div class="voteContainer">
      <div class="voteSidebar">

        {if $voteBox}
          {include "bits/scoreAndVote.tpl"
            type=Vote::TYPE_ANSWER
            object=$answer
            upvotePriv=User::PRIV_UPVOTE_ANSWER
            downvotePriv=User::PRIV_DOWNVOTE_ANSWER}
        {/if}

      </div>

      <div class="voteMain">
        {include "bits/answerMain.tpl"}
      </div>
    </div>
  {else}
    <div class="voteMain">
      {include "bits/answerMain.tpl"}
    </div>
  {/if}
</div>
