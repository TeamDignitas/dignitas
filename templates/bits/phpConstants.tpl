{* expose some PHP constants *}
<script>
  const SELECT2_LOCALE = '{LocaleUtil::getSelect2Locale()}';
  const URL_PREFIX = '{Config::URL_PREFIX}';
  const UPLOAD_MIME_TYPES = JSON.parse('{Util::getUploadMimeTypes()|json_encode}');

  // used in the flagging modal
  const TYPE_ENTITY = {BaseObject::TYPE_ENTITY};
</script>