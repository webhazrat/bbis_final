<?php 
  include 'inc/header.php';
  $page = $_GET['page'];
  $slug = explode('/', $page)[1];
?>

<div class="page-header-area">
    <div class="container">
        <div class="header-body text-center">
            <h2 id="getTitle">  </h2>
        </div>
    </div>
</div>

<section class="species-list-area pt-5 pb-5">
    <div class="container">
        <div class="row" id="speciesAjax" per_page="12" slug="<?php echo $slug; ?>">
            <div class="col-md-12 text-center">
                <div class="spinner-border text-success spinner-border-sm"> </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="pagination-area">
                    <nav>
                        <ul id="speciesPagination" class="pagination justify-content-center">
                            
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'inc/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>/js/species-list.js"></script>