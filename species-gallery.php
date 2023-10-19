<?php 
  include 'inc/header.php';
  $page = $_GET['page'];
  $code = explode('/', $page)[1];
?>

<section id="speciesCode" class="species-thumbnails-area pt-5 pb-5" code="<?php echo $code; ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="species-thumbnails">
                    
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'inc/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>/js/species-gallery.js"></script>