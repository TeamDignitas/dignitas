{extends "layout.tpl"}

{block "title"}{cap}{t}title-verdict-report{/t}{/cap}{/block}

{block "content"}

  <h2 class="mb-4">{cap}{t}title-verdict-report{/t}{/cap}</h2>

  {if count($map['proofNoVerdict'])}

    <h5 class="alert alert-secondary capitalize-first-word">{t}title-statements-proof-no-verdict{/t}</h5>

    <ol>
      {foreach $map['proofNoVerdict'] as $statement}
        <li>
          {include 'bits/statementLink.tpl'}
        </li>
      {/foreach}
    </ol>

  {/if}

  {if count($map['verdictNoProof'])}

    <h5 class="alert alert-secondary capitalize-first-word">{t}title-statements-verdict-no-proof{/t}</h5>

    <ol>
      {foreach $map['verdictNoProof'] as $statement}
        <li>
          {include 'bits/statementLink.tpl'}
        </li>
      {/foreach}
    </ol>

  {/if}

  {if count($map['verdictMismatch'])}

    <h5 class="alert alert-secondary capitalize-first-word">{t}title-statements-verdict-mismatch{/t}</h5>

    <ol>
      {foreach $map['verdictMismatch'] as $statement}
        <li>
          {include 'bits/statementLink.tpl'}
        </li>
      {/foreach}
    </ol>

  {/if}

{/block}
