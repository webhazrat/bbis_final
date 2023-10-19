<?php include 'inc/header.php';
  $slug = $_GET['page'];
?>

<div class="page-header-area">
    <div class="container">
        <div class="header-body text-center">
          <h2 id="getTitle" slug="<?php echo $slug; ?>"> </h2>
        </div>
    </div>
</div>

  <section class="page single-content pt-5 pb-5">
    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
            <div id="singlePhpAjax" slug="<?php echo $slug; ?>" class="single-item-content">
              <div class="text-center">
                <div class="spinner-border text-success spinner-border-sm"> </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </section>

  <?php include 'inc/footer.php'; ?>
  <script src="js/single.js"></script>