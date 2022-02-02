<div class="table-responsive">
  <table class="table">

    <thead>
      <tr>
        <th>{t}label-date{/t}</th>
        <th>{t}label-action{/t}</th>
      </tr>
    </thead>

    <tbody>
      {foreach $actions as $a}
        <tr>
          <td class="text-muted text-nowrap">
            {include "bits/moment.tpl" t=$a->createDate}
          </td>
          <td>
            {$obj=$a->getObject()}
            {if !$obj}   {* Underlying object has been deleted *}
              {$a->getTypeName()}
              {$a->description|esc}
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
</div>
