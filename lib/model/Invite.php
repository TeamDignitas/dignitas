<?php

class Invite extends Proto {

  function getSender() {
    return User::get_by_id($this->senderId);
  }

  function getReceiver() {
    return User::get_by_id($this->receiverId);
  }

  /**
   * Mark this invite as having been accepted by this user. This will be
   * called when a user registers with an invitation code.
   */
  function acceptedBy($user) {
    $this->receiverId = $user->id;
    $this->save();
  }

  /**
   * Find any outstanding invite for this user and mark it as accepted. This
   * will be called upon any user registration, in case the user received an
   * invitation, but then decided to register without clicking on the invite.
   *
   * @param User $user User who just signed up.
   */
  static function acceptByEmail($user) {
    $invite = Invite::get_by_email_receiverId($user->email, 0);
    if ($invite) {
      $invite->receiverId = $user->id;
      $invite->save();
    }
  }

  static function loadAll() {
    return Model::factory('Invite')
      ->order_by_asc('email')
      ->find_many();
  }

}
