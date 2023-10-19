<?php 
  include 'inc/header.php';
  $page = $_GET['page'];
  $slug = explode('/', $page)[1];
?>

<div class="page-header-area">
    <div class="container">
        <div class="header-body text-center">
            <h2 id="getTitle"> </h2>
        </div>
    </div>
</div>

<section class="page pt-5 pb-5">
  <div class="container">
    <div class="row">
        <div class="col-md-12">
          <div id="singleGroupAjax" slug="<?php echo $slug; ?>">
            <div class="text-center">
              <div class="spinner-border text-success spinner-border-sm"> </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</section>
  
<?php include 'inc/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>/js/group.js"></script>