$(function(){

	console.log('species-addition.js');

    const spId = $('#speciesAdditionList').attr('data-id');
    
    const speciesAdditionList = $('#speciesAdditionList').DataTable({
        'processing': true,
        'serverSide': true,
        "ajax": {
            url: "../back-end/api/species-addition/filterAuth.php",
            type: "POST",
            data: function(d) {
                d.key = 'spId';
                d.value = spId;
                d.type = 'dataTable';
                return JSON.stringify(d);
            }
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columns": [
            { "data": "sn" },
            { "data": "authorMod" },
            { "data": "locality" },
            { "data": "districtName" },
            { "data": "coordination" },
            { "data": "collectionDate" },
            { "data": "comment" },
            { "data": "statusMod" },
            { "data": "createdAt" },
            { "data": "action" }
        ],
        "columnDefs": [
            { "targets": [9], 'orderable': false, 'className': 'text-center' }
        ],
        "order": [],
        "drawCallback": function (settings) {
            feather.replace();
        }
    });

    
    $(document).on('click', '#speciesAdditionStatus', function(e){
        e.preventDefault();
        findOne($(this).attr('data_id'));
        $('#speciesAdditionStatusModal').modal('show');
    })

    function findOne(id){
        $.ajax({
            url: `../back-end/api/species-addition/findOne.php?id=${id}`,
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
                    $('#speciesAdditionStatusBtn').attr('dataId', result.id)
                    $('#speciesAdditionStatusBtn').attr('groupId', result.groupId)
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

    $(document).on('click', '#speciesAdditionStatusBtn', function(e){
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
            url: "../back-end/api/species-addition/updateStatus.php?id="+id,
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
                    $('#speciesAdditionStatusModal').modal('hide')
                    speciesAdditionList.ajax.reload();
                }else{
                    alert('Sorry! '+res.msg)
                }
            },
            error: function (error) {
                console.log(error.responseText);
            }
        });
    }

    // $(document).on('click', '#speciesAdditionEdit', function(e){
    //     e.preventDefault();
    //     const id = $(this).attr('data_id');
    // })
    // function speciesAdditionFindOne(){
    //     $.ajax({
    //         url: "../back-end/api/species-addition/updateStatus.php?id="+id,
    //         method: "PUT",
    //         data: JSON.stringify(data),
    //         beforeSend: function () {
    //             $(".preloader").show();
    //         },
    //         complete: function () {
    //             $(".preloader").hide();                
    //         },
    //         success: function (res) {
    //             if(res.status == '200'){
    //                 $('#speciesAdditionStatusModal').modal('hide')
    //                 speciesAdditionList.ajax.reload();
    //             }else{
    //                 alert('Sorry! '+res.msg)
    //             }
    //         },
    //         error: function (error) {
    //             console.log(error.responseText);
    //         }
    //     });
    // }

})