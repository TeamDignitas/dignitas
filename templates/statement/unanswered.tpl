{extends "layout.tpl"}

{block "title"}{cap}{t}title-unanswered-statements{/t}{/cap}{/block}

{block "content"}

  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-unanswered-statements{/t}{/cap}</h1>

    <div>
      {include "bits/statementFilters.tpl" verdicts=$verdicts}

      <div id="statement-list-wrapper">
        {include "bits/statementList.tpl" statements=$statements}
      </div>

      {include "bits/paginationWrapper.tpl"
        n=$numStatementPages
        url="{Config::URL_PREFIX}ajax/search-statements"
        target="#statement-list-wrapper"}
    </div>
  </div>

{/block}
