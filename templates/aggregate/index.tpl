{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  <div id="what-is" class="row">
    <div class="col-7">
      <h1 class="mb-5">
        <span class="display-4 font-weight-bold">DIGNITAS - </span><br>
        <span class="display-5">prima platformă de</span><br>
        <span class="display-5 font-weight-bold">fact checking colaborativ</span><br>
        <span class="display-5">din lume</span>
      </h1>
      <p class="lead mb-5">Oricine poate contribui acum la demontarea știrilor false (<b>#fakeNews</b>) din România.</p>
      <a class="btn btn-primary btn-lg mr-3" href="#how-it-works" role="button">Cum funcționează</a>
      <a class="btn btn-outline-primary btn-lg" href="#" role="button">Contribuie și tu</a>
    </div>
    <div class="col-5 px-0 background-quotes"></div>
  </div>

  <div id="how-it-works" class="mt-5">
    <h2 class="mt-3 font-weight-bold">Cum funcționează Dignitas</h2>

    <section class="row py-4">
      <div class="col-6 step-description">
        <div class="wrapper mt-5">
          <h3 class="">1. Adaugi afirmații noi pe platformă</h3>
          <p>Ai nevoie doar de un cont pentru asta. Scopul nostru este să strângem și să analizăm cât mai multe afirmații politice, în special de la politicienii aflați actualmente în funcții publice cu rol de conducere. Odată strânse și analizate, aceste afirmații ne vor arăta nivelul de încredere pe care îl putem avea într-un politician la următoarele alegeri.</p>
          <a href="" class="btn btn-outline-info btn-lg">vezi cele mai recente afirmații</a>
        </div>
      </div>
      <div class="col-6 step-image text-left">
        <img src="" class="img-fluid" alt="Responsive image">
      </div>
    </section>

    <section class="row py-4">
      <div class="col-6 step-image text-right">
        <img src="" class="img-fluid" alt="Responsive image">
      </div>
      <div class="col-6 step-description">
        <div class="wrapper mt-5">
          <h3 class="">2. Votezi răspunsurile altor contribuitori</h3>
          <p>Pentru început, drepturile tale ca contribuitor sunt limitate până acumulezi mai multă reputație. Colaborarea cu alți utilizatori ai platformei este esențială pentru creșterea puterii tale de analiză pe această platformă.</p>
          <a href="" class="btn btn-outline-info btn-lg">vezi cele mai recente răspunsuri</a>
        </div>
      </div>
    </section>

    <section class="row py-4">
      <div class="col-6 step-description">
        <div class="wrapper mt-5">
          <h3 class="">3. Acumulezi reputație (puncte)</h3>
          <p>Pe măsură ce voturile tale ajută la definirea valorilor de adevăr ale afirmațiilor în curs de analizare, vei acumula reputație sub formă de puncte și vei dobândi drepturi noi pe platformă.</p>
          <a href="" class="btn btn-outline-info btn-lg">cum acumulezi reputație</a>
        </div>
      </div>
      <div class="col-6 step-image text-left">
        <img src="" class="img-fluid" alt="Responsive image">
      </div>
    </section>

    <section class="row py-4">
      <div class="col-6 step-image text-right">
        <img src="" class="img-fluid" alt="Responsive image">
      </div>
      <div class="col-6 step-description">
        <div class="wrapper mt-5">
          <h3 class="">4. Primești drepturi noi pe platformă</h3>
          <p>Mai multe drepturi, mai multă responsabilitate pentru contribuitori. Pe măsură ce reputația ta crește, vei putea raporta afirmațiile duplicate, vei puea comenta un răspuns, vei putea adăuga etichete noi pentru definirea campaniilor etc.</p>
          <a href="" class="btn btn-outline-info btn-lg">vezi lista de privilegii</a>
        </div>
      </div>
    </section>

    <section class="row py-4">
      <div class="col-6 step-description">
        <div class="wrapper mt-5">
          <h3 class="">5. Votezi valoarea de adevăr a unei afirmații politice</h3>
          <p>Unele afirmații politice, prin natura lor, nu se încadrează în valorile standard de adevărat/fals. De aceea Dignitas folosește o scară de adevăr cu 5 trepte.</p>
          <a href="" class="btn btn-outline-info btn-lg">vezi scara de adevăr</a>
        </div>
      </div>
      <div class="col-6 step-image text-left">
        <img src="" class="img-fluid" alt="Responsive image">
      </div>
    </section>

  </div>

  <h3>{t}title-recent-statements{/t}</h3>

  {include "bits/statementList.tpl"}

  <h3>{t}title-entities{/t}</h3>

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
