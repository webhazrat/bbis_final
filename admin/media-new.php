<?php include "inc/header.php"; ?>

<div class="main-content-area">

	<div class="title-section">
		<div class="section-title d-flex justify-content-between align-items-center">
			<h3>Upload New Media</h3>
			<div class="title-links">
				<a href="media.php" class="btn btn-primary">Media Library</a>
			</div>
		</div>
	</div>

	<div class="content-section">
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

<?php include "inc/footer.php"; ?>
<script src="../plugins/js/jquery.dm-uploader.min.js"></script>
<script src="../plugins/js/demo-ui.js"></script>
<script src="../plugins/js/demo-config.js"></script>