console.log('species-gallery.js');
$(function () {
    const speciesCode = $('#speciesCode').attr('code')
    let data = {"key": "spCode", "value":speciesCode};
    speciesGallery(data);

    function speciesGallery(data) {
      $.ajax({
        url: `${baseUrl}/back-end/api/species/filter.php`,
        method: "POST",
        data: JSON.stringify(data),
        success: function (res) {
          console.log({"Species Gallery":res});
          if(res.status == '200'){
            let result = res.data[0];            

            let html = `<h6>Photos for <i>${result.spScName}</i></h6>`;
            html += `<div class="form-row">`;
            
            if(result.addition.length){
              result.addition.forEach((item)=>{
                item.photos.forEach((photo) => {
                  const photoAuthor = photo.author2 ? photo.author2 : photo.author1;
                  html += `<div class="col-md-3">
                    <div class="profile-img mb-2">
                        <div class="img">
                            <img src="${baseUrl}/uploads/${photo.name}" alt="${photo.name}">
                        </div>
                        <div class="author">
                          <strong>Author :</strong> <span>${photoAuthor}</span>
                        </div>
                      </div>
                  </div>`;
                })
              })
            }
              
            if(result.spPhotos.length){
              result.spPhotos.forEach((photo) => {
                const photoAuthor = photo.author2 ? photo.author2 : photo.author1;
                  html += `<div class="col-md-3">
                    <div class="profile-img mb-2">
                        <div class="img">
                            <img src="${baseUrl}/uploads/${photo.name}" alt="${photo.name}">
                        </div>
                        <div class="author">
                          <strong>Author :</strong> <span>${photoAuthor}</span>
                        </div>
                      </div>
                  </div>`;
              })
            }

            if(!result.addition.length && !result.spPhotos.length){
              html += `<div class="col-md-12">No Photos</div>`;
            }
            
            html += `</div>`;
            $('#species-thumbnails').html(html);
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }
      
  })