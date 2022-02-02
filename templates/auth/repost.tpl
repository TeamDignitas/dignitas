{extends "layout.tpl"}

{block "title"}{cap}{t}title-redirecting{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h3>{t}title-redirecting{/t}</h3>
    <form id="form-repost" action="{$referrer|esc}" method="post">
      {foreach $postData as $key => $value}
        {if is_array($value)}
          {foreach $value as $v}
            <input type="hidden" name="{$key|esc}[]" value="{$v|esc}">
          {/foreach}
        {else}
          <input type="hidden" name="{$key|esc}" value="{$value|esc}">
        {/if}
      {/foreach}
      <button type="submit" class="btn btn-link">
        {t}link-repost-manual{/t}
      </button>
    </form>
    <script type="text/javascript">
      document.getElementById('form-repost').submit();
    </script>
  </div>
{/block}
