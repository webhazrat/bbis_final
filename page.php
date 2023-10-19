<?php 
  include 'inc/header.php';
  $slug = $_GET['page'];
?>

<div class="page-header-area">
    <div class="container">
        <div class="header-body text-center">
            <h2 id="getTitle" slug="<?php echo $slug; ?>"> </h2>
        </div>
    </div>
</div>

<section class="page pt-5 pb-5">
  <div class="container">
    <div class="row">
        <div class="col-md-12">
          <div id="pagePhpAjax" slug="<?php echo $slug; ?>">
            <div class="text-center">
              <div class="spinner-border text-success spinner-border-sm"> </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</section>
  
  <?php include 'inc/footer.php'; ?>
  <script src="js/page.js"></script>