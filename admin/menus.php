<?php include "inc/header.php"; ?>

<div class="main-content-area">
    <div class="title-section">
        <div class="section-title d-flex justify-content-between align-items-center">
            <h3>Menus</h3>
            <div class="title-links">
                <a href="#" id="deleteMenu" class="btn btn-danger mr-2"> Delete</a>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-lg-4">
            <div class="content-section">
                <div class="right-bar">
                    <div class="card">
                        <div class="card-header">
                            <a class="btn-link d-flex justify-content-between" data-toggle="collapse" data-target="#collapseOne"> <span>Pages</span> <span><i data-feather="chevron-down"></i></span>
                            </a>
                        </div>
                        <div id="collapseOne" class="collapse">
                            <div class="card-body pages">
                                <form id="pageForMenuForm">
                                    <div class="pagesForMenu">
                                                                         
                                    </div>
                                </form>
                            </div>
                            <div class="link-area text-right">
                                <a href="#" class="btn btn-soft-primary" id="addPage">Add to Menu</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="btn-link d-flex justify-content-between" data-toggle="collapse" data-target="#collapseTwo">
                                <span>Custom Links</span> <span><i data-feather="chevron-down"></i></span>
                            </a>
                        </div>
                        <div id="collapseTwo" class="collapse">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="linkText">Link Text</label>
                                    <input type="text" id="linkText" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="linkUrl">Link URL</label>
                                    <input type="text" id="linkUrl" class="form-control">
                                </div>
                            </div>
                            <div class="link-area text-right">
                                <a href="#" class="btn btn-soft-primary" id="addLinkToMenu">Add to Menu</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="content-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        To create a new menu
                        <input type="text" id="newMenu" name="newMenu" class="form-control" placeholder="New Menu" style="width:200px;margin:0 10px;">
                        <a href="#" id="createNewMenu">Create New Menu</a>
                    </div>
                    <div style="width:200px">
                        <select id="menuSelect" class="form-control selectpicker" title=" " > </select>
                    </div>
                </div>
            </div>
            <div class="content-section">
                <div class="cf nestable-lists">
                    <div class="dd" id="nestable">

                    </div>
                </div>
                <input type="hidden" id="nestable-output">
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<?php 
    include "modal/update-menu-item.php"; 
    include "inc/footer.php"; 
?>
<script src="../plugins/js/jquery.nestable.js"></script>
<script src="../js/menus.js"></script>

<script>
    $(function(){
         // nestable
        var updateOutput = function(e){
            var list   = e.length ? e : $(e.target),
            output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            }else {
                output.val('JSON browser support required for this demo.');
            }
        };
        // activate Nestable for list 1
        $('#nestable').nestable({
            group: 1
        }).on('change', updateOutput);
        // output initial serialised data
        updateOutput($('#nestable').data('output', $('#nestable-output')));
    })
</script>

