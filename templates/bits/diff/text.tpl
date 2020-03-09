{foreach $ses as $cmd}
  {include "bits/diff/ses.tpl" ses=$cmd.diff}

  {if $cmd.copyLineCount}
    <div>
      <button class="identical-toggle btn btn-sm btn-outline-secondary">
        {t
          count=$cmd.copyLineCount
          1=$cmd.copyLineCount
          plural="identical-lines-skipped-plural"}
        identical-lines-skipped-singular
        {/t}
      </button>

      {strip}
      <pre class="diff identical-block">
        {$cmd.copy}
      </pre>
      {/strip}
    </div>
  {/if}
{/foreach}
