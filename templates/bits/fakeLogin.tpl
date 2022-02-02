<div class="card">
  <div class="card-header">
    {cap}{t}title-test-login{/t}{/cap}
  </div>

  <div class="card-body">
    <form method="post">
      <input type="hidden" name="referrer" value="{$referrer|esc}">

      {include "bs/iconField.tpl"
        icon='email'
        name='fakeEmail'
        value='test@test.com'}

      <div class="input-group mb-3">
        {include "bits/icon.tpl" i=emoji_events class="input-group-text"}

        <input
          class="form-control"
          type="text"
          name="fakeReputation"
          placeholder="{t}label-reputation{/t}"
          list="preset">
        <datalist id="preset">
          <option>10000</option>
          <option>2000</option>
          <option>125</option>
          <option>100</option>
          <option>15</option>
          <option>10</option>
          <option>1</option>
        </datalist>
      </div>

      {include 'bs/checkbox.tpl'
        divClass='mb-3'
        label="{t}label-moderator{/t}"
        name='fakeModerator'}

      <button class="btn btn-outline-secondary" type="submit">
        {include "bits/icon.tpl" i=warning}
        {t}link-test-login{/t}
      </button>

    </form>
  </div>
</div>
