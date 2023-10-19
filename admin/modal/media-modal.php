<div id="mediaModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Media Gallery</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="upload-tab" data-toggle="tab" href="#upload-media" role="tab" aria-controls="upload" aria-selected="true">Upload</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="media-tab" data-toggle="tab" href="#media-gallery" role="tab" aria-controls="media" aria-selected="false">Gallery</a>
                    </li>
                </ul>
                <div class="tab-content mt-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="upload-media" role="tabpanel" aria-labelledby="upload-tab">
                        <div class="upload-media">
                            <div id="drag-and-drop-zone" class="dm-uploader p-5">
                                <h6 class="mb-3 mt-3 text-muted">Drag &amp; drop files here</h6>

                                <div class="btn btn-primary mb-3">
                                    <span>Select files</span>
                                    <input type="file" title='Click to add Files' />
                                </div>
                            </div>
                            <p class="size-notify">Maximum upload file size: 2 MB</p>

                            <ul class="list-unstyled" id="files">
                            </ul>
                            <ul class="list-unstyled" id="debug">
                            </ul>

                            <script type="text/html" id="files-template">
                                <li class="media">
                                    <div class="container-fluid no-padding">
                                        <div class="row align-items-center">
                                            <div class="col-lg-9">
                                                %%filename%% - (<span class="text-muted">Waiting</span>)
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </script>

                            <script type="text/html" id="debug-template">
                                <li class="text-%%color%%">%%message%%</li>
                            </script>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="media-gallery" role="tabpanel" aria-labelledby="media-tab">
                        <div id="allMedia" class="text-center">
                            <ul id="gallery_img" class="clearfix img-gallery">
                                <div class="preloader spinner-border text-primary spinner-border-sm"></div>
                            </ul>
                        </div>
                        <div class="btn-area d-flex justify-content-between align-items-center mt-2"> 
                            <div id="loadMore"> </div>
                            <a href="#" class="btn btn-primary" id="addImage">Add Image</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>