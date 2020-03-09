{strip}
<pre class="diff mt-1 mb-0">
  {foreach $ses as $cmd}
    <span class="diffOp diffOp{$cmd.0}">{$cmd.1|escape}</span>
  {/foreach}
</pre>
{/strip}
