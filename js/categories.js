$(function(){
    console.log('categories.js');
    const baseUrl = $('.navbar-brand').attr('href');

    $(document).on("click", "#createCategoryBtn", function (e) {
		e.preventDefault();
		const name = $("#catName").val();
		create({name});
	})
    function create(data){
		$.ajax({
			url: baseUrl+"/back-end/api/category/create.php",
			method: "POST",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
				if(res.status == '200'){
					categoryAll.ajax.reload();
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

    const categoryAll = $('#categoryAll').DataTable({  
		'processing': true,
        'serverSide': true,
        "ajax": {
            url: baseUrl+"/back-end/api/category/filter.php",
            type: "POST",
            data: function(d) {
                d.type = 'dataTable';
                return JSON.stringify(d);
            }
        },
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "columns" : [  
             { "data" : "sn"},  
             { "data" : "name"},  
             { "data" : "authorName"},  
             { "data" : "count"},  
             { "data" : "createdMod"},  
             { "data" : "action"} 
        ],
		"columnDefs" : [
			{ "targets": [5], 'orderable': false, 'className': 'text-center' }
		],
		"order" : [],
		"drawCallback": function (settings) {
			feather.replace();
		} 
    })

    $(document).on("click", "#editCategory", function (e) {
		e.preventDefault();
		$("#updateCategoryBtn").data('id', $(this).data('id'));
		$("#u_cat_name").val($(this).data('name'));
		$("#updateCategoryModal").modal("show");
	})
    $(document).on("click", "#updateCategoryBtn", function (e) {
		e.preventDefault();
		const id = $(this).data('id');
		const name = $("#u_cat_name").val();
		update(id, {name});
	})
    function update(id, data){
		$.ajax({
			url: baseUrl+"/back-end/api/category/update.php?id="+id,
			method: "PUT",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
				if(res.status == '200'){
					categoryAll.ajax.reload();
					$("#updateCategoryForm")[0].reset();
					$("#updateCategoryModal").modal('hide');
				}else{
					alert('Sorry! '+res.msg)
				}
			},
			error: function(e){
				console.log(e.responseText);
			}
		});
	}

    $(document).on("click", "#delCategory", function (e) {
		e.preventDefault();
		const id = $(this).attr('data-id');
		if (confirm("Are you sure to delete!")) {
			deleteCategory(id);
		}
	})
    function deleteCategory(id){
		$.ajax({
			url: baseUrl+"/back-end/api/category/delete.php?id="+id,
			method: "DELETE",
			success: function (res) {
				if(res.status == '200'){
					categoryAll.ajax.reload();
				}else{
					alert('Sorry! '+res.msg);
				}
			},
			error: function(e){
				console.log(e.responseText);
			}
		});
	}
})