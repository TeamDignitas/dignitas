<?php

/**
 * This class is necessary because comments do have a history: they can be
 * deleted. For example, moderators can view deleted comments and vote on
 * them. Voting, in turn, causes people @mentioned in the comment to be
 * notified. And Notification::notifyMentions() needs access to revisions.
 */

class RevisionComment extends Comment {
  use RevisionTrait;
}
