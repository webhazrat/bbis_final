<?php 
    include 'inc/header.php';
?>

    <div class="page-header-area">
        <div class="container">
            <div class="header-body text-center">
                <h2> Contributors </h2>
            </div>
        </div>
    </div>
        
    <section class="pt-5 pb-5">
        <div class="container">
            <div class="row" id="contributorsAjax" per_page="16">
                <div class="text-center">
                    <div class="spinner-border text-success spinner-border-sm"> </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pagination-area">
                        <nav>
                            <ul id="peoplePagination" class="pagination justify-content-center">
                                
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include 'inc/footer.php'; ?>

<script src="js/contributors.js"></script>
