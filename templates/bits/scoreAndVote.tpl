{* $type: type of object being voted *}
{* $object: object being voted (should have id and score fields) *}
{* $classes: additional CSS classes for the wrapper div *}

{$voteValue=$object->getVote()->value|default:0}

<div class="scoreAndVote {$classes}">
  <button
    class="btn btn-sm btn-light voteButton {if $voteValue == 1}voted{/if}"
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-value="1">
    <i class="icon icon-thumbs-up-alt"></i>
  </button>

  {t}score{/t}: <span class="score">{$object->score}</span>

  <button
    class="btn btn-sm btn-light voteButton {if $voteValue == -1}voted{/if}"
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-value="-1">
    <i class="icon icon-thumbs-down-alt"></i>
  </button>

</div>
