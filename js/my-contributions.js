console.log('my-contributions.js');

$(function () {

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status') == 'canceled' ? '5' : '';

    if(urlParams.get('status') == 'canceled'){
        $('.header-body h2').text('Canceled Contributions')
    }
    
    const contributionsList = $('#contributionsList').DataTable({
        'processing': true,
        'serverSide': true,
        "ajax": {
            url: `${baseUrl}/back-end/api/species-addition/filterAuth.php`,
            type: "POST",
            data: function(d) {
                d.key = 'author';
                d.status = status;
                d.type = 'dataTable';
                d.for = 'my-contributions';
                return JSON.stringify(d);
            }
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columns": [
            { "data": "sn" },
            { "data": "spGroup" },
            { "data": "spCode" },
            { "data": "spScName" },
            { "data": "spEngName" },
            { "data": "spFamily" },
            { "data": "locality" },
            { "data": "districtName" },
            { "data": "statusMod" },
            { "data": "createdAt" },
            { "data": "action" }
        ],
        "columnDefs": [
            { "targets": [10], 'orderable': false, 'className': 'text-center' }
        ],
        "order": [],
        "drawCallback": function (settings) {
            feather.replace();
        }
    });
    
          
    var markers = [];
    function viewMap(info){
      
          const mapSelector = document.getElementById('map');
          mapSelector.style.height = '305px';
          var script = document.createElement('script');
          script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB-tOlvcG_X2W_jF_FlU_vGPIRNWtmKOz4&callback=initMap&libraries=places';
          script.async = true;

          let centerCoords = info.coordination.split(',')
          let map
          let activeInfoWindow
          function initMap(){
            map = new google.maps.Map(mapSelector, {
              center: new google.maps.LatLng(centerCoords[0], centerCoords[1]),
              zoom: 13,
              mapTypeId: google.maps.MapTypeId.HYBRID,
              controlSize: 24,
            })
            
            const content =  `<h6> ${info.locality} ${info.district}</h6> <strong>Collection Date:</strong> ${info.collectionDate}`
            const infoWindow = new google.maps.InfoWindow({
              content : content,
              maxWidth: 250
            })

            let position = new google.maps.LatLng(centerCoords[0], centerCoords[1])
            addMarkers(position, map);
            
            // ============================
            const input = document.getElementById("placeSearch");
            const gpsCoordination = document.getElementById("gpsCoordination")
            const currentLocationBtn = document.getElementById('currentLocation')

            const bounds = new google.maps.LatLngBounds();
            const searchBox = new google.maps.places.SearchBox(input);

            currentLocationBtn.addEventListener('click', () => {
              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                  markers = [];
                  let latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
                  // set position in the map
                  map.setCenter(latlng)
                  // adding marker in google map
                  addMarkers(latlng, map)
                  gpsCoordination.value = latlng.toString().replace(/\(|\)/g, '')
                  // get address from coordination 
                  getAddress(latlng, (address) => {
                    input.value = address
                    console.log(address)
                  })
                })
              }
            })

            map.addListener("click", (mapsMouseEvent) => {
              console.log(mapsMouseEvent.latLng)
              // set position in the map
              map.setCenter(mapsMouseEvent.latLng)
              // adding marker in google map
              addMarkers(mapsMouseEvent.latLng, map)
              gpsCoordination.value = mapsMouseEvent.latLng.toString().replace(/\(|\)/g, '')
              // get address from coordination 
              getAddress(mapsMouseEvent.latLng, (address) => {
                input.value = address
                console.log(address)
              })
            });

            searchBox.addListener("places_changed", () => {
              console.log('search')
              markers.forEach((marker)=>{
                marker.setMap(null);
              })
              const places = searchBox.getPlaces();
              if (places.length == 0) {
                return;
              }
              places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                  console.log("Returned place contains no geometry");
                  return;
                }
                
                // adding marker in google map
                addMarkers(place.geometry.location, map)
                gpsCoordination.value = place.geometry.location.toString().replace(/\(|\)/g, '')

                if (place.geometry.viewport) {
                  bounds.union(place.geometry.viewport);
                } else {
                  bounds.extend(place.geometry.location);
                }
              });

              map.fitBounds(bounds);
            });

            // ============================
            
          }
          
          document.head.appendChild(script)
          window.initMap = initMap

    }

    function getAddress(latlng, callback) {
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ 'latLng': latlng }, (results, status) => {
        if (status !== google.maps.GeocoderStatus.OK) {
          alert(status);
        }
        if (status == google.maps.GeocoderStatus.OK) {
          callback(results[0].formatted_address)
        }
      });
    }
  
    function addMarkers(position, map) {
      markers.forEach((marker)=>{
        marker.setMap(null);
      })
      markers = [];
      markers.push(
        new google.maps.Marker({
          position,
          map
        })
      )  
    }

    
    function districts(districtId) {
      $.ajax({
        url: `${baseUrl}/back-end/api/location/district.php`,
        method: "GET",
        success: function (res) {
          console.log({ 'districts': res })
          if (res.status == '200') {
            const result = res.data;
            let district = '';
            result.forEach(e => {
              district += `<option value="${e.id}"> ${e.name} </option>`;
            });
            $('#district').html(district).selectpicker('refresh');
            $('#district').selectpicker('val', districtId);
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }
    
    $(document).on('click', '#speciesAdditionEdit', function(e){
        e.preventDefault();
        const id = $(this).attr('data_id');
        speciesAdditionEdit(id);
        $('#speciesAdditionUpdateForm').attr('data-id', id);
        $('#speciesAdditionUpdateModal').modal('show');
    })

    function speciesAdditionEdit(id){
        $.ajax({
            url: `${baseUrl}/back-end/api/species-addition/findOne.php?id=${id}`,
            method: "GET",
            success: function (res) {
                console.log({ "speciesAdditionEdit": res });
                if (res.status == '200') {
                    let result = res.data[0];
                    fetchGroup({ "key": "endLevel", "value": "true", 'groupId':result.groupId});
                    fetchSpecies({ 'key': 'groupId', 'value': result.groupId, 'spId':result.spId});
                    $('#commonName').val(result.commonName);
                    $('#dateTime').val(result.collectionDate);
                    $('#locality').val(result.locality);
                    districts(result.district);
                    $('#gpsCoordination').val(result.coordination);
                    let info = {}
                    info.coordination = result.coordination;
                    info.locality = result.locality;
                    info.district = result.district;
                    info.collectionDate = result.collectionDate;
                    viewMap(info);

                    
                    $('#localName').val(result.localName);
                    $('#notes').val(result.notes);
                    $('#description').val(result.description);
                    $('#habitat').val(result.habitat);
                    $('#habitat').val(result.habitat);
                    $('#biology').val(result.biology);
                    $('#reference').val(result.reference);
                    
                    let html = '<div>';
                    result.photos.forEach(photo => {
                      html += `<span><a href="#" class="chipsBtn"><i data-feather="x"></i></a><img dataId="${photo.id}" src="${baseUrl}/uploads/${photo.name}"></span>`;
                    })
                    html += '</div>';
                    $('#prevImagePreview').html(html);
                    feather.replace();
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        })
    }
        
    function fetchGroup(data) {
      $.ajax({
        url: `${baseUrl}/back-end/api/group/filter.php`,
        method: "POST",
        data: JSON.stringify(data),
        success: function (res) {
          console.log({ 'fetchGroup': res })
          if (res.status == '200') {
            let option = ''
            let result = res.data
            result.forEach(item => {
              option += `<option value="${item.id}">${item.hierarchyName.join(' > ')}</option>`;
            });

            $('#taxonGroup').html(option).selectpicker('refresh'); 
            if(data.groupId){
              $('#taxonGroup').selectpicker('val', data.groupId); 
            }           
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }
    
    function fetchSpecies(data) {
        $.ajax({
            url:  `${baseUrl}/back-end/api/species/filter.php`,
            method: "POST",
            data: JSON.stringify(data),
            success: function (res) {
            console.log({ "fetchSpecies": res });
            if (res.status == '200') {
                let result = res.data;
                let html = '';
                result.forEach((e) => {
                    html += `<option value="${e.id}">${e.spScName}</option>`
                })
                $('#scientificName').html(html).selectpicker('refresh')
                if(data.spId){
                    $('#scientificName').selectpicker('val', data.spId);
                }
            }else{
                $('#scientificName').html('').selectpicker('refresh')
            }
            },
            error: function (e) {
              console.log(e.responseText);
            }
        });
    }

    $(document).on('change', '#taxonGroup', function(e){
      const id = $(this).val();
      fetchSpecies({ 'key': 'groupId', 'value': id});
    })

    $(document).on('change', '#scientificName', function (e) {
      const id = $(this).val()
      commonNameFetch({ 'key': 'id', 'value': id })
    })
    function commonNameFetch(data) {
      $.ajax({
        url: `${baseUrl}/back-end/api/species/filter.php`,
        method: "POST",
        data: JSON.stringify(data),
        success: function (res) {
          console.log({ "commonNameFetch": res });
          if (res.status == '200') {
            let result = res.data[0];
            $('#commonName').val(result.spEngName)
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }

    $(document).on('change', '#photos', function(e){
      let photos = e.target.files
      if(photos.length){
        let photo = '<div>';
        for (var i = 0; i < photos.length; i++) {
          photo += `<img src="${URL.createObjectURL(photos[i])}" title="${photos[i].name}">`
        }
        photo += '</div>';
        $('#imagePreview').html(photo);
      }else{
        $('#imagePreview').html('');
      }
    })

    var photos = [];
    $(document).on('click', '.chipsBtn', function(e){
      e.preventDefault();
      $(this).parents('span').remove();
      photos.push($(this).parents('span').children('img').attr('dataId'));
      $('#removePhotos').val(photos.join(','));
    });
    
    $(document).on('submit', '#speciesAdditionUpdateForm', function (e) {
      e.preventDefault();
      let formData = new FormData($(this)[0]);
      const id = $(this).attr('data-id');
      const prevPhotos = $( "#prevImagePreview span img" ).map(function() {
        return $(this).attr('dataId');
      }).get().join(',');
      formData.set('prevPhotos', prevPhotos);
      if(confirm('Alert! Are you sure to update?')){
        update(id, formData)
      }   
    })

    function update(id, data) {
      $.ajax({
        url: `${baseUrl}/back-end/api/species-addition/updateAuth.php?id=${id}`,
        method: "POST",
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $('.preloader').show();
        },
        complete: function () {
          $('.preloader').hide();
        },
        success: function (res) {
          console.log({ 'update': res });
          if(res.status == '200'){
            $('#speciesAdditionUpdateForm')[0].reset();
            contributionsList.ajax.reload();
            $('#imagePreview').html('');
            $('#speciesAdditionUpdateModal').modal('hide');
            $('#alert').addClass('show').html(`<div class="alert-body text-success"> <div> <strong> Success! </strong> ${res.msg} </div> <a href="">OK</a></div>`);
          }else{
            $('#alert').addClass('show').html(`<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${res.msg} </div> <a href="">OK</a></div>`);
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }

    
  
  })