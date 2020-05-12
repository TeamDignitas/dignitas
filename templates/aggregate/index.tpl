{$entityImages=$entityImages|default:true}
{$addedBy=$addedBy|default:true}
{$addStatement=$addStatement|default:false}
{$addStatementEntityId=$addStatementEntityId|default:null}

{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  <div id="what-is">
    <div class="container">
      <div class="row">
        <div class="col-12">

          <h1 class="font-weight-bold">
            <span class="serif-text">DIGNITAS - </span>
            <span class="serif-text">fact checking colaborativ</span>
          </h1>
          <p class="lead mb-5 pr-4">Prima platformă de acest fel din România și din lume.
            <a class="" href="#how-it-works" role="button">Cum funcționează</a>
          </p>

        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="statements-carousel">
      <h3 class="mt-5 mb-3 capitalize-first-word font-weight-bold text-center serif-text">{t}title-recent-statements{/t}</h3>

      <div class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">

          <div class="carousel-item active">
            <div class="statement card border-secondary mr-3 mb-3 py-4 px-4">
              <div class="">
                <div class="card-title">
                  <a href="/dignitas/www/afirmatie/1" class="">„Finlanda vrea să ia din experții români pentru președinția UE”</a>
                </div>
                <div class="text-right card-text small">
                  — <a href="/dignitas/www/autor/6">Viorica Dăncilă</a>,
                  23 iunie 2019
                </div>
              </div>
            </div>
          </div>

          <div class="carousel-item">
            <div class="statement card border-secondary mr-3 mb-3 py-4 px-4">
              <div class="">
                <div class="card-title">
                  <a href="/dignitas/www/afirmatie/1" class="">„But I must explain to you how all this mistaken idea of denouncing pleasure and praising ”</a>
                </div>
                <div class="text-right card-text small">
                  — <a href="/dignitas/www/autor/6">Gabriela Firea</a>,
                  23 iunie 2019
                </div>
              </div>
            </div>
          </div>

          <div class="carousel-item">
            <div class="statement card border-secondary mr-3 mb-3 py-4 px-4">
              <div class="">
                <div class="card-title">
                  <a href="/dignitas/www/afirmatie/1" class="">„On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment”</a>
                </div>
                <div class="text-right card-text small">
                  — <a href="/dignitas/www/autor/6">Viorica Dăncilă</a>,
                  23 iunie 2019
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

    <div id="how-it-works" class="mt-5">
      <h2 class="pt-5 font-weight-bold text-center serif-text display-5">Cum funcționează Dignitas</h2>

      <section class="row">
        <div class="col-6 step-description">
          <div class="wrapper">
            <h3><span class="font-weight-bold display-3">1</span><br>
              Adaugi afirmații noi pe platformă
            </h3>
            <p>Strângem și analizăm cât mai multe afirmații politice, pe baza cărora putem calcula nivelul de încredere al unui politician.</p>
            <a href="" class="btn btn-outline-secondary btn-lg col-10">vezi cele mai recente afirmații</a>
          </div>
        </div>
        <div class="col-6 step-image text-left">
          <img src="/dignitas/www/img/step1.png" class="img-fluid" alt="Responsive image">
        </div>
      </section>

      <section class="row">
        <div class="col-6 step-image text-right pr-5">
          <img src="/dignitas/www/img/step2.png" class="img-fluid" alt="Responsive image">
        </div>
        <div class="col-6 step-description">
          <div class="wrapper">
            <h3><span class="font-weight-bold display-3">2</span><br>
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
            <h3><span class="font-weight-bold display-3">3</span><br>
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
        <div class="col-6 step-image text-right pr-5">
          <img src="/dignitas/www/img/step4.png" class="img-fluid" alt="Responsive image">
        </div>
        <div class="col-6 step-description">
          <div class="wrapper">
            <h3><span class="font-weight-bold display-3">4</span><br>
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
            <h3><span class="font-weight-bold display-3">5</span><br>
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
  </div>

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
