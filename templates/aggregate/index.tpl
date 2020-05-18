{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  <div id="what-is" class="pt-3">
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
    <div class="statements-carousel pb-4">
      <h3 class="mt-4 mb-4 capitalize-first-word serif-text">{t}title-recent-statements{/t}</h3>

      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner pb-5">

          {foreach $statements as $i => $page}
            <div class="carousel-item mb-3 {if !$i}active{/if}">
              <div class="container px-0">

                <div class="row mb-5">
                  <div class="col-6">
                    {include "bits/carouselStatement.tpl" statement=$page[0]}
                  </div>

                  <div class="col-6">
                    {if count($page) >= 2}
                      {include "bits/carouselStatement.tpl" statement=$page[1]}
                    {/if}
                  </div>
                </div>

                {if count($page) >= 3}
                  <div class="row mb-5">
                    <div class="col-6">
                      {include "bits/carouselStatement.tpl" statement=$page[2]}
                    </div>

                    <div class="col-6">
                      {if count($page) == 4}
                        {include "bits/carouselStatement.tpl" statement=$page[3]}
                      {/if}
                    </div>
                  </div>
                {/if}
              </div>
            </div>
          {/foreach}

          <ol class="carousel-indicators">
            {foreach $statements as $i => $ignored}
              <li
                data-target="#carouselExampleIndicators"
                data-slide-to="{$i}"
                {if !$i}class="active"{/if}>
              </li>
            {/foreach}
          </ol>
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

{/block}
