console.log('species-profile.js');

$(function () {
    
    const speciesCode = $('#speciesCode').attr('code');
    speciesProfile({"key": "spCode", "value":speciesCode});

    function speciesProfile(data) {
      $.ajax({
        url: `${baseUrl}/back-end/api/species/filter.php`,
        method: "POST",
        data: JSON.stringify(data),
        success: function (res) {
          console.log({"Species Profile":res});
          if (res.status == '200'){
            let result = res.data[0];
            
            var image = '';
            var photoAuthor = '';
            result.addition.forEach((item) => {
                if(item.photos.length){
                    image = item.photos[0].name;
                    photoAuthor = item.photos[0].author2 ? item.photos[0].author2 : item.photos[0].author1;
                }
            })

            if(image == '' && result.spPhotos.length){
                image = result.spPhotos[0].name;
                photoAuthor = result.spPhotos[0].author2 ? result.spPhotos[0].author2 : result.spPhotos[0].author1;
            }

            let photo = image ?
            `<a href="${baseUrl}/gallery/${result.spCode}">
                <div class="img">
                    <img src="${baseUrl}/uploads/${image}" alt="${image}">
                </div>
            </a>` : 
            `<div class="icon"><img src="${baseUrl+'/assets/images/no-img.png'}" alt="no-img"></div>`;
            
            let author = photoAuthor ? `<div class="author">
                <strong>Author :</strong> <span>${photoAuthor}</span>
            </div>` : '';
            
            let spAcName = result.spAcName.length ? result.spAcName.join(', ') : ''
            let spSeq = result.spSeq.length ? result.spSeq.join(', ') : ''
            let spOc = result.spOc.length ? result.spOc.join(', ') : ''
            let iucnBd = result.spIucnBd ? $.parseHTML(result.spIucnBd)[0].textContent : ''
            let iucnGb = result.spIucnGb ? $.parseHTML(result.spIucnGb)[0].textContent : ''
            let iucnBdYear = result.spIucnBdYear ? `(${result.spIucnBdYear})` : ''
            let iucnGbYear = result.iucnGbYear ? `(${result.iucnGbYear})` : ''
            
            let html = `
            <div class="profile-title mb-2">
                <h6><i>${result.spScName}</i> ${result.spScNameAuth}</h6>
                <ul>
                    <li><strong>English Name:</strong> ${result.spEngName}</li>
                    <li><strong>Local Name:</strong> ${result.spLocalName}</li>
                </ul>
            </div>

            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="profile-img mb-3">
                        ${photo}
                        ${author}
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="profile-feature">
                        <ul>
                            <li><strong>Kingdom:</strong> ${result.spKingdom} </li>
                            <ul>
                                <li><strong>Phylum:</strong> ${result.spPhylum} </li>
                                <ul> 
                                    <li><strong>Class:</strong> ${result.spClass} </li>
                                    <ul>
                                        <li><strong>Order:</strong> ${result.spOrder} </li>
                                        <ul> 
                                            <li><strong>Family:</strong> ${result.spFamily} </li>
                                            <ul>
                                                <li><strong>Subfamily:</strong> <i>${result.spSubFamily}</i> ${result.spSubFamily}</li>
                                                <ul>
                                                    <li><strong>Genus:</strong> <i>${result.spGenus}</i> ${result.spGenus}</li>
                                                    <ul>
                                                        <li><strong>Species:</strong> <i>${result.spSpecies}</i> ${result.spSpeciesAuth}</li>
                                                    </ul>
                                                </ul>
                                            </ul>
                                        </ul>
                                    </ul>
                                </ul>
                            </ul>
                        </ul>
                    </div>
                </div>
            </div>
                                  
            <div class="row">
                <div class="col-md-12">
                    <div class="species-body">
                        <div class="body-part">
                            <h6>Accepted Name / Synonym :</h6>
                            <p>${spAcName}</p>
                        </div>
                            
                        <div class="body-part">
                            <h6>Short Decription / Identification :</h6>
                            <p>${result.spShortDes}</p>
                        </div>

                        <div class="body-part">
                            <h6>Habitat :</h6>
                            <p>${result.spHabitat}</p>
                        </div>
                        
                        <div class="body-part">
                            <h6>Sequences :</h6>
                            <p>${spSeq}</p>
                        </div>

                        <div class="body-part">
                            <h6>Conservation status : </h6>
                            <p> <strong>IUCN status in Bangladesh:</strong> ${iucnBd} ${iucnBdYear}</p>
                            <p> <strong>Global IUCN status:</strong> ${iucnGb} ${iucnGbYear}</p>
                            <strong>CITIS :</strong>
                            <p>${result.spCitis}</p>
                        </div>

                        <div class="body-part">
                            <h6>Occurrence : <span>${spOc}</span></h6>
                            <strong>Distribution in Bangladesh:</strong>  
                            <div id="map" style="max-width:500px"></div>
                            <p><span>${result.spBdDist}</span></p>
                            <strong>Global Distribution:</strong>
                            <p><span>${result.spGbDist}</span></p>
                        </div>
                        
                        <div class="body-part">
                            <h6>Biology:</h6>
                            <p>${result.spBiology}</p>
                        </div>

                        <div class="body-part">
                            <h6>Cite This Page :</h6>
                            <p>${result.spCitePage}</p>
                        </div>

                        <div class="body-part">
                            <h6><a href="${baseUrl+'/references/'+result.spCode}">References</a></h6>
                        </div>
                    </div>
                </div>
            </div>
            `;
        $('#species-profile').html(html)
        
        if(result.addition.length){
          const mapSelector = document.getElementById('map');
          mapSelector.style.height = '300px';
          var script = document.createElement('script')
          script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB-tOlvcG_X2W_jF_FlU_vGPIRNWtmKOz4&callback=initMap&libraries=places'
          script.async = true;

          let centerCoords = result.addition[0].coordination.split(',')
          
          let map
          let activeInfoWindow
          function initMap(){
            map = new google.maps.Map(mapSelector, {
              center: new google.maps.LatLng(centerCoords[0], centerCoords[1]),
              zoom: 13,
              mapTypeId: "hybrid",
              controlSize: 24,
            })
            var bounds = new google.maps.LatLngBounds();

            result.addition.forEach((item) => {
              const content =  `<h6> ${item.locality}, ${item.districtName}</h6> <strong>Collection Date:</strong> ${item.collectionDate}`
              const infoWindow = new google.maps.InfoWindow({
                content : content,
                maxWidth: 250
              })

              let coords = item.coordination.split(',')
              let position = new google.maps.LatLng(coords[0], coords[1])
              const marker = new google.maps.Marker({
                position,
                map
              })
              bounds.extend(position);
              
              marker.addListener('click', ()=> {
                if (activeInfoWindow) { activeInfoWindow.close();}
                infoWindow.open(map, marker);
                activeInfoWindow = infoWindow
              })  
            })

            map.fitBounds(bounds);
          }

          

          document.head.appendChild(script)
          window.initMap = initMap

          
          
        }
        
            feather.replace()
          }else if(res.status == '204'){
            $("#species-profile").html('<div class="col-md-12"><h6 class="text-center">Nothing found</h6></div>');
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }
    

  
  })