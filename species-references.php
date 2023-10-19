<?php 
  include 'inc/header.php';
  $page = $_GET['page'];
  $code = explode('/', $page)[1];
  
?>

<section id="speciesCode" class="species-references-area pt-5 pb-5" code="<?php echo $code; ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
              <div id="references">
                
              </div>
            </div>
        </div>        
    </div>
</section>

<?php include 'inc/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>/js/species-references.js"></script>