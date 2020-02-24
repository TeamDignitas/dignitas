{extends "layout.tpl"}

{block "title"}{cap}{t}title-verdict-report{/t}{/cap}{/block}

{block "content"}

  <h3>{cap}{t}title-verdict-report{/t}{/cap}</h3>

  {if count($map['proofNoVerdict'])}

    <h4>{t}title-statements-proof-no-verdict{/t}</h4>

    {foreach $map['proofNoVerdict'] as $statement}
      <div>
        {include 'bits/statementLink.tpl'}
      </div>
    {/foreach}

  {/if}

  {if count($map['verdictNoProof'])}

    <h4>{t}title-statements-verdict-no-proof{/t}</h4>

    {foreach $map['verdictNoProof'] as $statement}
      <div>
        {include 'bits/statementLink.tpl'}
      </div>
    {/foreach}

  {/if}

  {if count($map['verdictMismatch'])}

    <h4>{t}title-statements-verdict-mismatch{/t}</h4>

    {foreach $map['verdictMismatch'] as $statement}
      <div>
        {include 'bits/statementLink.tpl'}
      </div>
    {/foreach}

  {/if}

{/block}
