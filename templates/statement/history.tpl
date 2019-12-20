{extends "layout.tpl"}

{block "title"}
  {t}Statement history for{/t}:
  {cap}{$history[0]->summary|escape}{/cap}
{/block}

{block "content"}
  {for $i = 0; $i < count($history) - 1; $i++}
    {$new=$history[$i]}
    {$old=$history[$i + 1]}
    <h4 class="versionHeader">
      {t}changes by{/t}
      {include "bits/userLink.tpl" u=$new->getModUser()}
      {$new->modDate|lt:false:true}
    </h4>

    {include "bits/diff/card.tpl"
      title="{t}changes to summary{/t}"
      old=$old->summary
      new=$new->summary}
    {include "bits/diff/card.tpl"
      title="{t}changes to context{/t}"
      old=$old->context
      new=$new->context}
    {include "bits/diff/card.tpl"
      title="{t}changes to goal{/t}"
      old=$old->goal
      new=$new->goal}

    <dl class="row">
      {if $old->dateMade != $new->dateMade}
        {include "bits/diff/field.tpl"
          name="{t}statement date{/t}"
          old=$old->dateMade|ld
          new=$new->dateMade|ld}
      {/if}
      {if $old->entityId != $new->entityId}
        {include "bits/diff/field.tpl"
          name="{t}author{/t}"
          old=$old->getEntity()
          new=$new->getEntity()}
      {/if}
      {if $old->status != $new->status}
        {include "bits/diff/field.tpl"
          name="{t}status{/t}"
          old=$old->getStatusName()
          new=$new->getStatusName()}
      {/if}
    </dl>
  {/for}

{/block}
