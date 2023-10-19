$(function(){
    console.log('media.js');
    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');

    // $(document).on("click", ".dataTables_paginate a", function(e){
    //     e.preventDefault();
    //     const page = $(this).attr('data-dt-idx');
    //     window.history.replaceState(null, null, '?p='+page);
    // });
    
    const mediaAll = $('#mediaAll').DataTable({
        'processing': true,
        'serverSide': true,
        "ajax": {
            url: baseUrl+"/back-end/api/media/filter.php",
            type: "POST",
            data: function(d) {
                d.type = 'dataTable';
                return JSON.stringify(d);
            }
        },
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "columns": [
            { "data": "sn" },
            { "data": "namePhoto" },
            { "data": "filesize" },
            { "data": "authorName" },
            { "data": "author2" },
            { "data": "createdAt" },
            { "data": "action" }
        ],
        "columnDefs": [
            { "targets": [6], 'orderable': false, 'className': 'text-center' }
        ],
        "order": [],
        "drawCallback": function (settings) {
            feather.replace();
        }
    })
    
    $(document).on("click", "#viewMedia", function (e) {
        e.preventDefault();
        const id = $(this).attr('data-id');
        findOne(id);
        $("#mediaViewModal").modal('show');
    })
    function findOne(id){
        $.ajax({
            url: baseUrl+"/back-end/api/media/findOne.php?id="+id,
            method: "GET",
            success: function (res) {
                if(res.status == '200'){
                    const result = res.data[0];
                    $("#filePreview").html('<img src="../uploads/' + result.name + '" alt="" />');
                    $("#dateTime").text(result.dateTimeMod);
                    $("#fileUrl").val(result.url);
                    $("#fileName").text(result.name);
                    $("#fileSize").text(result.size);
                    $("#fileDim").text(result.dimensions);
                }else{
                    alert('Sorry! '+res.msg)
                }
            },
            error: function(error){
                console.log(error.responseText)
            }
        });
    }

     $(document).on("click", ".copyToClipboard", function(e){
        const copyText = $("#fileUrl").val();
        navigator.clipboard.writeText(copyText);
        alert("Copied!");
    })

    $(document).on("click", "#delMedia", function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var path = $(this).attr('data');
        if (path != '') {
            if (confirm("Are you sure to delete?")) {
                deleteMedia(id, path);
            } else {
                return false;
            }
        }
    })
    function deleteMedia(id, path){
        $.ajax({
            url: baseUrl+"/back-end/api/media/delete.php?id="+id+"&path="+path,
            method: "DELETE",
            success: function (res) {
                if(res.status == '200'){
                    mediaAll.ajax.reload();
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        });
    }
})