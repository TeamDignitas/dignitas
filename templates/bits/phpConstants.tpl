{* expose some PHP constants *}
<script defer>
  const SELECT2_LOCALE = '{LocaleUtil::getSelect2Locale()}';
  const URL_PREFIX = '{Config::URL_PREFIX}';
  const UPLOAD_MIME_TYPES = JSON.parse('{Util::getUploadMimeTypes(true)}');

  {** used in the flagging modal **}
  const TYPE_ENTITY = {Proto::TYPE_ENTITY};

  {** used in the vote confirmation alerts **}
  const TYPE_COMMENT = {Proto::TYPE_COMMENT};

  {** localized search URL **}
  const SEARCH_URL = '{Router::link('aggregate/search')}';

</script>
