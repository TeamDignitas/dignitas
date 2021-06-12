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
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>

        <div class="modal-body p-0">
          {$sideSheet}
        </div>

        <div class="modal-footer small">
          {capture 'label'}{t}label-answer-resources-checkbox{/t}{/capture}
          {include 'bs/checkbox.tpl'
            checked=!User::getActive()->getMinimizeAnswerResources()
            inputId='checkboxAnswerResources'
            label=$smarty.capture.label
            name='deleteImage'}
        </div>

      </div>
    </div>
  </div>
{/if}
