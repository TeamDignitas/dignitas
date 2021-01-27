<?php

/**
 * Discards errors silently.
 **/

$user = User::getActive();

if ($user) {
  $user->toggleMinimizeAnswerResources();
}
