{strip}
<pre class="diff">
  {foreach $ses as $cmd}
    <span class="diff-op{$cmd.0}">{$cmd.1|escape}</span>
  {/foreach}
</pre>
{/strip}
