{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{* publish the ClaimReview structured object if the statement has one *}
{block "claimReview"}
  {$crJson=$statement->getClaimReviewJson()}
  {if $crJson}
    <script type="application/ld+json">
      {$crJson}
    </script>
  {/if}
{/block}

{block "content"}
  <div class="container my-5">
    {include "bits/statement.tpl"
      editLink=true
      addComment=User::canComment($statement)}

    <div class="row mt-5 answers-area">
      <div class="col-12 col-md-8 px-0">
        {if count($answers)}
          <h6 class="mt-4 pb-2 ps-0 subsection text-uppercase fw-bold">
            {t count=count($answers) 1=count($answers) plural="title-answers-plural"}
            title-answers-singular
            {/t}
          </h6>

          {foreach $answers as $answer}
            {include "bits/answer.tpl"
              addComment=User::canComment($answer)}
          {/foreach}
        {/if}
      </div>
    </div>

    {if $statement->isAnswerable()}
      <div class="your-answer rounded mt-5">
        <h6 class="answer-label text-uppercase fw-bold ps-0 pb-2">
          {t}title-your-answer{/t}
        </h6>

        {include "bits/answerEdit.tpl"
          answer=$newAnswer
          buttonText="{t}link-post-answer{/t}"}
      </div>

    {/if}

    {include "bits/commentForm.tpl"}
    {include "bits/flagModal.tpl"}
  </div>
{/block}
