{extends "layout.tpl"}

{block "title"}{t}title-ban-user{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-ban-user{/t} {$user}</h1>

    <form method="post">
      <input type="hidden" name="id" value="{$user->id}">

      <div class="fw-bold">
        {hf
          breakpoint='md'
          col=4
          label="{t}label-type{/t}"}
          <label class="col-form-label">
            {t}label-duration{/t}
          </label>
        {/hf}
      </div>

      {for $t = 1 to Ban::NUM_TYPES}
        {hf
          inputId="ban-type-{$t}"
          breakpoint='md'
          col=4
          label=Ban::typeName($t)}
          <select
            id="ban-type-{$t}"
            name="banDuration[{$t}]"
            class="form-select">
            <option value="0">{t}ban-duration-none{/t}</option>
            <option value="10">{t}ban-duration-10-minutes{/t}</option>
            <option value="30">{t}ban-duration-30-minutes{/t}</option>
            <option value="60">{t}ban-duration-1-hour{/t}</option>
            <option value="120">{t}ban-duration-2-hours{/t}</option>
            <option value="360">{t}ban-duration-6-hour{/t}</option>
            <option value="720">{t}ban-duration-12-hour{/t}</option>
            <option value="1440">{t}ban-duration-1-day{/t}</option>
            <option value="4320">{t}ban-duration-3-days{/t}</option>
            <option value="10080">{t}ban-duration-1-week{/t}</option>
            <option value="20160">{t}ban-duration-2-weeks{/t}</option>
            <option value="43200">{t}ban-duration-1-month{/t}</option>
            <option value="43200">{t}ban-duration-3-months{/t}</option>
            <option value="43200">{t}ban-duration-6-months{/t}</option>
            <option value="{Ban::EXPIRATION_NEVER}">{t}ban-duration-forever{/t}</option>
          </select>
        {/hf}
      {/for}

      {include "bs/actions.tpl" cancelLink=Router::userLink($user)}

    </form>
  </div>
{/block}
