<div class="comment">
  {$comment->contents|md}
  &mdash;
  {include 'bits/userLink.tpl' u=$comment->getUser()}
  {include 'bits/moment.tpl' t=$comment->createDate}
</div>
