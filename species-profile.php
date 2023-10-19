<?php 
  include 'inc/header.php';
  $page = $_GET['page'];
  $code = explode('/', $page)[1];
?>

<section id="speciesCode" class="species-profile-area pt-5 pb-5" code="<?php echo $code; ?>">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-10">
                <div id="species-profile">
                    <div class="text-center">
                        <div class="spinner-border text-success spinner-border-sm"> </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>


<?php include 'inc/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>/js/species-profile.js"></script>