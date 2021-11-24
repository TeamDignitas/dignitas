<table class="table table-hover">
  <thead>
    <tr class="d-flex small">
      <th class="col-sm-3 col-md-2 py-1">{t}label-date{/t}</th>
      <th class="col-sm-9 col-md-10 py-1">{t}label-action{/t}</th>
    </tr>
  </thead>
  <tbody>
    {foreach $actions as $a}
      <tr class="d-flex small ms-0">
        <td class="col-sm-3 col-md-2 text-muted">
          {include "bits/moment.tpl" t=$a->createDate}
        </td>
        <td class="col-sm-9 col-md-10">
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
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>
