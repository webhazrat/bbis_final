<?php include 'inc/header.php'; ?>

    <div class="page-header-area">
        <div class="container">
            <div class="header-body text-center">
                <h2> News and Events </h2>
            </div>
        </div>
    </div>

    <section class="news pt-5 pb-5">
      <div class="container">
        <div class="row" id="newsAjax" per_page="8">
          <div class="col-md-12 text-center">
            <div class="spinner-border text-success spinner-border-sm"> </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="pagination-area">
              <nav aria-label="Page navigation example">
                <ul id="newsPagination" class="pagination justify-content-center">
                  
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </section>

<?php include 'inc/footer.php'; ?>
<script src="js/news.js"></script>