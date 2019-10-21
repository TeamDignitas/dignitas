<?php

$urlName = Request::get('name');

User::enforce(User::PRIV_QUEUE);

$queueType = Queue::getTypeFromUrlName($urlName);
if ($queueType === null) {
  FlashMessage::add(_('No queue exists by that name.'));
  Util::redirectToHome();
}

// load an item from the queue at random
$qi = Model::factory('QueueItem')
  ->where('queueType', $queueType)
  //  ->order_by_expr('rand()')
  ->order_by_desc('id')
  ->find_one();

if ($qi) {

  // load the corresponding object
  $object = $qi->getObject();
  Smart::assign('object', $object);
}

Smart::assign([
  'queueType' => $queueType,
]);
Smart::display('queue/view.tpl');
