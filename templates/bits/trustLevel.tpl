{$trustLevel=$trustLevel|default:null}

{if $trustLevel}
  <div class="mt-4 mb-3 d-flex justify-content-center">
    {$class=$trustLevel->getClass()}
    {$deg=$trustLevel->value*360}

    <div class="trust-wrapper">
      <div class="trust-arc trust-arc-{$class}" style="--angle: {$deg}deg"></div>
      <div class="trust-circle"></div>
      <div class="trust-msg trust-msg-{$class}">
        {cap}{t}label-trust-level{/t}{/cap}<br>
        {$trustLevel->getMessage()}
      </div>
    </div>
  </div>

  <div class="mb-4 text-muted trust-level-update">
    {t}label-last-update{/t} {$trustLevel->lastTimestamp|lt:false}
  </div>
{/if}
