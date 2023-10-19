
console.log('contribute.js')

$(function () {

  var script = document.createElement('script');
  script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB-tOlvcG_X2W_jF_FlU_vGPIRNWtmKOz4&callback=initMap&libraries=places';
  script.async = true;

  let markers = [];

  function initMap() {
    const mapSelector = document.getElementById('map');
    mapSelector.style.height = '305px';

    const map = new google.maps.Map(mapSelector, {
      center: new google.maps.LatLng(23.813908352674524, 90.41929991938888),
      zoom: 13,
      mapTypeId: google.maps.MapTypeId.HYBRID,
      controlSize: 24,
    });

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
      console.log('Searched')
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
  }

  window.initMap = initMap;

  document.head.appendChild(script);

  
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

  fetchGroup({ "key": "endLevel", "value": "true"});
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

  $(document).on('change', '#taxonGroup', function (e) {
    const id = $(this).val();
    fetchSpecies({ 'key': 'groupId', 'value': id });
  })
  
  function fetchSpecies(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/species/filter.php",
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
        }else{
          $('#scientificName').html('').selectpicker('refresh')
        }
      },
      error: function (e) {
        console.log(e.responseText);
      }
    });
  }

  $(document).on('change', '#scientificName', function (e) {
    let id = $(this).val()
    commonNameFetch({ 'key': 'id', 'value': id })
  })
  function commonNameFetch(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/species/filter.php",
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

  districts();
  function districts() {
    $.ajax({
      url: baseUrl + "/back-end/api/location/district.php",
      method: "GET",
      success: function (res) {
        console.log({ 'districts': res })
        if (res.status == '200') {
          const result = res.data;
          let district = '';
          result.forEach(e => {
            district += `<option value="${e.id}"> ${e.name} </option>`;
          });
          $("#district").html(district).selectpicker('refresh');
        }
      },
      error: function (e) {
        console.log(e.responseText);
      }
    });
  }


  $('#photos').on('change', function (e) {
    let photos = e.target.files
    let photo = '<div>';
    for (var i = 0; i < photos.length; i++) {
      photo += `<img src="${URL.createObjectURL(photos[i])}" title="${photos[i].name}">`
    }
    photo += '</div>';
    $('#imagePreview').html(photo);
  })

  $(document).on('click', '#typeToggleBtn', function (e) {
    e.preventDefault()    
    $('#commonName').val('')
    $('#commonName').attr('readonly') ? $('#commonName').removeAttr('readonly') : $('#commonName').attr('readonly', 'readonly')
    if ($(this).attr('type') == 'exist') {
      $('.scientificNameArea').html(`<input class="form-control" id="scientificName" name="scientificName">`)
      $(this).text('Contribute additional information').attr('type', 'new')
    } else if ($(this).attr('type') == 'new') {
      $('.scientificNameArea').html(`<select name="scientificName" id="scientificName" class="form-control selectpicker" data-live-search="true" data-size="5" title=" ">  </select>`)
      $('.selectpicker').selectpicker('refresh')
      $(this).text('Search then If not exist, Add as a new').attr('type', 'exist')
    }
  })

  $(document).on('submit', '#contributeForm', function (e) {
    e.preventDefault()
    let formData = new FormData($(this)[0])
    formData.set('type', $('#typeToggleBtn').attr('type'));
    if($('#terms').is(':checked')){
      formData.set('terms', 'true');
    }else{
      formData.set('terms', '');
    }
    create(formData)
  })

  function create(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/species-addition/create.php`,
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
        console.log({ 'create': res })
        if(res.status == '200'){
          $('#contributeForm')[0].reset();
          $('.selectpicker').selectpicker('refresh');
          $('#imagePreview').remove();
          $('#alert').addClass('show').html(`<div class="alert-body text-success"> <div> <strong> Success! </strong> ${res.msg} </div> <a href="">OK</a> </div>`);
        }else{
          $('#alert').addClass('show').html(`<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${res.msg} </div> <a href="">OK</a> </div>`);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      }
    });
  }

  
})