$(function(){
    console.log('post-categories.js');
    const baseUrl = $('.navbar-brand').attr('href');

    $(document).on("click", "#createCategoryBtn", function (e) {
		e.preventDefault();
		const name = $('#catName').val();
		create({name});
	})
    function create(data){
		$.ajax({
			url: baseUrl+"/back-end/api/category/create.php",
			method: "POST",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.prealoder').show();
			},
			complete: function () {
				$('.prealoder').hide();
			},
			success: function (res) {
				if(res.status == '200'){
                    categories();
					$("#createCategoryForm")[0].reset();
					$("#createCategoryModal").modal('hide');
				}else{
					alert("Sorry! "+res.msg);
				}
			},
			error: function (error){
				console.log(error.responseText);
			}
		});
	}

	categories()
	function categories(){
		$.ajax({
			url: baseUrl+"/back-end/api/category/filter.php",
			method: "POST",
			data:JSON.stringify({}),
			success: function (res) {
                const result = res.data;
                let option = '';
                result.forEach(e => {
                   option += `<option value="${e.name}">${e.name}</option>`; 
                });
                $("#postCategory").html(option).selectpicker('refresh');
			},
			error: function(e){
				console.log(e.responseText);
			}
		});
	}


})