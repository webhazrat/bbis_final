console.log('location.js');

$(function(){

    findDistrict();
    function findDistrict() {
        $.ajax({
            url: `${baseUrl}/back-end/api/location/district.php`,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    const result = res.data;
                    let district = '';
                    result.forEach(e => {
                        district += "<option value='" + e.id + "'>" + e.name + "</option>";
                    });
                    $("#district").html(district).selectpicker('refresh');
                }
            },
            error: function(e){
                console.log(e.responseText);
            }
        });
    }

    $(document).on("change", "#district", function() {
        const id = $(this).val();
        if(id){
            filterArea(id);
        }
    })
    function filterArea(id){
        $.ajax({
            url: `${baseUrl}/back-end/api/location/area.php?districtId=${id}`,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    const result = res.data;
                    let area = '';
                    result.forEach(e => {
                        area += "<option value='" + e.id + "'>" + e.name + "</option>";
                    });
                    $("#area").html(area).selectpicker('refresh');
                    filterCurrentZone();
                }
            },
            error: function(e){
                console.log(e.responseText);
            }
        });
    }
    
    $(document).on("change", "#area", function(){
        const id = $(this).val();
        if (id) {
            filterZone(id);
        }
    })
    function filterZone(id) {
        $.ajax({
            url: `${baseUrl}/back-end/api/location/zone.php?areaId=${id}`,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    const result = res.data;
                    let zone = '';
                    result.forEach(e => {
                        zone += "<option value='" + e.id + "'>" + e.name + "</option>";
                    });
                    $("#zone").html(zone).selectpicker('refresh');
                }else{
                    $("#zone").html('').selectpicker('refresh');
                }
            },
            error: function(e){
                console.log(e.responseText);
            }
        });
    }

})