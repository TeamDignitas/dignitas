{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}

<div class="voteContainer comment">

  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_COMMENT
      object=$comment
      upvotePriv=User::PRIV_UPVOTE_COMMENT
      downvotePriv=User::PRIV_DOWNVOTE_COMMENT
      position="before"}
  {/if}

  <div class="voteMain">
    {$comment->contents|md}
    &mdash;
    {include 'bits/userLink.tpl' u=$comment->getUser()}
    {include 'bits/moment.tpl' t=$comment->createDate}
  </div>

</div>
