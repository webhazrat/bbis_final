$(function(){

	console.log('species.js');
    
    const speciesList = $('#speciesList').DataTable({
        'processing': true,
        'serverSide': true,
        "ajax": {
            url: "../back-end/api/species/filterAuth.php",
            type: "POST",
            data: function(d) {
                d.type = 'dataTable';
                return JSON.stringify(d);
            }
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columns": [
            { "data": "sn" },
            { "data": "authorMod" },
            { "data": "hierarchyName" },
            { "data": "spCode" },
            { "data": "spScName" },
            { "data": "spEngName" },
            { "data": "spKingdom" },
            { "data": "spPhylum" },
            { "data": "spClass" },
            { "data": "spFamily" },
            { "data": "reviewNums" },
            { "data": "statusName" },
            { "data": "createdMod" },
            { "data": "action" }
        ],
        "columnDefs": [
            { "targets": [13], 'orderable': false, 'className': 'text-center' }
        ],
        "order": [],
        "drawCallback": function (settings) {
            feather.replace();
        }
    });

    $(document).on('click', '#speciesStatus', function(e){
        e.preventDefault()
        findOne($(this).attr('data_id'))
        $('#speciesStatusModal').modal('show')
    })

    function findOne(id){
        $.ajax({
            url: "../back-end/api/species/findOne.php?id="+id,
            method: "GET",
            beforeSend: function () {
                $(".preloader").show();
            },
            complete: function () {
                $(".preloader").hide();                
            },
            success: function (res) {
                console.log({'findOne':res});
                if(res.status == '200'){
                    let result = res.data[0]
                    $('#speciesStatusBtn').attr('dataId', result.id)
                    $('#speciesStatusBtn').attr('groupId', result.groupId)
                    $('.modal-title').text(result.spScName)
                    $('#status').selectpicker('val', result.status)
                    $('#comment').val(result.comment)
                    result.status == '5' ? $('#commentArea').removeClass('hide') : $('#commentArea').addClass('hide')
                }
            },
            error: function(error){
                console.log(error.responseText)
            }
        })
    }

    $(document).on('change', '#status', function(){
       $(this).val() == '5' ? $('#commentArea').removeClass('hide') : $('#commentArea').addClass('hide')
    })

    $(document).on('click', '#speciesStatusBtn', function(e){
        e.preventDefault()
        let id = $(this).attr('dataId')
        let data = {}
        data.groupId = $(this).attr('groupId')
        data.status = $('#status').val()
        data.comment = $('#comment').val()
        updateStatus(id, data)
    })

    function updateStatus(id, data){
        $.ajax({
            url: `../back-end/api/species/updateStatus.php?id=${id}`,
            method: "PUT",
            data: JSON.stringify(data),
            beforeSend: function () {
                $(".preloader").show();
            },
            complete: function () {
                $(".preloader").hide();                
            },
            success: function (res) {
                if(res.status == '200'){
                    $('#speciesStatusModal').modal('hide')
                    speciesList.ajax.reload();
                    console.log('Success! '+res.msg)
                }else{
                    alert('Sorry! '+res.msg)
                }
            },
            error: function (error) {
                console.log(error.responseText);
            }
        });
    }

    $(document).on('click', '#speciesDelete', function(e){
        e.preventDefault()
        const id = $(this).attr('data_id')
        if (confirm("Are you sure to delete?")) {
            speciesDel(id)
		}
    })
    function speciesDel(id){
        $.ajax({
			url: "../back-end/api/species/delete.php?id="+id,
			method: "DELETE",
			success: function (res) {
				if(res.status == '200'){
					speciesList.ajax.reload();
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