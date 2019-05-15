{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{block "content"}
  <div class="clearfix">
    {include "bits/image.tpl"
      obj=$entity
      size=Config::THUMB_ENTITY_LARGE
      spanClass="col-3"
      imgClass="img-thumbnail rounded float-right ml-5"}

    <h3>{$statement->summary|escape}</h3>

    <p>
      — {include "bits/entityLink.tpl" e=$entity},
      {$statement->dateMade|ld}
    </p>

    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_STATEMENT
      object=$statement
      upvotePriv=User::PRIV_UPVOTE_STATEMENT
      downvotePriv=User::PRIV_DOWNVOTE_STATEMENT}

    {if count($sources)}
      <div id="sources" class="my-2">
        {t}sources{/t}:
        <ul class="list-inline">
          {foreach $sources as $s}
            <li class="list-inline-item">
              <a href="{$s->url}">{$s->getDisplayName()}</a>
            </li>
          {/foreach}
        </ul>
      </div>
    {/if}

    <h4>{t}context{/t}</h4>

    {$statement->context|md}

    <h4>{t}goal{/t}</h4>

    {$statement->goal|escape}
  </div>

  <div class="mt-3 clearfix">
    {if $statement->isEditable()}
      <a href="{Router::link('statement/edit')}/{$statement->id}" class="btn btn-light">
        <i class="icon icon-edit"></i>
        {t}edit{/t}
      </a>
    {/if}

    <small class="btn text-muted float-right">
      {t}added by{/t} <b>{$statement->getUser()|escape}</b>
      {$statement->createDate|moment}
    </small>
  </div>

  {if count($answers)}
    <h4 class="mt-3">
      {t count=count($answers) 1=count($answers) plural="%1 answers"}one answer{/t}
    </h4>

    {foreach $answers as $a}
      <div class="answer clearfix {if $a->id == $answerId}highlightedAnswer{/if}">
        {$a->contents|md}

        {include "bits/scoreAndVote.tpl"
          type=Vote::TYPE_ANSWER
          object=$a
          upvotePriv=User::PRIV_UPVOTE_ANSWER
          downvotePriv=User::PRIV_DOWNVOTE_ANSWER
          classes="float-left"}

        <small class="btn text-muted float-right">
          {t}posted by{/t} <b>{$a->getUser()|escape}</b>
          {$a->createDate|moment}
        </small>
      </div>
    {/foreach}
  {/if}

  {if User::may(User::PRIV_ADD_ANSWER)}
    <h4 class="mt-3">{t}your answer{/t}</h4>

    <form method="post">
      <input type="hidden" name="statementId" value="{$statement->id}">

      <div class="form-group">
        <textarea
          id="fieldContents"
          name="contents"
          class="form-control hasUnloadWarning {if isset($errors.contents)}is-invalid{/if}"
          rows="10"></textarea>
        {include "bits/fieldErrors.tpl" errors=$errors.contents|default:null}
        {include "bits/markdownHelp.tpl"}
      </div>

      <div>
        <button name="postAnswerButton" type="submit" class="btn btn-primary">
          <i class="icon icon-floppy"></i>
          {t}post your answer{/t}
        </button>
      </div>
    </form>

    <h4 class="mt-3">{t}preview{/t}</h4>

    <div id="markdownPreview"></div>
  {/if}

{/block}
