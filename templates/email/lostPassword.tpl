{**
  * Weird spacing necessary for plain text email.
  * See https://blog.rodneyrehm.de/archives/16-Smarty-Whitespace-Control.html
  **}
{t}email-hello{/t}


{t}email-you-requested-password-change{/t}


{Router::link('auth/passwordRecovery', true)}?token={$token}

{t}email-discard-password-change{/t}


{t}email-thank-you{/t}

{t}email-signature{/t}
