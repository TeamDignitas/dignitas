{extends "layout.tpl"}

{block "title"}{cap}{$statement->summary|escape}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    {include "bits/statement.tpl"
      editLink=true
      addComment=User::canComment($statement)}

    <div class="row mt-5 answers-area">
      <div class="col-sm-12 col-md-8 px-0">
        {if count($answers)}
            <h6 class="mt-4 pb-2 pl-0 subsection text-uppercase font-weight-bold">
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
      <div class="your-answer rounded row mt-5">
        <h6 class="col-md-12 answer-label text-uppercase font-weight-bold pl-0 pb-2">
          {t}title-your-answer{/t}
        </h6>

        {capture "buttonText"}{t}link-post-answer{/t}{/capture}
        {include "bits/answerEdit.tpl"
          answer=$newAnswer
          buttonText=$smarty.capture.buttonText}
      </div>

      {* #answer-resources will always be in one of these three states:

         1. Invisible (no CSS class). This is the initial state. If and when
         the answer box receives focus, then #answer-resources becomes either
         maximized or minimized, depending on the user's preference.

         2. Maximized (using the "maximized" CSS class). The div is visible,
         including the minimize button.

         3. Minimized (using the "minimized" CSS class). The div is invisible
         except for a thin border. The maximize button is visible. *}
      {if $sideSheet}
        <div id="answer-resources">

          <div id="answer-resources-maximize">
            <span
              class="icon icon-lightbulb"
              title="{t}title-answer-resources{/t}"
            ></span>
          </div>

          <div class="card">

            <div class="card-body pl-0 pr-0">
              <h6 class="card-title pl-3 pr-3 font-weight-bold">
                {t}title-answer-resources{/t}

                <button
                  id="answer-resources-minimize"
                  class="close"
                  type="button"
                  aria-label="close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </h6>

              <div class="card-text">
                {$sideSheet}
              </div>
            </div>

            <div class="card-footer small bg-transparent border-0">
              <input
                id="checkboxAnswerResources"
                type="checkbox"
                {if !User::getActive()->getMinimizeAnswerResources()}
                checked
                {/if}
              >
              <label for="checkboxAnswerResources" class="d-inline">
                {t}label-answer-resources-checkbox{/t}
              </label>
            </div>

          </div>

        </div>
      {/if}

    {/if}

    {include "bits/commentForm.tpl"}
    {include "bits/flagModal.tpl"}
    {include "bits/subscribeToast.tpl"}
  </div>
{/block}
