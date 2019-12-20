{$ses=Diff::sesText($from, $to)}

{foreach $ses as $cmd}
  {include "bits/diff/ses.tpl" ses=$cmd.diff}

  {if $cmd.copyLineCount}
    <div>
      <button class="identicalToggle btn btn-light">
        {t
          count=$cmd.copyLineCount
          1=$cmd.copyLineCount
          plural="%1 identical lines skipped"}
        one identical line skipped
        {/t}
      </button>

      {strip}
      <pre class="diff identicalBlock">
        {$cmd.copy}
      </pre>
      {/strip}
    </div>
  {/if}
{/foreach}
