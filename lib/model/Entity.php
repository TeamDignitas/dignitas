<?php

class Entity extends BaseObject implements DatedObject {

  const TYPE_PERSON = 1;
  const TYPE_PARTY = 2;
  const TYPE_UNION = 3; // of parties

  const TYPES = [
    self::TYPE_PERSON,
    self::TYPE_PARTY,
    self::TYPE_UNION,
  ];

  static function typeName($type) {
    switch ($type) {
      case self::TYPE_PERSON: return _('person');
      case self::TYPE_PARTY:  return _('party');
      case self::TYPE_UNION:  return _('union');
    }
  }

  function getTypeName() {
    return self::typeName($this->type);
  }

  function getRelations() {
    return Model::factory('Relation')
      ->where('fromEntityId', $this->id)
      ->order_by_asc('rank')
      ->find_many();
  }

  function copyUploadedImage($tmpImageName) {
    $dest = $this->getImageLocation();
    @mkdir(dirname($dest), 0777, true);

    // clean up old images and thumbnails
    $cmd = sprintf('rm -f %simg/entity/%d.* %simg/entity/thumb-*/%d.*',
                   Config::SHARED_DRIVE, $this->id,
                   Config::SHARED_DRIVE, $this->id);
    OS::execute($cmd, $ignored);

    copy($tmpImageName, $dest);
  }

  function getImageLocation() {
    return ($this->imageExtension && $this->id)
      ? sprintf('%simg/entity/%d.%s', Config::SHARED_DRIVE, $this->id, $this->imageExtension)
      : '';
  }

  // assumes $thumbIndex is a valid index in Config::THUMB_SIZES
  function getThumbLocation($thumbIndex) {
    $rec = Config::THUMB_SIZES[$thumbIndex];
    return ($this->imageExtension && $this->id)
      ? sprintf('%simg/entity/thumb-%dx%d/%d.%s',
                Config::SHARED_DRIVE,
                $rec[0],
                $rec[1],
                $this->id,
                $this->imageExtension)
      : '';
  }

  function getThumbLink($thumbIndex) {
    return sprintf('%s/%d/%d.%s',
                   Router::link('entity/image'),
                   $this->id,
                   $thumbIndex,
                   $this->imageExtension);
  }

  /**
   * If the thumbnail exists, returns its dimensions. If not, falls back to
   * the Config.php values. The two may differ due to differences in aspect.
   **/
  function getThumbSize($thumbIndex) {
    $file = $this->getThumbLocation($thumbIndex);

    $rec = ($file && file_exists($file))
      ? getimagesize($file)
      : Config::THUMB_SIZES[$thumbIndex];

    return [
      'width' => $rec[0],
      'height' => $rec[1],
    ];
  }

  public function __toString() {
    return $this->name;
  }

}
