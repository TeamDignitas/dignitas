<?php

class User extends BaseObject implements DatedObject {
  use MarkdownTrait, UploadTrait;

  // privileges and their required reputation
  const PRIV_ADD_ENTITY = 1;
  const PRIV_EDIT_ENTITY = 10;
  const PRIV_DELETE_ENTITY = 2000;

  const PRIV_ADD_STATEMENT = 1;
  const PRIV_EDIT_STATEMENT = 2000;
  const PRIV_DELETE_STATEMENT = 10000;

  const PRIV_ADD_ANSWER = 1;
  const PRIV_EDIT_ANSWER = 2000;
  const PRIV_DELETE_ANSWER = 10000;

  const PRIV_UPVOTE_STATEMENT = 15;
  const PRIV_DOWNVOTE_STATEMENT = 125;
  const PRIV_UPVOTE_ANSWER = 15;
  const PRIV_DOWNVOTE_ANSWER = 125;

  const PRIV_FLAG = 15;

  const PRIV_ADD_TAG = 1500;
  const PRIV_EDIT_TAG = 1500;
  const PRIV_DELETE_TAG = 5000;

  const PRIV_UPLOAD_ATTACHMENT = 100;

  const PRIV_REVIEW = 2000;
  const PRIV_CLOSE_REOPEN_VOTE = 3000;

  // flag earning
  const BASE_FLAGS_PER_DAY = 10;
  const REPUTATION_FOR_NEW_FLAG = 2000;
  const MAX_FLAGS_PER_DAY = 100;

  private static $active = null; // user currently logged in

  function getMarkdownFields() {
    return [ 'aboutMe' ];
  }

  function getObjectType() {
    return self::TYPE_USER;
  }

  private function getFileSubdirectory() {
    return 'user';
  }

  private function getFileRoute() {
    return 'user/image';
  }

  static function getActive() {
    return self::$active;
  }

  static function getActiveId() {
    return self::$active ? self::$active->id : 0;
  }

  static function setActive($userId) {
    // update lastSeen before loading the user
    $query = sprintf('update user set lastSeen = %d where id = %d', time(), $userId);
    DB::execute($query);

    self::$active = User::get_by_id($userId);
  }

  static function getFlagsPerDay() {
    if (!self::$active) {
      return 0;
    }

    $earned = (int)(self::$active->reputation / self::REPUTATION_FOR_NEW_FLAG);
    return min(self::BASE_FLAGS_PER_DAY + $earned,
               self::MAX_FLAGS_PER_DAY);
  }

  static function getRemainingFlags() {
    $pending = Model::factory('Flag')
      ->table_alias('f')
      ->join('review', ['f.reviewId', '=', 'r.id'], 'r')
      ->where('f.userId', self::getActiveId())
      ->where('r.status', Review::STATUS_PENDING)
      ->where_gte('f.createDate', Time::ONE_DAY_IN_SECONDS)
      ->count();

    return self::getFlagsPerDay() - $pending;
  }

  // Checks if the user can claim this email when registering or editing their profile.
  // Returns null on success or an error message on failure. Assumes $email is not empty.
  static function canChooseEmail($email) {
    if (!$email) {
      return _('Please enter an email address.');
    }

    $u = User::get_by_email($email);
    if ($u && $u->id != self::getActiveId()) {
      return _('This email address is already taken.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return _('This email address looks incorrect.');
    }

    return null;
  }

  // returns null on success or an error message on failure
  static function validateNickname($nickname) {
    if (!preg_match('/^(\p{L}|\d)(\p{L}|\d|[-_.]){2,29}$/u', $nickname)) {
      return _(
        'Your nickname must begin with a letter or digit and consist of ' .
        '3 to 30 letters, digits and punctuation from among -_.');
    }
    return null;
  }

  // returns null on success or an error message on failure
  static function validateNewPassword($password, $password2) {
    if (!$password) {
      return _('Please enter a password.');
    } else if (!$password2) {
      return _('Please enter your password twice to prevent typos.');
    } else if ($password != $password2) {
      return _("Passwords don't match.");
    } else if (strlen($password) < 8) {
      return _('Your password must be at least 8 characters long.');
    } else {
      return null;
    }
  }

  // checks whether the active user has the privilege
  static function may($privilege) {
    return self::$active && (self::$active->reputation >= $privilege);
  }

  // checks whether the active user has the privilege and bounces them if not
  static function enforce($privilege) {
    // redirect to log in page if there is no active user
    if (!self::$active) {
      Util::redirectToLogin();
    } else if (self::$active->reputation < $privilege) {
      FlashMessage::add(sprintf(
        _('You need at least %s reputation to perform this action.'),
        Str::formatNumber($privilege)));
      Util::redirectToHome();
    }
  }

  /**
   * Checks if the active user may flag the given object.
   *
   * @param Flaggable $obj A flaggable object
   * @param boolean $throw Whether to also throw an exception with a detailed message
   * @return boolean Returns true iff the user should be allowed to flag
   * @throws Exception If the user should not be allowed to flag and $throw = true
   */
  static function canFlag($obj, $throw = false) {
    try {
      // check the user's reputation
      if (!self::may(self::PRIV_FLAG)) {
        throw new Exception(
          sprintf(_('You need at least %s reputation to flag.'),
                  Str::formatNumber(User::PRIV_FLAG)));
      }

      // check the user's remaining flags
      if (self::getRemainingFlags() <= 0) {
        $fpd = User::getFlagsPerDay();
        throw new Exception(
          sprintf(ngettext('You can use at most one flag every 24 hours.',
                           'You can use at most %d flags every 24 hours.',
                           $fpd), $fpd));
      }

      // check that the object exists
      if (!$obj) {
        throw new Exception(_('Cannot flag: object does not exist.'));
      }

      // check that the user does not already have a pending flag
      if ($obj->isFlagged()) {
        throw new Exception(_('You already have a pending flag for this object.'));
      }

      return true;
    } catch (Exception $e) {

      if ($throw) {
        throw $e;
      }

      return false;
    }
  }

  public function __toString() {
    return $this->nickname;
  }

}
