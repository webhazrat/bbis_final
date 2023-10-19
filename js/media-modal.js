$(function(){
    console.log('media-modal.js');
    const element = document.querySelector('.navbar-brand')
    const baseUrl = element.getAttribute('href')
    
    $(document).on("click", "#selectImage", function (e) {
        e.preventDefault();
        $("#mediaModal").modal("show");
        $('#addImage').attr('wrapper', $(this).attr('wrapper'))
        $('#addImage').attr('type', $(this).attr('type'))
        $('#gallery_img').attr('type', $(this).attr('type'))
        media();
    })
    
    $(document).on("click", "#media-tab", function () {
        media();
    });
    function media() {
        $.ajax({
            url: baseUrl+"/back-end/api/media/filter.php",
            method: "POST",
            data: JSON.stringify({}),
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                console.log({'media':res});
                if(res.status == '200'){
                    const result = res.data;
                    var html = ``;
                    var last_id =  ``;
                    result.forEach((photo) => {
                        let author = photo.author2 ? photo.author2 : photo.authorName;
                        html += `<li data-placement="bottom" data-toggle="popover" data-trigger="hover" title="${photo.name}" data-content="Author: ${author}, CreatedAt: ${photo.createdAt}" id="${photo.id}" data="${photo.name}"><div class="img">${photo.photoMod}</div></li>`;
                        last_id = photo.id;
                    })
                    var button = `<button data-id='`+last_id+`' id="loadMoreBtn" class="btn btn-soft-primary">Load More</button>`;
                    $("#gallery_img").html(html);
                    $("#loadMore").html(button);
                    feather.replace();
                    $('[data-toggle="popover"]').popover();
                }else{
                    $("#gallery_img").html('No photos');
                }
            },
            error:function(error){
                console.log(error.reponseText)
            }
        });
    }
   
    $(document).on("click", "#loadMoreBtn", function(e){
        var id = $(this).data('id');
        loadMoreMedia(id);
    })
    function loadMoreMedia(id){
        $.ajax({
            url: baseUrl+"/back-end/api/media/filter.php",
            method: "POST",
            data: JSON.stringify({'last_id':id, 'load_more':true, 'length':12}),
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                let result = res.data;
                let html = ``;
                let last_id =  ``;
                result.forEach((e) => {
                    html += `<li data-placement="bottom" data-toggle="popover" data-trigger="hover" data-content="` + e.name + `" id="` + e.id + `" data="` + e.name + `"><div class="img">`+e.photoMod+`</div></li>`;
                    last_id = e.id;
                })
                let button = `<button data-id='`+last_id+`' id="loadMoreBtn" class="btn btn-soft-primary">Load More</button>`;
                if(result.length < 12){
                    button = '';
                }
                $("#gallery_img").append(html);
                $("#loadMore").html(button);
                feather.replace();
                $('[data-toggle="popover"]').popover();
            },
            error:function(error){
                console.log(error.reponseText)
            }
        });
    }

    $(document).on("click", "#gallery_img li", function (e) {
        e.preventDefault();
        if($(this).parents('#gallery_img').attr('type') == 'multiple'){
            $(this).toggleClass('selected');
        }else{
            $('#gallery_img li').not(this).removeClass('selected');
            $(this).toggleClass('selected');
        }
    });

    $(document).on("click", "#addImage", function (e) {
        e.preventDefault();
        const wrapper   = $(this).attr('wrapper');
        const type      = $(this).attr('type');
        const dataId    = $("#gallery_img li.selected").attr('id');
        const data      = $("#gallery_img li.selected").attr('data');
        if(type == 'multiple'){
            let html = ''
            $('#gallery_img').find('li.selected').map(function() {
                html += '<span><i class="chipsBtn"><i data-feather="x"></i></i><img dataId="' + $(this).attr('id') + '" src="../uploads/' + $(this).attr('data') + '" alt="" /></span>';
            });
            $("#mediaModal").modal("hide");
            if($("#"+wrapper).html() == ''){
                $("#"+wrapper).html(html);
            }else{
                $("#"+wrapper).append(html);
            }
        }else{
            if (data != '' && data != undefined) {
                let html = '<span><i class="chipsBtn"><i data-feather="x"></i></i><img dataId="' + dataId + '" src="../uploads/' + data + '" alt="" /></span>';
                $("#mediaModal").modal("hide");
                $("#"+wrapper).html(html);
            }
        }
        feather.replace();
        
    });
    
    $(document).on("click", ".addedImage span .feather", function () {
        $(this).parents('.addedImage span').remove();
    })
})