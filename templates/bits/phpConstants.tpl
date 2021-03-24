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

  // used for archived versions of URLs
  const ARCHIVED_VERSION_MESSAGE = '{t}archived-version-%1{/t}';

</script>
