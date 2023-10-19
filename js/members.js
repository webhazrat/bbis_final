
$(function(){
    console.log('members.js');
    const baseUrl = $('.navbar-brand').attr('href');

    const memberAll = $('#memberAll').DataTable({
        'processing': true,
        'serverSide': true,
        "ajax": {
            url: baseUrl+"/back-end/api/user/filter.php",
            type: "POST",
            data: function(d) {
                d.key = 'all';
                d.value = '1';
                d.type = 'dataTable';
                return JSON.stringify(d);
            }
        },
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "columns": [
            { "data": "sn" },
            { "data": "mId" },
            { "data": "namePhoto" },
            { "data": "gender" },
            { "data": "phoneAction" },
            { "data": "address" },
            { "data": "bloodGroup" },
            { "data": "authorName" },
            { "data": "role" },
            { "data": "createdAt" },
            { "data": "statusName" },
            { "data": "action" }
        ],
        "columnDefs": [
            { "targets": [11], 'orderable': false, 'className': 'text-center' }
        ],
        "order": [],
        "drawCallback": function (settings) {
            feather.replace();
        }
    });
    
    $(document).on("click", "#memberStatus", function (e) {
        e.preventDefault();
        const id = $(this).attr("data_id");
        $("#memberStatusBtn").attr('data_id', id);
        findAllRole();
        findOneForStatus(id);
        $("#memberStatusModal").modal('show');
    });

    function findAllRole(){
		$.ajax({
            url : baseUrl+"/back-end/api/role/findAll.php",
            method : "GET",
            success:function(res){
                if(res.status == '200'){
                    const result = res.data;
                    let role = '';
                    result.forEach(e => {
                        role += "<option value='" + e.id + "'>"+ e.role +"</option>"; 
                    });
                    $("#role").html(role).selectpicker('refresh');
                }
            },
            error:function(error){
                console.log(error.responseText);
            }
        });
	}

    function findOneForStatus(id){
        $.ajax({
            url: baseUrl+"/back-end/api/user/findOne.php?id=" + id,
            method: "GET",
            success: function(res){
                if (res.status == '200') {
                    const result = res.data[0];  
                    let roles = result.role.split(',');
                    setTimeout(()=>{
                        $('#role').selectpicker('val', roles);
                        $('#status').selectpicker('val', result.status);
                    }, 50)
                    if(roles.includes('3')){
                        showManageBg();
                        $('#manageBg').selectpicker('val', result.manageBg);
                    }else{
                        $(".manageBg").html('');
                    }
                }
            },
            error:function(error){
                console.log(error.responseText);
            }
        });
    }

    $(document).on("change", "#role", function(){
        let roles = $(this).val();
        if(roles.includes('3')){
            if($("#manageBg").length == 0){
                showManageBg();
            }
        }else{
            $('.manageBg').html('');
        }
    })

    function showManageBg(){
        $('.manageBg').html(`<div class="form-group">
        <label for="manageBg">Manage Blood</label>
        <select name="manageBg" id="manageBg" class="form-control selectpicker" title=" "><option value="A">Group A</option><option value="B">Group B</option><option value="AB">Group AB</option><option value="O">Group O</option><option value="All">Group All</option></select> </div>`);
        $(".selectpicker").selectpicker('refresh');
    }

    $(document).on("click", "#memberStatusBtn", function (e) {
        e.preventDefault();
        const id = $(this).attr('data_id');
        const role = $("#role").val().toString();
        const manageBg = $("#manageBg").val();
        const status = $("#status").val();
        updateAuth(id, {role, manageBg, status});
    });
    function updateAuth(id, data) {
        $.ajax({
            url: baseUrl+"/back-end/api/user/updateAuth.php?id="+id,
            method: "PUT",
            data: JSON.stringify(data),
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                if (res.status == '200') {
                    memberAll.ajax.reload();
                    $("#memberStatusModal").modal('hide');
                } else{
                    alert("Sorry! "+res.msg);
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }

    $(document).on("click", "#memberEdit", function(e){
        e.preventDefault();
        const id = $(this).attr('data_id');
        $("#member_id").val(id);
        findOneEdit(id);
        $("#memberUpdateModal").modal('show');
    });
    function findOneEdit(id) {
        $.ajax({
            url: baseUrl+"/back-end/api/user/findOne.php?id=" + id,
            method: "GET",
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                console.log({'Edit Date':res});
                if (res.status == '200') {
                    var result = res.data[0];
                    $("#name").val(result.name);
                    $("#gender").selectpicker('val', result.gender);
                    $("#dob").val(result.dob);
                    $("#bloodGroup").selectpicker('val', result.bloodGroup);
                    $("#weight").val(result.weight);
                    $("#occupation").val(result.occupation);
                    $("#institute").val(result.institute);

                    $('#currentDistrict').selectpicker('val', result.currentDistrict);
                    
                    filterCurrentArea(result.currentDistrict, result.currentArea);
                    filterCurrentZone(result.currentArea, result.currentZone);

                    $('#homeDistrict').selectpicker('val', result.homeDistrict);
                    filterPermanentArea(result.homeDistrict, result.homeArea);
                    filterPermanentZone(result.homeArea, result.homeZone);
                    
                    result.bloodDonor == 1 ? $('#bloodDonor:checkbox').prop('checked', true) : $('#bloodDonor:checkbox').prop('checked', false);
                    result.volunteer == 1 ? $('#volunteer:checkbox').prop('checked', true) : $('#volunteer:checkbox').prop('checked', false);
                }
            }
        });
    }
    
    function filterCurrentArea(id, areaId) {
        $.ajax({
            url: baseUrl + "/back-end/api/location/area.php?districtId=" + id,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    const result = res.data;
                    let area = '';
                    result.forEach(e => {
                        area += "<option value='" + e.id + "'>" + e.name + "</option>";
                    });
                    $("#currentArea").html(area).selectpicker('refresh');
                    setTimeout(function () {
                        $('#currentArea').selectpicker('val', areaId);
                    }, 10);
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }
    function filterCurrentZone(id, zoneId) {
        $.ajax({
            url: baseUrl + "/back-end/api/location/zone.php?areaId=" + id,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    const result = res.data;
                    let zone = '';
                    result.forEach(e => {
                        zone += "<option value='" + e.id + "'>" + e.name + "</option>";
                    });
                    $("#currentZone").html(zone).selectpicker('refresh');
                    setTimeout(function () {
                        $('#currentZone').selectpicker('val', zoneId);
                    }, 10);
                } else {
                    $("#currentZone").html('').selectpicker('refresh');
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }

    function filterPermanentArea(id, areaId) {
        $.ajax({
            url: baseUrl + "/back-end/api/location/area.php?districtId=" + id,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    const result = res.data;
                    let area = '';
                    result.forEach(e => {
                        area += "<option value='" + e.id + "'>" + e.name + "</option>";
                    });
                    $("#homeArea").html(area).selectpicker('refresh');
                    setTimeout(function () {
                        $('#homeArea').selectpicker('val', areaId);
                    }, 10);
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }
    function filterPermanentZone(id, zoneId) {
        $.ajax({
            url: baseUrl + "/back-end/api/location/zone.php?areaId=" + id,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    const result = res.data;
                    let zone = '';
                    result.forEach(e => {
                        zone += "<option value='" + e.id + "'>" + e.name + "</option>";
                    });
                    $("#homeZone").html(zone).selectpicker('refresh');
                    setTimeout(function () {
                        $('#homeZone').selectpicker('val', zoneId);
                    }, 10);
                } else {
                    $("#homeZone").html('').selectpicker('refresh');
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }

    $(document).on("click", "#updateMember", function (e) {
        e.preventDefault();
        var id = $("#member_id").val();
        var name = $("#mu_name").val();
        var gender = $("#mu_gender").val();
        var dob = $("#mu_dob").val();
        var blood_group = $("#mu_bloodGroup").val();
        var weight = $("#mu_weight").val();
        var occupation = $("#mu_occupation").val();
        var institute = $("#mu_institute").val();
        var current_district = $("#u_current_district").val();
        var c_area = $("#u_c_area").val();
        var c_zone = $("#u_c_zone").val();
        var home_district = $("#u_home_district").val();
        var area = $("#u_m_area").val();
        var zone = $("#u_m_zone").val();
        var blood_donor = $("#mu_bloodDonor").is(':checked') ? 1 : '';
        var volunteer = $("#mu_volunteer").is(':checked') ? 1 : '';
        if (name == '') {
            alert('Sorry! Name required');
        } else if (gender == '') {
            alert('Sorry! Gender required');
        }else if(dob == ''){
            alert('Sorry! Date of birth required');
        }else if(blood_group == ''){
            alert('Sorry! Blood group required');
        }else if (occupation == '') {
            alert("Sorry! Occupation required");
        }else if(current_district == ''){
            alert("Sorry! Current district required");
        }else if(home_district == ''){
            alert("Sorry! Home district required");
        }else if(area == ''){
            alert("Sorry! Area required");
        }else if(zone == ''){
            alert("Sorry! Zone required");
        }else{
            var data = {name, dob, gender, blood_group, weight, occupation, institute, current_district, c_area, c_zone, home_district, area, zone, blood_donor, volunteer };
            update(id, data);
        }
    });

    function update(id, data) {
        $.ajax({
            url: baseUrl+"/back-end/api/user/update.php?id="+id,
            method: "PUT",
            data: JSON.stringify(data),
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                if(res.status == '200') {
                    $("#memberUpdateModal").modal('hide');
                    memberAll.ajax.reload();
                }else{
                    alert(res.msg);
                }
            },
            error: function (error) {
                console.log(error.responseText);
            }
        });
    }
    
    $(document).on("click", "#memberView", function (e) {
        e.preventDefault();
        var id = $(this).attr("data_id");
        findOneView(id);
        filterBloodDonated({"key":"donor_id", "value":id});
        filterAddedMember({"key":"author", "value":id});
        filterDonatedFund({"key":"donor_id", "value":id});
        $("#view_member_modal").modal('show');
    });

    function findOneView(id) {
        $.ajax({
            url: baseUrl+"/back-end/api/user/findOne.php?id=" + id,
            method: "GET",
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                if (res.status == '200') {
                    var result = res.data[0];
                    
                    if (result.photo == '') {
                        $("#uploaded_image").html('<img src="' + baseUrl + '/admin/assets/images/profile.png" alt="">');
                    } else {
                        $("#uploaded_image").html('<img src="' + baseUrl + '/uploads/' + result.photo + '" alt="">');
                    }
                    $("#profile_name").html("<span>" + result.name + " </span>");

                    var profile_info_list = "";
                    profile_info_list += '<li id="m_id" class="btn btn-soft-primary mb-2 text-center">ID NO: ' + result.m_id + '</li>';
                    profile_info_list += '<li><i data-feather="calendar"></i>Joined ' + result.joined + '</li>';
                    profile_info_list += '<li><i data-feather="droplet"></i><strong>Blood Group :&nbsp; </strong> <span id="mBG">' + result.blood_group + '</span>(ve)</li>';
                    profile_info_list += '<li> <i data-feather="smartphone"></i>+88' + result.phone + '</li>';

                    $(".profile_info_list").html(profile_info_list);

                    var personal_info1 = '';
                    personal_info1 += '<li><strong>Date of Birth :</strong> ' + result.dob + '</li>';
                    personal_info1 += '<li><strong>Gender :</strong> ' + result.gender + ',  <strong>Weight :</strong> ' + result.weight + ' KG </li>';
                    
                    var personal_info2 = '';
                    personal_info2 += '<li><strong>Occupation :</strong> ' + result.occupation + '</li>';
                    personal_info2 += result.institute !== '' ? '<li><strong>Institute :</strong> ' + result.institute + '</li>' : '';
                    var interested = '';
                    if (result.blood_donor == 1) {
                        interested += 'Blood Donar';
                    }
                    if (result.blood_donor == 1 && result.volunteer == 1) {
                        interested += ', ';
                    }
                    if (result.volunteer == 1) {
                        interested += 'Volunteer';
                    }
                    personal_info2 += '<li><strong>Interested :</strong> ' + interested + '</li>';

                    var personal_info3 = '';
                    personal_info3 += '<li><strong>Present Address :</strong> ' + result.cu_district + ', '+result.cu_area+', '+result.cu_zone+'</li>';
                    personal_info3 += '<li><strong>Permanent Address :</strong> ' + result.p_district + ', '+result.p_area+', '+result.p_zone+'</li>';
                    $("#personal_info1").html(personal_info1);
                    $("#personal_info2").html(personal_info2);
                    $("#personal_info3").html(personal_info3);
                    feather.replace();
                }
            }
        });
    }

    function filterBloodDonated(data) {
        $.ajax({
            url: baseUrl + "/back-end/api/blood-request/countByFilter.php",
            method: "POST",
            data: JSON.stringify(data),
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                if (res.status == '200' || res.status == '204') {
                    let result = res.data;
                    let bloodDonate = result.filter((value) => {
                        return value.status == '1' || value.status == '5';
                    })
                    $(".donated_blood").html('<strong>' + bloodDonate.length + '</strong><p>Donated Blood</p>');
                }else{
                    console.log(res.msg);
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }

    function filterAddedMember(data) {
        $.ajax({
            url: baseUrl + "/back-end/api/user/countByFilter.php",
            method: "POST",
            data: JSON.stringify(data),
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                if (res.status == '200' || res.status == '204') {
                    var result = res.data;
                    const members = result.filter((value) => {
                        return value.status == '5';
                    })
                    $(".added_member").html('<strong>' + members.length + '</strong><p>Added Member</p>');
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }

    function filterDonatedFund(data) {
        $.ajax({
            url: baseUrl + "/back-end/api/collection/countByFilter.php",
            method: "POST",
            data: JSON.stringify(data),
            beforeSend: function () {
                $('.preloader').show();
            },
            complete: function () {
                $('.preloader').hide();
            },
            success: function (res) {
                if (res.status == '200' || res.status == '204') {
                    var result = res.data;
                    const funds = result.filter((value) => {
                        return value.status == '5';
                    })
                    let amount = 0;
                    funds.forEach((value) => {
                        amount += Number(value.amount);
                    })
                    $(".donated_fund").html('<strong>' + amount + '</strong><p>Donated Fund</p>');
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }



});


