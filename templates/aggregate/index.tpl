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
    <div class="statements-carousel pb-4">
      <h3 class="mt-4 mb-4 capitalize-first-word serif-text">{t}title-recent-statements{/t}</h3>

      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner pb-5">

          <!-- ####################### item 1 ################-->
          <div class="carousel-item active mb-4">
            <div class="container">
              <div class="row mb-4">

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/6/200.jpg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          Finlanda vrea să ia din experții români pentru președinția UE.
                        </div>
                        <a href="/dignitas/www/afirmatie/1" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Viorica Dăncilă</a>, 23 iunie 2019
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/8/200.jpg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur
                          magni dolores eos qui ratione voluptatem sequi nesciunt.
                        </div>
                        <a href="/dignitas/www/afirmatie/1" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Gabriela Firea</a>, 23 iunie 2019
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <div class="row">

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/9/200.jpg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                          labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                          laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                          voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </div>
                        <a href="/dignitas/www/afirmatie/1" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Klaus Iohannis</a>, 23 iunie 2020
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/10/200.jpg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                          totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                          dicta sunt explicabo.
                        </div>
                        <a href="/dignitas/www/afirmatie/1" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Victor Ponta</a>, 23 iunie 2019
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- ####################### item 2 ################
          <div class="carousel-item mb-4">
            <div class="container">
              <div class="row">

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/5/200.svg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          [Protestele de pe 10 august 2018] au fost susținute de Antifa, cea mai periculoasă grupare anarhică din lume.
                        </div>
                        <a href="/dignitas/www/afirmatie/1" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Lumea Justiției</a>, 23 iunie 2019
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/8/200.jpg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          But I must explain to you how all this mistaken idea of denouncing pleasure and praising. You how all this mistaken idea of denouncing pleasure and praising.
                        </div>
                        <a href="/dignitas/www/afirmatie/6" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Gabriela Firea</a>, 23 iunie 2019
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div-->

           <!--####################### item 3 ################-->
          <div class="carousel-item mb-4">
            <div class="container">
              <div class="row">

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/4/200.svg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          Încadrarea în mod obligatoriu a elevilor în programe de educație sexuală reprezintă un atentat asupra inocenței copiilor,
                          împiedicînd dezvoltarea lor firească și marcîndu-i pe aceștia pentru întreaga viață.
                        </div>
                        <a href="/dignitas/www/afirmatie/1" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Lumea Justiției</a>, 23 iunie 2019
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      <img src="/dignitas/www/imagine-entitate/8/200.jpg" class="pic rounded-circle img-fluid no-outline" width="128" height="128">
                    </div>
                    <div class="col-9">
                      <div class="bubble mr-3 mb-2 p-3">
                        <div class="bubble-title ml-1">
                          But I must explain to you how all this mistaken idea of denouncing pleasure and praising. You how all this mistaken idea of denouncing pleasure and praising.
                        </div>
                        <a href="/dignitas/www/afirmatie/1" class="stretched-link"></a>
                      </div>
                      <div class="bubble-author"> —
                        <a href="/dignitas/www/autor/6">Gabriela Firea</a>, 23 iunie 2019
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
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
