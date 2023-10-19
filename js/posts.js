$(function(){
	
    console.log('posts.js');
    const baseUrl = $('.navbar-brand').attr('href');

    var postType = $("#postType").val();
    var postAll = $('#postAll').DataTable({  
        "ajax": {
			url: baseUrl+"/back-end/api/post/filterAuth.php",
			method: "POST",
			data:function(d) {
				d.type = 'dataTable';
                d.key = 'postType';
                d.value = postType;
                return JSON.stringify(d);
            }
		},
        "columns" : [  
             { "data" : "sn"},  
             { "data" : "titlePhoto"},  
             { "data" : "authorName"},  
             { "data" : "categoryName"},  
             { "data" : "ordering"},  
             { "data" : "statusAction"},  
             { "data" : "createdMod"},  
             { "data" : "action"} 
        ],
		"columnDefs" : [
			{ "targets": [7], 'orderable': false, 'className': 'text-center' }
		],
		"order" : [],
		"drawCallback": function (settings) {
			feather.replace();
		} 
    }) 
	
    $(document).on("click", "#delPost", function (e) {
		e.preventDefault();
		const id = $(this).attr('data-id');
		if (confirm("Are you sure to delete?")) {
			deletePost(id);
		}
	})
	function deletePost(id){
		$.ajax({
			url: baseUrl+"/back-end/api/post/delete.php?id="+id,
			method: "DELETE",
			success: function (res) {
				if(res.status == '200'){
					postAll.ajax.reload();
				}else{
					alert('Sorry! '+res.msg)
				}
			},
			error: function(error){
				console.log(error.responseText)
			}
		});
	}

})