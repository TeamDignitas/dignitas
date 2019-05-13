{extends "layout.tpl"}

{block "title"}{cap}{t}redirecting{/t}{/cap}{/block}

{block "content"}
  <h3>{t}Redirecting you back{/t}</h3>
  <form id="repostForm" action="{$referrer}" method="post">
    {foreach $postData as $key => $value}
      {if is_array($value)}
        {foreach $value as $v}
          <input type="hidden" name="{$key|escape}[]" value="{$v|escape}">
        {/foreach}
      {else}
        <input type="hidden" name="{$key|escape}" value="{$value|escape}">
      {/if}
    {/foreach}
    <button type="submit" class="btn btn-link">
      {t}click here if you are not redirected automatically{/t}
    </button>
  </form>
  <script type="text/javascript">
    document.getElementById('repostForm').submit();
  </script>
{/block}
