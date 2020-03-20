{**
  * Weird spacing necessary for plain text email.
  * See https://blog.rodneyrehm.de/archives/16-Smarty-Whitespace-Control.html
  **}
{t}email-hello{/t}


{t
  1=$sender->nickname
  2=$sender->email}
email-user-invited-you-to-dignitas-%1-%2
{/t}


{Router::link('auth/register', true)}?code={$code}

{t}email-discard-invite{/t}


{t}email-thank-you{/t}

{t}email-signature{/t}
