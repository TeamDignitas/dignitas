{* mandatory argument: $statement *}
{$editLink=$editLink|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}

{$entity=$statement->getEntity()}

{if $voteBox || $entity->fileExtension}
  <div class="voteContainer">
    <div class="voteSidebar">

      {include "bits/image.tpl"
        obj=$entity
        geometry=Config::THUMB_ENTITY_MEDIUM
        imgClass="pic"}

      {if $voteBox}
        {include "bits/scoreAndVote.tpl"
          type=Vote::TYPE_STATEMENT
          object=$statement
          upvotePriv=User::PRIV_UPVOTE_STATEMENT
          downvotePriv=User::PRIV_DOWNVOTE_STATEMENT}
      {/if}
    </div>

    <div>
      {include "bits/statementMain.tpl"}
    </div>
  </div>
{else}
  {include "bits/statementMain.tpl"}
{/if}
