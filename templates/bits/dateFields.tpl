{$nameSuffix=$nameSuffix|default:''} {* e.g. '[]' for field arrays *}
{$date=$date|default:'0000-00-00'}
{assign var="dateParts" value="-"|explode:$date}

<div class="input-group date-field-container">
  {strip}

  {$year = ($dateParts[0] == '0000') ? '' : $dateParts[0]}
  <input
    type="number"
    class="form-control"
    size="4"
    name="{$namePrefix}Y{$nameSuffix}"
    value="{$year}">

  <select
    class="form-control"
    name="{$namePrefix}M{$nameSuffix}">
    <option value=""></option>
    {for $m = 1 to 12}
      {$fm=$m|string_format:'%02d'}
      <option
        value="{$fm}"
        {if $dateParts[1]|default:'' == $fm}selected{/if}>
        {$m|monthName:true}
      </option>
    {/for}
  </select>

  <select
    class="form-control"
    name="{$namePrefix}D{$nameSuffix}">
    <option value=""></option>
    {for $d = 1 to 31}
      {$fd=$d|string_format:'%02d'}
      <option
        value="{$fd}"
        {if $dateParts[2]|default:'' == $fd}selected{/if}>
        {$d}
      </option>
    {/for}
  </select>

  {/strip}
</div>
