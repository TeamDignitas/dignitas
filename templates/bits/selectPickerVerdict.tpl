{* Contents of one <option> from the verdict selectpicker in the statement filters block. *}
{* Note: DO NOT use double quotes. All of this goes inside a data-content="..." attribute. *}
<span class='badge badge-pill align-top bg-verdict-{$v} py-1 px-2'>&nbsp;</span> {Statement::verdictName($v)}
