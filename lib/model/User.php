<?php

class User extends BaseObject implements DatedObject {

  // privileges
  const PRIV_ADD_ENTITY = 0x01;
  const PRIV_EDIT_ENTITY = 0x02;
  const PRIV_DELETE_ENTITY = 0x04;
  const PRIV_ADD_STATEMENT = 0x08;
  const PRIV_EDIT_STATEMENT = 0x10;
  const PRIV_DELETE_STATEMENT = 0x20;

  private static $active = null; // user currently logged in

  static function getActive() {
    return self::$active;
  }

  static function getActiveId() {
    return self::$active ? self::$active->id : 0;
  }

  static function setActive($userId) {
    self::$active = User::get_by_id($userId);
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

  // checks whether the active user has any privilege in the mask
  static function may($privilegeMask) {
    return self::$active !== null;
  }

  // checks whether the active user has any privilege in the mask and bounces
  // them if not
  static function enforce($privilegeMask) {
    if (!self::may($privilegeMask)) {
      FlashMessage::add(_('You are not allowed to perform this action.'));
      Util::redirectToHome();
    }
  }

  public function __toString() {
    return $this->email;
  }

}
