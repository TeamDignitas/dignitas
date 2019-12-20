{strip}
<pre class="diff">
  {foreach $ses as $cmd}
    <span class="diffOp diffOp{$cmd.0}">{$cmd.1|escape}</span>
  {/foreach}
</pre>
{/strip}
