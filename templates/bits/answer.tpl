{* mandatory argument: $answer *}
{$highlighted=$highlighted|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}
<div class="answer clearfix {if $highlighted}highlightedAnswer{/if}">
  <div>
    {$answer->contents|md}
  </div>

  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_ANSWER
      object=$answer
      upvotePriv=User::PRIV_UPVOTE_ANSWER
      downvotePriv=User::PRIV_DOWNVOTE_ANSWER
      classes="float-left"}
  {/if}

  <ul class="list-inline text-muted float-right">
    <li class="list-inline-item">
      {t}posted by{/t}
      {include 'bits/userLink.tpl' u=$answer->getUser()}
      {$answer->createDate|moment}
    </li>
    {if $answer->isDeletable()}
      <li class="list-inline-item">
        <a
          href="?deleteAnswerId={$answer->id}"
          data-confirm="{t}Are you sure you want to delete this answer?{/t}">
          {t}delete{/t}
        </a>
      </li>
    {/if}

    {if $flagBox && ($answer->isFlaggable() || $answer->isFlagged())}
      <li class="list-inline-item">
        <a
          id="flagAnswerLink{$answer->id}"
          href="#"
          data-toggle="modal"
          data-target="#flagModal"
          data-object-type="{Flag::TYPE_ANSWER}"
          data-object-id="{$answer->id}"
          data-unflag-link="#unflagAnswerLink{$answer->id}"
          {if $answer->isFlagged()}hidden{/if}
        >
          <i class="icon icon-flag"></i>
          {t}flag{/t}
        </a>
        <a
          id="unflagAnswerLink{$answer->id}"
          href="#"
          class="unflag"
          data-object-type="{Flag::TYPE_ANSWER}"
          data-object-id="{$answer->id}"
          data-flag-link="#flagAnswerLink{$answer->id}"
          {if !$answer->isFlagged()}hidden{/if}
        >
          <i class="icon icon-flag-empty"></i>
          {t}unflag{/t}
        </a>
      </li>
    {/if}
  </ul>
</div>
