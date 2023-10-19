
console.log('species-list.js');

$(function () {
      
    $(document).on("click", "#speciesPagination a", function(e){
      e.preventDefault();
      const page = $(this).attr('href');
      window.history.replaceState(null, null, page);
      speciesReq();
    });

    speciesReq();
    function speciesReq(){
      const params = new window.URLSearchParams(window.location.search);
      const page = params.get('p') ? params.get('p') : '1';
      let per_page = $("#speciesAjax").attr('per_page');
      let slug = $("#speciesAjax").attr('slug');
      fetchSpcecies({slug, per_page, page});
    }
  
    function fetchSpcecies(data) {
      $.ajax({
        url: `${baseUrl}/back-end/api/species/filter.php`,
        method: "POST",
        data: JSON.stringify(data),
        success: function (res) {
          console.log({"fetchSpcecies":res});
          if (res.status == '200'){
            var result = res.data;
            $('#getTitle').text(result[0].hierarchyName.join(' > '));
            var html = '';
            
            result.forEach((item) => {

              var image = '';
              var photoNum = 0;
              if(item.addition.length){
                item.addition.forEach((add) => {
                  if(add.photos.length){
                    image = add.photos[0].name;
                  }
                  photoNum += add.photos.length;
                })
              }

              photoNum += item.spPhotos.length;
              if(image == '' && item.spPhotos.length){
                image = item.spPhotos[0].name;
              }
              
              const photo = image ? `<img src="${baseUrl}/uploads/${image}">` : `<img src="${baseUrl}/assets/images/no-img.png" alt="no-img">`;
                            
              html += `<div class="col-md-3"><div class="single-species"><a href="${baseUrl+'/species/'+item.spCode}"><div><div class="img">${photo}<span class="shadow-sm"><i data-feather="image"></i> ${photoNum} </span></div><div class="body"><span class="fact">${item.hierarchyName.join(' > ')}</span><h6>${item.spEngName}</h6> <i>${item.spScName}</i> ${item.spScNameAuth}</div><div class="sp-footer d-flex justify-content-between align-items-center"><span> <i data-feather="user"></i> ${item.authorName} </span> <span> <i data-feather="calendar"></i> ${item.createdAt} </span></div></div></a></div></div>`;
            })
            
            $("#speciesAjax").html(html);
            $("#speciesPagination").html(res.pagination);
            feather.replace();
          }else if(res.status == '204'){
            $("#speciesAjax").html('<div class="col-md-12"><h6 class="text-center">Nothing found</h6></div>');
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }
    
  })

  