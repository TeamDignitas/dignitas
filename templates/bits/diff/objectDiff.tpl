<div class="col-sm-6 col-md-2 pl-0 highlight-sm">
  {include "bits/userLink.tpl" u=$od->modUser}
</div>
<div class="col-sm-6 col-md-2 pl-0 highlight-sm">
  {include 'bits/moment.tpl' t=$od->modDate}
</div>

<div class="col-sm-11 offset-sm-1 col-md-8 offset-md-0">
  <div class="row">
    {foreach $od->getTextChanges() as $diff}
      <div class="col-md-3 pl-0">- {$diff.title}</div>
      <div class="col-md-9">
        {include "bits/diff/text.tpl" ses=$diff.ses}
      </div>
    {/foreach}

    {foreach $od->getFieldChanges() as $change}
      <div class="col-md-3 pl-0">- {$change.title}</div>
      <div class="col-md-9">
        {include "bits/diff/field.tpl"
          type=$change.type
          old=$change.old
          new=$change.new}
      </div>
    {/foreach}

    {if $od->duplicate}
      <div class="col-md-3 pl-0">- {t}label-duplicate-of{/t}</div>
      <div class="col-md-9">
        {if $od->duplicate instanceof Entity}
          {include "bits/entityLink.tpl" e=$od->duplicate}
        {elseif $od->duplicate instanceof Statement}
          {include "bits/statementLink.tpl" statement=$od->duplicate}
        {/if}
      </div>
    {/if}
  </div>
</div>

{if $od->review}
  {$id=$od->review->id}
  <div class="col-sm-12">
    <button
      class="btn btn-sm btn-light"
      type="button"
      data-toggle="collapse"
      data-target="#collapse-{$id}">
      <i class="icon icon-right-open"></i>
      {t}info-reviewed-because{/t}: {$od->review->getReasonName()}
    </button>
    <span class="btn btn-sm">
      {t}label-resolution{/t}:
      <span {if $od->review->status != Review::STATUS_KEEP}class="text-danger"{/if}>
        {$od->review->getResolutionName()}
      </span>
    </span>
  </div>

  <div class="col-sm-12">
    <div id="collapse-{$id}" class="card collapse mt-2">
      <div class="card-body">
        <h6 class="card-title">{t}title-votes{/t}</h6>
        <hr>
        {include "bits/reviewFlagList.tpl" review=$od->review}
      </div>
    </div>
  </div>
{/if}
