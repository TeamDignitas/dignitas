<div class="gtable gx-2">

  <div class="row gtable-header">
    <div class="col-12 col-lg-3">{t}label-date{/t}</div>
    <div class="col-12 col-lg-9">{t}label-action{/t}</div>
  </div>

  {foreach $actions as $a}
    <div class="row gtable-row">
      <div class="col-12 col-lg-3 text-muted">
        {include "bits/moment.tpl" t=$a->createDate}
      </div>
      <div class="col-12 col-lg-9">
        {$obj=$a->getObject()}
        {if !$obj}   {* Underlying object has been deleted *}
          {$a->getTypeName()}
          {$a->description|escape}
        {elseif $a->objectType == Proto::TYPE_ANSWER}
          {$a->getTypeName()}
          <a href="{$obj->getViewUrl()}">
            {t}action-target-answer{/t}
          </a>
          <div class="text-muted">
            {$obj->contents|shorten:120}
          </div>
        {elseif $a->objectType == Proto::TYPE_COMMENT}
          {$a->getTypeName()}
          <a href="{$obj->getViewUrl()}">
            {t}action-target-comment{/t}
          </a>
          <div class="text-muted">
            {$obj->contents|shorten:120}
          </div>
        {elseif $a->objectType == Proto::TYPE_ENTITY}
          {$a->getTypeName()}
          {include "bits/entityLink.tpl" e=$obj}
        {elseif $a->objectType == Proto::TYPE_STATEMENT}
          {$a->getTypeName()}
          {include "bits/statementLink.tpl" statement=$obj quotes=false}
        {elseif $a->objectType == Proto::TYPE_TAG}
          {$a->getTypeName()}
          {include "bits/tag.tpl" t=$obj link=true}
        {elseif $a->objectType == Proto::TYPE_USER}
          {t}action-updated-user-profile{/t}
        {/if}
      </div>
    </div>
  {/foreach}
</div>
