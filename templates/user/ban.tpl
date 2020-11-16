{extends "layout.tpl"}

{block "title"}{t}title-ban-user{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{t}title-ban-user{/t} {$user}</h1>

    <form method="post">
      <input type="hidden" name="id" value="{$user->id}">

      <div class="form-group row font-weight-bold">
        <label class="col-sm-3 col-form-label">
          {t}label-type{/t}
        </label>
        <label class="col-sm-9 col-form-label">
          {t}label-duration{/t}
        </label>
      </div>

      {for $t = 1 to Ban::NUM_TYPES}
        <div class="form-group row">
          <label for="ban-type-{$t}" class="col-sm-3 col-form-label">
            {Ban::typeName($t)}
          </label>
          <div class="col-sm-9">
            <select
              id="ban-type-{$t}"
              name="banDuration[{$t}]"
              class="form-control">
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
          </div>
        </div>
      {/for}

      <div class="mt-4 text-right">
        <a href="{Router::userLink($user)}" class="btn btn-sm btn-outline-secondary col-sm-4 col-lg-2 mr-2 mb-2">
          <i class="icon icon-cancel"></i>
          {t}link-cancel{/t}
        </a>

        <button name="saveButton" type="submit" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
          <i class="icon icon-floppy"></i>
          {t}link-save{/t}
        </button>
      </div>
    </form>
  </div>
{/block}
