$(function(){

    console.log('people-type.js');

    $(document).on('click', '#peopleTypeCreateBtn', function(e){
        e.preventDefault();
        const name = $('#typeName').val();
        const status = $('#typeStatus').val();
        const order = $('#typeOrder').val();
        create({name, status, order});
    })
    function create(data){
        $.ajax({
            url : "../back-end/api/people/create.php",
            method : "POST",
            data: JSON.stringify(data),
            beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
            success:function(res){
                if(res.status == '200'){
                    $('.selectpicker').selectpicker('refesh');
                    $('#peopleTypeCreateFrom')[0].reset();
                    $('#peopleTypeCreateModal').modal('hide');
                    peopleType.ajax.reload();
                }else{
                    alert(`Sorry! ${res.msg}`);
                }
            },
            error:function(error){
                console.log(error.responseText);
            }
        });
    }

    const peopleType = $('#peopleTypeAll').DataTable({
        "processing": true,
		"serverSide": true,
		"ajax": {
			url: "../back-end/api/people/filterAuth.php",
			type: "POST",
			data: function (d) {
                d.key = 'all';
                d.value = '1';
                d.type = 'dataTable';
				return JSON.stringify(d);
			}
		},
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columns" : [  
            { "data" : "sn"},  
            { "data" : "name"},  
            { "data" : "count"},  
            { "data" : "ordering"},  
            { "data" : "authorName"},  
            { "data" : "statusName"},
            { "data" : "createdMod"},
            { "data" : "action"}
       ],
        "columnDefs": [
            { "targets": [7], 'orderable': false, 'className': 'text-center' }
        ],
        "order": [],
        "drawCallback": function (settings) {
            feather.replace();
        }
    })

    $(document).on('click', '#typeUpdate', function(e){
        e.preventDefault();
        const id = $(this).attr('data_id');
        findOne(id);
        $('#peopleTypeUpdateBtn').attr('data_id', id);
        $('#peopleTypeUpdateModal').modal('show');
    })
    function findOne(id){
        $.ajax({
            url : `../back-end/api/people/findOne.php?id=${id}`,
            method : "GET",
            success:function(res){
                if(res.status == '200'){
                    const result = res.data[0];
                    $('#uTypeName').val(result.name);
                    $('#uTypeStatus').selectpicker('val', result.status);
                    $('#uTypeOrder').val(result.ordering);
                }else{
                    alert(`Sorry! ${res.msg}`);
                }
            },
            error:function(error){
                console.log(error.responseText);
            }
        });
    }

    $(document).on('click', '#peopleTypeUpdateBtn', function(e){
        e.preventDefault();
        const id = $(this).attr('data_id');
        const name = $('#uTypeName').val();
        const status = $('#uTypeStatus').val();
        const order = $('#uTypeOrder').val();
        update(id, {name, status, order});
    })
    function update(id, data){
        $.ajax({
            url: `../back-end/api/people/update.php?id=${id}`,
            method: 'PUT',
            data: JSON.stringify(data),
            beforeSend: function(){
                $('.preloader').show();
            },
            complete: function(){
                $('.preloader').hide();
            },
            success: function(res){
                if(res.status == '200'){
                    peopleType.ajax.reload();
                    $('#peopleTypeUpdateModal').modal('hide');
                    console.log(res)
                }else{
                    alert(`Sorry! ${res.msg}`);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        })
    }

    $(document).on('click', '#typeDelete', function(e){
        e.preventDefault();
        if(confirm('Are you sure to delete?')){
            const id = $(this).attr('data_id');
            deleteType(id);
        }
    })

    function deleteType(id){
        $.ajax({
            url: `../back-end/api/people/delete.php?id=${id}`,
            method: 'DELETE',
            success: function(res){
                if(res.status == '200'){
                    peopleType.ajax.reload();
                    alert(`Success! ${res.msg}`);
                }else{
                    alert(`Sorry! ${res.msg}`);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        })
    }

})