<?php include 'inc/header.php'; ?>

  
    <section id="banner" class="pt-4 pb-4" style="background-color:#F7F9FB">
        <div class="container">
          <div class="slider-area">
            <div id="sliderAjax" class="banner-item owl-carousel"> </div>

              <div class="search-area">
                  <div class="slider-text pr-5"> 
                    <h2 id="sliderTitle">Bangladesh Biodiversity Information System</h2> 
                    <p id="sliderDescription">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Earum repellat eius nihil, dicta sit saepe sed omnis facere</p> 
                    <div class="banner-search"> 
                      <div> 
                        <i data-feather="search"></i>
                        <input type="text" id="searchSpecies" class="form-control" placeholder="Search By Family, Scientific Name, English Name"> 
                        <div class="preloader spinner-border text-success spinner-border-sm mr-2 hide"> </div>
                      </div> 
                      <div class="search-result">

                      </div>
                    </div> 
                  </div>
              </div>

          </div>
        </div>
    </section>

    <section class="about pt-5 pb-5">      
      <div class="container">
        <div class="row align-items-center justify-content-between">
          <div class="col-md-5">
            <div id="aboutPageContent" class="about-text">
              
            </div>
          </div>
          <div class="col-md-7">
            <div class="help-type">
              <div class="form-row" id="missionAjax">

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="group pt-5 pb-5" style="background-color:#F7F9FB">
      <div class="container">
        <div class="group-area">
          <div class="row">
            <div class="col-md-12">
              <div class="section-header mb-3 d-flex justify-content-between align-items-end">
                <div>
                  <span>Group</span>
                  <h2>Species Group</h2>
                </div>
                <a href="<?php echo BASE_URL; ?>/species-group" class="btn btn-success">View All</a>
              </div>
            </div>
            
            <div class="col-md-12">
              <div id="groupAjax" class="group-slider owl-carousel">
                
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="species-items pt-5 pb-5">
      <div class="container">
        <div class="species-items-area">
          <div class="row">
            <div class="col-md-12">
              <div class="section-header text-center mb-3">
                <span>Items</span>
                <h2>Species</h2>
              </div>
            </div>
            
            <div class="col-md-12">
              <div id="speciesAjax" length="10" class="species-slider owl-carousel">
                
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="partner" style="background-color:#F7F9FB">
      <div class="container">
        <div class="partner-area pt-5 pb-5">
          <div class="row">
              <div class="col-md-12">
                  <div class="section-header text-center mb-4">
                    <h2>Partners</h2>
                  </div>
              </div>

              <div class="col-md-12">
                <div id="partnersAjax" class="owl-carousel">
                  
                </div>
              </div>
          </div>
        </div>
      </div>
    </section>
    
    <section class="news pt-5 pb-5">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header text-center mb-5">
              <span> News </span>
              <h2> News and Events </h2>
            </div>
          </div>
        </div>
        <div class="row" id="newsAjax">

        </div>
      </div>
    </section>

<?php include 'inc/footer.php'; ?>
<script src="js/home.js"></script>
