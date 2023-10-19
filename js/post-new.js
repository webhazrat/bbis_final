$(function(){

    console.log('post-new.js');
    const element = document.querySelector('.navbar-brand')
    const baseUrl = element.getAttribute('href')

    $(document).on("keyup", "#postTitle", function(){
		$("#postSlug").val(convertToSlug($(this).val().trim()));
	})
	function convertToSlug(Text){
		return Text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
	}

    $(document).on("click", "#postPublish", function (e) {
		e.preventDefault();
		const postType 		= $(this).attr('type');
		const title 		= $("#postTitle").val();
		const slug 			= $("#postSlug").val();
		const content 		= $("#postContent").val();
		const excerpt 		= $("#postExcerpt").val();
		const image 		= $("#featureImage img").attr('dataId') ? $("#featureImage img").attr('dataId') : '';
		const template 		= $("#template").val() ? $("#template").val(): '';
		const category 		= $("#postCategory").val() ? $("#postCategory").val(): '';
		const status 		= $('#status').val();
		const ordering 		= $("#ordering").val() ? $("#ordering").val(): '';
		create({postType, title, slug, content, excerpt, image, template, category, status, ordering});
	})
    function create(data){
		$.ajax({
			method: "POST",
			url: baseUrl+"/back-end/api/post/create.php",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
				if(res.status == '200'){
					$('#postNewForm')[0].reset();
					$(".selectpicker").selectpicker('refresh');
					$(".summerNote").summernote('reset');
					$("#featureImage").html('');
					alert('Success! '+res.msg);
				}else{
					alert('Sorry! '+res.msg);
				}
			},
			error: function (error){
				console.log(error.responseText);
			}
		});
	}

})