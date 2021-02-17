#!/usr/bin/php
<?php

/**
 * This script creates a subset of the Material Icons font. To use it, adapt
 * CODEPOINTS below and run the script.
 **/

require_once __DIR__ . '/../lib/Core.php';

// list of glyphs we want in the subset, specified by their codepoint
const CODEPOINTS = [
  'add_circle',
  'cancel',
  'chevron_right',
  'compare_arrows',
  'content_copy',
  'delete_forever',
  'done',
  'drag_indicator',
  'email',
  'emoji_events',
  'expand_more',
  'filter_list',
  'flag',
  'folder',
  'format_bold',
  'format_italic',
  'format_list_bulleted',
  'format_list_numbered',
  'format_quote',
  'format_size',
  'gavel',
  'groups',
  'hourglass_full',
  'insert_comment',
  'insert_invitation',
  'insert_link',
  'insert_photo',
  'integration_instructions',
  'inventory',
  'language',
  'local_offer',
  'lock',
  'logout',
  'map',
  'menu',
  'mode_edit',
  'more_vert',
  'notifications',
  'open_in_full',
  'person',
  'person_add_alt_1',
  'save',
  'search',
  'send',
  'text_snippet',
  'thumb_down',
  'thumb_up',
  'view_column',
  'visibility',
  'visibility_off',
];

const ASCII_GLYPHS = [ '5f-7a', '40-49' ]; // always include [_a-z0-9]

// Use the stable font, but the master codepoints (there are no codepoints in
// the release).
const CODEPOINT_URL = 'https://raw.githubusercontent.com/google/material-design-icons/master/font/MaterialIcons-Regular.codepoints';
const FONT_URL = 'https://github.com/google/material-design-icons/raw/4.0.0/font/MaterialIcons-Regular.ttf';

const TMP_FONT_FILE = '/tmp/material-icons.ttf';
const OUTPUT_FILE = __DIR__ . '/../www/fonts/material-icons.woff2';

$glyphs = getGlyphs();

// download the font file
file_put_contents(TMP_FONT_FILE, file_get_contents(FONT_URL));

$cmd = sprintf(
  'fonttools subset %s --unicodes=%s --no-layout-closure --output-file=%s --flavor=woff2',
  TMP_FONT_FILE,
  implode(',', $glyphs),
  OUTPUT_FILE
);

print("Running: {$cmd}\n");
OS::executeAndAssert($cmd);

unlink(TMP_FONT_FILE);

/*************************************************************************/

// parses the codepoints file and returns a set of Unicode glyph codes
function getGlyphs() {
  // convert the 'codepoint glyph' format to a codepoint => glyph array
  $codepointLines = file(CODEPOINT_URL);

  $codepoints = [];
  foreach ($codepointLines as $line) {
    $parts = explode(' ', $line, 2);
    $codepoints[$parts[0]] = trim($parts[1]);
  }

  $result = ASCII_GLYPHS;

  foreach (CODEPOINTS as $code) {
    isset($codepoints[$code]) || die("ERROR: Ligature {$code} is not defined in the font.\n");
    $result[] = $codepoints[$code];
  }

  return $result;
}
