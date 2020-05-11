{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  <div id="what-is" class="row">

    <div class="col-7">
      <div class="row">
        <h1 class="mb-4">
          <span class="display-4 font-weight-bold name">DIGNITAS - </span><br>
          <span class="display-5">prima platformă de</span><br>
          <span class="display-5 font-weight-bold">fact checking colaborativ</span><br>
          <span class="display-5">din lume</span>
        </h1>
        <p class="lead mb-5 pr-4">Oricine poate contribui acum la demontarea știrilor false (<b>#fakeNews</b>) din România.</p>
      </div>

      <div class="row homepage-actions">
        <a class="btn btn-primary btn-lg col-md-5 col-sm-12 mr-3" href="#how-it-works" role="button">Cum funcționează</a>
        <a class="btn btn-outline-primary btn-lg col-md-5 col-sm-12" href="{Router::link('auth/register')}" role="button">Contribuie și tu</a>
      </div>
    </div>

    <div class="col-5 px-0">
      <img src="/dignitas/www/img/quotes_background.png" class="img-fluid" alt="Responsive quotes image">
    </div>
  </div>

  <div id="how-it-works" class="mt-5">
    <h2 class="pt-5 font-weight-bold display-4">Cum funcționează Dignitas</h2>

    <section class="row">
      <div class="col-6 step-description">
        <div class="wrapper">
          <h3><span class="font-weight-bold display-2">1</span><br>
            Adaugi afirmații noi pe platformă
          </h3>
          <p>Strângem și analizăm cât mai multe afirmații politice, pe baza cărora putem calcula nivelul de încredere al unui politician.</p>
          <a href="#recent-statements" class="btn btn-outline-secondary btn-lg col-10">vezi cele mai recente afirmații</a>
        </div>
      </div>
      <div class="col-6 step-image text-left">
        <img src="/dignitas/www/img/step1.png" class="img-fluid" alt="Responsive image">
      </div>
    </section>

    <section class="row">
      <div class="col-6 step-image text-right">
        <img src="/dignitas/www/img/step2.png" class="img-fluid" alt="Responsive image">
      </div>
      <div class="col-6 step-description">
        <div class="wrapper">
          <h3><span class="font-weight-bold display-2">2</span><br>
            Votezi răspunsurile altor contribuitori
          </h3>
          <p>Colaborarea cu alți utilizatori ai platformei este esențială pentru creșterea reputației și drepturilor tale pe platformă.</p>
          <a href="" class="btn btn-outline-secondary btn-lg col-10">vezi cele mai recente răspunsuri</a>
        </div>
      </div>
    </section>

    <section class="row">
      <div class="col-6 step-description">
        <div class="wrapper">
          <h3><span class="font-weight-bold display-2">3</span><br>
            Acumulezi reputație (puncte)
          </h3>
          <p>Pe măsură ce voturile tale ajută la definirea valorilor de adevăr ale afirmațiilor în curs de analizare, reputație ta va crește.</p>
          <a href="https://dignitas.ro/ajutor/reputatie" class="btn btn-outline-secondary btn-lg col-10">cum acumulezi reputație</a>
        </div>
      </div>
      <div class="col-6 step-image text-left">
        <img src="/dignitas/www/img/step3.png" class="img-fluid" alt="Responsive image">
      </div>
    </section>

    <section class="row">
      <div class="col-6 step-image text-right">
        <img src="/dignitas/www/img/step4.png" class="img-fluid" alt="Responsive image">
      </div>
      <div class="col-6 step-description">
        <div class="wrapper">
          <h3><span class="font-weight-bold display-2">4</span><br>
            Primești drepturi noi pe platformă
          </h3>
          <p>Mai multe drepturi, mai multă responsabilitate pentru contribuitori.</p>
          <a href="https://dignitas.ro/ajutor/privilegii" class="btn btn-outline-secondary btn-lg col-10">vezi lista de privilegii</a>
        </div>
      </div>
    </section>

    <section class="row">
      <div class="col-6 step-description">
        <div class="wrapper">
          <h3><span class="font-weight-bold display-2">5</span><br>
            Alegi valoarea de adevăr a afirmațiilor
          </h3>
          <p>Dignitas folosește o scară de adevăr cu 6 trepte, similar altor platforme de fact checking din lume.</p>
          <a href="https://dignitas.ro/ajutor/verdicte" class="btn btn-outline-secondary btn-lg col-10">vezi scara de adevăr</a>
        </div>
      </div>
      <div class="col-6 step-image text-left">
        <img src="/dignitas/www/img/step5.png" class="img-fluid" alt="Responsive image">
      </div>
    </section>

  </div>

  <h3 id="recent-statements" class="mt-5 mb-3 capitalize-first-word font-weight-bold display-4">{t}title-recent-statements{/t}</h3>

  {include "bits/statementList.tpl"}

  <h3 class="mt-5 mb-3 capitalize-first-word font-weight-bold display-4">{t}title-entities{/t}</h3>

  {foreach $entities as $e}
    <div class="clearfix">
      {include "bits/image.tpl"
        obj=$e
        geometry=Config::THUMB_ENTITY_SMALL
        imgClass="pic float-right"}

      {include "bits/entityLink.tpl" e=$e showStatus=true}
      <div>{$e->getEntityType()->name|escape}</div>
    </div>
    <hr>
  {/foreach}

  <div>
    {if User::may(User::PRIV_ADD_STATEMENT)}
      <a href="{Router::link('statement/edit')}" class="btn btn-link">
        {t}link-add-statement{/t}
      </a>
    {/if}

    {if User::may(User::PRIV_ADD_ENTITY)}
      <a href="{Router::link('entity/edit')}" class="btn btn-link">
        {t}link-add-entity{/t}
      </a>
    {/if}
  </div>

{/block}
