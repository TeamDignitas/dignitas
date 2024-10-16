{$val=$val|default:TrustLevel::UNDEFINED}

{if $val != TrustLevel::UNDEFINED}
  {$class=TrustLevel::getClass($val)}
  {$deg=$val*360}

  <div class="trust-wrapper">
    <div class="trust-arc trust-arc-{$class}" style="--angle: {$deg}deg"></div>
    <div class="trust-circle"></div>
    <div class="trust-msg trust-msg-{$class}">
      {cap}{t}label-trust-level{/t}{/cap}<br>
      {TrustLevel::getMessage($val)}
    </div>
  </div>
{/if}
