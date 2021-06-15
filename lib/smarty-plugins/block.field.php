<?php

/**
 * A regular Bootstrap form field (label on top, field on the bottom).
 **/

const FORM_FIELD_TEMPLATE = '
  <div class="mb-3">

    <label
      for="%s"
      class="form-label">
      %s
    </label>

    %s

  </div>
';

function smarty_block_field($params, $content, $template, &$repeat) {
  if (!$repeat) {
    return sprintf(
      FORM_FIELD_TEMPLATE,
      $params['inputId'] ?? '',
      $params['label'] ?? '',
      $content,
    );
  }
}
