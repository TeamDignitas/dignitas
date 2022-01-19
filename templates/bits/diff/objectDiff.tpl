{$leftCol='col-12 col-md-5 col-xl-3 text-nowrap'} {* For the first line only *}
{$rightCol='col-12 col-md-7 col-xl-9'}
<tr class="align-baseline">
  <td class="text-nowrap">
    {include "bits/userLink.tpl" u=$od->modUser}
  </td>

  <td class="text-nowrap">
    {include 'bits/moment.tpl' t=$od->modDate}
  </td>

  <td class="container">
    {foreach $od->getTextChanges() as $diff}
      <div class="row mb-2">
        <div class="{$leftCol}">
          - {$diff.title}
        </div>
        <div class="{$rightCol}">
          {include "bits/diff/text.tpl" ses=$diff.ses}
        </div>
      </div>
    {/foreach}

    {foreach $od->getFieldChanges() as $change}
      <div class="row mb-2">
        <div class="{$leftCol}">
          - {$change.title}
        </div>
        <div class="{$rightCol}">
          {include "bits/diff/field.tpl"
            type=$change.type
            old=$change.old
            new=$change.new}
        </div>
      </div>
    {/foreach}

    {if $od->duplicate}
      <div class="row mb-2">
        <div class="{$leftCol}">
          - {t}label-duplicate-of{/t}
        </div>
        <div class="{$rightCol}">
          {if $od->duplicate instanceof Entity}
            {include "bits/entityLink.tpl" e=$od->duplicate}
          {elseif $od->duplicate instanceof Statement}
            {include "bits/statementLink.tpl" statement=$od->duplicate}
          {/if}
        </div>
      </div>
    {/if}

    {if $od->review}
      {$id=$od->review->id}
      <div class="row mb-2">
        <div class="col-12">
          <button
            class="btn btn-sm btn-link text-wrap"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse-{$id}">
            {include "bits/icon.tpl" i=chevron_right}
            {t}info-reviewed-because{/t}: {$od->review->getReasonName()}
          </button>
          <span class="btn btn-sm">
            {t}label-resolution{/t}:
            <span {if $od->review->status != Review::STATUS_KEEP}class="text-danger"{/if}>
              {$od->review->getResolutionName()}
            </span>
          </span>
        </div>

        <div class="col-12">
          <div id="collapse-{$id}" class="card collapse mt-2">
            <div class="card-body">
              <h6 class="card-title">{t}title-votes{/t}</h6>
              <hr>
              {include "bits/reviewFlagList.tpl" review=$od->review}
            </div>
          </div>
        </div>
      </div>
    {/if}
  </td>
</tr>
