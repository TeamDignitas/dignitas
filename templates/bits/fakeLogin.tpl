<div class="card">
  <div class="card-header">
    {cap}{t}title-test-login{/t}{/cap}
  </div>

  <div class="card-body">
    <form method="post">
      <input type="hidden" name="referrer" value="{$referrer|escape}">

      <div class="form-group">
        <div class="input-group">
          <span class="input-group-prepend">
            {include "bits/icon.tpl" i=email class="input-group-text"}
          </span>
          <input
            class="form-control"
            type="text"
            name="fakeEmail"
            value="test@test.com">
        </div>
      </div>

      <div class="form-group">
        <div class="input-group">
          <span class="input-group-prepend">
            {include "bits/icon.tpl" i=emoji_events class="input-group-text"}
          </span>

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
      </div>

      {include 'bs/checkbox.tpl'
        divClass='mb-3'
        label="{t}label-moderator{/t}"
        name='fakeModerator'}

      <input
        class="btn btn-warning"
        type=submit
        value="{t}link-test-login{/t}">

    </form>
  </div>
</div>
