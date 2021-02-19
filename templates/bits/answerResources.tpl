{if $sideSheet}
  <div
    id="answer-resources"
    class="modal fade"
    data-focus="false"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <h6 class="modal-title">
            {t}title-answer-resources{/t}
          </h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body p-0">
          {$sideSheet}
        </div>

        <div class="modal-footer small">
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
  </div>
{/if}
