<?php

/**
 * A Bootstrap horizontal form field. Defaults to col-lg-2 for the label and
 *  col-lg-10 for the field.
 **/

const HF_TEMPLATE = '
  <div class="row mb-3">

    <label
      for="%s"
      class="col-form-label col-12 col-%s-%s ps-0">
      %s
    </label>

    <div class="col-12 col-%s-%s px-0">
      %s
    </div>

  </div>
';

function smarty_block_hf($params, $content, $template, &$repeat) {
  if (!$repeat) {
    return sprintf(
      HF_TEMPLATE,
      $params['inputId'] ?? '',
      $params['breakpoint'] ?? 'lg',
      $params['col'] ?? 2,
      $params['label'] ?? '',
      $params['breakpoint'] ?? 'lg',
      12 - ($params['col'] ?? 2),
      $content,
    );
  }
}
