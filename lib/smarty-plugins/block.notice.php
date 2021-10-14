<?php

/**
 * We include the template here instead of under templates/ so that
 * we won't be tempted to call it explicitly.
 */
const NOTICE_TEMPLATE = 'string:
  {$icon=$icon|default:null} {* optionally, a Material Icons name *}
  <div class="notice d-flex align-items-center small">
    {if $icon}
      <div class="notice-icon me-3">
        {include "bits/icon.tpl" i=$icon}
      </div>
    {/if}
    <div>
      {$contents}
    </div>
  </div>
';

/**
 * A replacement for Bootstrap alerts. We only use a single color and we
 * support vertically centered icons.
 **/
function smarty_block_notice($params, $content, $template, &$repeat) {
  if (!$repeat) {
    $template->smarty->assign([
      'contents' => $content,
      'icon' => $params['icon'] ?? null,
    ]);
    return $template->smarty->fetch(NOTICE_TEMPLATE);
  }
}
