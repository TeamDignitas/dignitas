<div class="card">
  <div class="card-header">
    {cap}{t}test user login{/t}{/cap}
  </div>

  <div class="card-body">
    <form method="post">
      <input type="hidden" name="referrer" value="{$referrer|escape}">

      <div class="form-group">
        <div class="input-group">
          <span class="input-group-prepend">
            <i class="input-group-text icon icon-mail"></i>
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
            <i class="input-group-text icon icon-award"></i>
          </span>

          <input
            class="form-control"
            type="text"
            name="fakeReputation"
            placeholder="{t}reputation{/t}"
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
      <input
        class="btn btn-warning"
        type=submit
        value="{t}test user login{/t}">

    </form>
  </div>
</div>
