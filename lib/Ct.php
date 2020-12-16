<?php

/**
 * Container for constants that apply to multiple classes and don't belong to
 * any one class.
 */

class Ct {

  // Applicable to FlaggableTrait objects (which can therefore be closed or
  // deleted). PHP won't let us define constants in traits
  const STATUS_ACTIVE = 0;
  const STATUS_CLOSED = 1;
  const STATUS_DELETED = 2;
  const STATUS_PENDING_EDIT = 3;

  // Reasons for starting a review and for closing and deleting objects.
  const REASON_SPAM = 1;
  const REASON_ABUSE = 2;
  const REASON_DUPLICATE = 3;
  const REASON_OFF_TOPIC = 4;
  const REASON_UNVERIFIABLE = 5;
  const REASON_LOW_QUALITY = 6;
  const REASON_NEW_USER = 7;
  const REASON_LATE_ANSWER = 8;
  const REASON_BY_OWNER = 9;
  const REASON_BY_USER = 10;
  const REASON_REOPEN = 11;
  const REASON_PENDING_EDIT = 12;
  const REASON_OTHER = 13;
  const REASON_NOT_NEEDED = 14;
  const NUM_REASONS = 14;

  const ONE_DAY_IN_SECONDS = 24 * 3600;

  // Applicable to RevisionTrait objects.
  const FIELD_CHANGE_STRING = 1;
  const FIELD_CHANGE_LINK = 2;
  const FIELD_CHANGE_COLOR = 3;
  const FIELD_CHANGE_STRING_LIST = 4;
  const FIELD_CHANGE_LINK_LIST = 5;
  const FIELD_CHANGE_TAG_LIST = 6;
  const FIELD_CHANGE_RELATION_LIST = 7;

  // Sort criteria
  const SORT_CREATE_DATE_DESC = 0;
  const SORT_CREATE_DATE_ASC = 1;
  const SORT_DATE_MADE_DESC = 2;
  const SORT_DATE_MADE_ASC = 3;
  const SORT_NAME_DESC = 4;
  const SORT_NAME_ASC = 5;
  const SORT_SQL = [
    self::SORT_CREATE_DATE_DESC => 'createDate desc',
    self::SORT_CREATE_DATE_ASC => 'createDate asc',
    self::SORT_DATE_MADE_DESC => 'dateMade desc',
    self::SORT_DATE_MADE_ASC => 'dateMade asc',
    self::SORT_NAME_DESC => 'name desc',
    self::SORT_NAME_ASC => 'name asc',
  ];
}
