{* expose some PHP constants *}
<script defer>
  const SELECT2_LOCALE = '{LocaleUtil::getSelect2Locale()}';
  const DATEPICKER_LOCALE = '{LocaleUtil::getDatePickerLocale()}';
  const DATEPICKER_FORMAT = '{LocaleUtil::getDatePickerFormat()}';
  const URL_PREFIX = '{Config::URL_PREFIX}';
  const UPLOAD_MIME_TYPES = JSON.parse('{Util::getUploadMimeTypes()|json_encode}');

  {** used in the flagging modal **}
  const TYPE_ENTITY = {Proto::TYPE_ENTITY};

  {** used in the vote confirmation alerts **}
  const TYPE_COMMENT = {Proto::TYPE_COMMENT};

  {** localized search URL **}
  const SEARCH_URL = '{Router::link('aggregate/search')}';

  {******************** translations ********************}

  const EASYMDE_BOLD = '{t}easymde-bold{/t}';
  const EASYMDE_ITALIC = '{t}easymde-italic{/t}';
  const EASYMDE_HEADING = '{t}easymde-heading{/t}';
  const EASYMDE_QUOTE = '{t}easymde-quote{/t}';
  const EASYMDE_GENERIC_LIST = '{t}easymde-generic-list{/t}';
  const EASYMDE_NUMBERED_LIST = '{t}easymde-numbered-list{/t}';
  const EASYMDE_LINK = '{t}easymde-link{/t}';
  const EASYMDE_IMAGE = '{t}easymde-image{/t}';
  const EASYMDE_PREVIEW = '{t}easymde-preview{/t}';
  const EASYMDE_SIDE_BY_SIDE = '{t}easymde-side-by-side{/t}';
  const EASYMDE_FULLSCREEN = '{t}easymde-fullscreen{/t}';
  const EASYMDE_RESOURCES = '{t}easymde-resources{/t}';

  // used in confirmation and alert modals
  const ALERT_OK_TEXT = '{t}link-ok{/t}';
  const CONFIRM_CANCEL_TEXT = '{t}link-cancel{/t}';
  const CONFIRM_OK_TEXT = '{t}link-confirm{/t}';

  // used for archived versions of URLs
  const ARCHIVED_VERSION_MESSAGE = '{t}archived-version-%1{/t}';

</script>
