<td class="col-sm-3 col-md-2">
  {include "bits/userLink.tpl" u=$od->modUser}
</td>

<td class="col-sm-2 col-md-2">
  {include 'bits/moment.tpl' t=$od->modDate}
</td>

<td class="col-sm-7 col-md-8">
  <table class="table table-sm">
    {foreach $od->getTextChanges() as $diff}
      <tr class="d-flex">
        <td class="col-4 border-0">- {$diff.title}</td>
        <td class="col-8 border-0">
          {include "bits/diff/text.tpl" ses=$diff.ses}
        </td>
      </tr>
    {/foreach}

    {foreach $od->getFieldChanges() as $change}
      <tr class="d-flex">
        <td class="col-4 border-0">- {$change.title}</td>
        <td class="col-8 border-0">
          {include "bits/diff/field.tpl"
            type=$change.type
            old=$change.old
            new=$change.new}
        </td>
      </tr>
    {/foreach}

    {if $od->duplicate}
      <tr class="d-flex">
        <td class="col-4 border-0">- {t}label-duplicate-of{/t}</td>
        <td class="col-8 border-0">
          {if $od->duplicate instanceof Entity}
            {include "bits/entityLink.tpl" e=$od->duplicate}
          {elseif $od->duplicate instanceof Statement}
            {include "bits/statementLink.tpl" statement=$od->duplicate}
          {/if}
        </td>
      </tr>
    {/if}

    {if $od->review}
      {$id=$od->review->id}
      <tr class="d-flex">
        <td class="col-4 border-0">
          <button
            class="btn btn-sm btn-light"
            type="button"
            data-toggle="collapse"
            data-target="#collapse-{$id}">
            {include "bits/icon.tpl" i=chevron_right}
            {t}info-reviewed-because{/t}: {$od->review->getReasonName()}
          </button>
          <span class="btn btn-sm">
            {t}label-resolution{/t}:
            <span {if $od->review->status != Review::STATUS_KEEP}class="text-danger"{/if}>
              {$od->review->getResolutionName()}
            </span>
          </span>
        </td>

        <td class="col-8 border-0">
          <div id="collapse-{$id}" class="card collapse mt-2">
            <div class="card-body">
              <h6 class="card-title">{t}title-votes{/t}</h6>
              <hr>
              {include "bits/reviewFlagList.tpl" review=$od->review}
            </div>
          </div>
        </td>
      </tr>
    {/if}
  </table>
</td>
