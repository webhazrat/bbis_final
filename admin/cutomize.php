<?php include 'inc/header.php'; ?>

<div class="main-content-area">

	<div class="content-section">
		<div class="section-title d-flex justify-content-between align-items-center">
			<h3>Customize</h3>
		</div>
	</div>

	<div class="form-row">
		<div class="col-lg-4">
			<div class="content-section">
				<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home">Side Identity</a>
					<a class="nav-link" id="v-pills-slider-tab" data-toggle="pill" href="#v-pills-slider">Slider</a>
					<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile">Colors</a>
					<a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings">Settings</a>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="content-section">
				<div class="tab-content" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="v-pills-home">
						<div class="siteIdentity">
							<form method="post">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Logo</label>
											<div class="addedImage mb-2" id="featureImage">

											</div>
											<a href="#" class="btn btn-soft-primary" id="selectImage" wrapper="featureImage">Select Image</a>
										</div>
										<div class="form-group">
											<label for="">Site Title</label>
											<input type="text" id="siteTitle" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-btn-area text-right">
									<button type="submit" id="siteIdentityBtn" class="btn btn-primary">Publish</button>
								</div>
							</form>
						</div>
					</div>
					
					<div class="tab-pane fade" id="v-pills-slider">
						<form method="post">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="sliderTitle">Title</label>
										<input type="text" id="sliderTitle" class="form-control">
									</div>

									<div class="form-group">
										<label for="sliderDescription">Description</label>
										<textarea id="sliderDescription" rows="4" class="form-control"></textarea>
									</div>
								</div>
							</div>
							<div class="form-btn-area text-right">
								<button type="submit" id="sliderContentBtn" class="btn btn-primary">Publish</button>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="v-pills-profile">...</div>
					<div class="tab-pane fade" id="v-pills-messages">...</div>
					<div class="tab-pane fade" id="v-pills-settings">...</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	include 'modal/media-modal.php';
	include 'inc/footer.php'; 
?>
<script src="../plugins/js/jquery.dm-uploader.min.js"></script>
<script src="../plugins/js/demo-ui.js"></script>
<script src="../plugins/js/demo-config.js"></script>
<script src="../js/media-modal.js"></script>
<script src="../js/customize.js"></script>