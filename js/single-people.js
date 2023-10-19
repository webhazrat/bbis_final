$(function(){

    console.log('single-people.js')
    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');

    user();
    function user(){
        $.ajax({
            url: `${baseUrl}/back-end/api/user/filter.php`,
            method: 'POST',
            data: JSON.stringify({'key':'userName', 'value':$('#singlePeople').attr('username')}),
            success: function(res){
                console.log({'user':res});
                if(res.status == '200'){
                    const result = res.data[0];
                   
                    let profilePhoto = '';
                    if (result.photo == '') {
                        profilePhoto += `<div id="uploaded_image">
                        <img src="${baseUrl}/assets/images/profile.png" alt=""> 
                        </div>`;
                    } else {
                        profilePhoto += `<div id="uploaded_image">
                        <img src="${baseUrl}/uploads/${result.photo}" alt=""> 
                        </div>`;
                    }

                    $("#profilePhoto").html(profilePhoto);
                    $("#profile_name").html("<span>" + result.name + " </span>");

                    var profile_info_list = "";
                    profile_info_list += `<li id="m_id" class="btn btn-soft-primary mb-2 text-center">ID NO: <span> PF${result.id}</span></li>`;
                    profile_info_list += '<li> <i data-feather="smartphone"></i>+88' + result.phone + '</li>';
                    profile_info_list += '<li> <i data-feather="mail"></i>' + result.email + '</li>';

                    $(".profile_info_list").html(profile_info_list);

                    let personal_info = '';
                    personal_info += '<li><strong>Date of Birth :</strong> ' + result.dob + ', <strong>Gender :</strong> ' + result.gender;
                    personal_info += '<li><strong>Profession :</strong> ' + result.profession + ', <strong>Institution :</strong> ' + result.institution + '</li>';
                    personal_info += '<li><strong>Address :</strong> ' + result.zoneName + ', '+result.areaName+', '+result.districtName+'</li>';
                    personal_info += '<li><strong>About :</strong> <br>' + result.about + '</li>';
                    $("#personal_info").html(personal_info);

                    let socials = '';
                    result.socials.forEach(social => {
                        socials += `<li><a href="${social.url}" target="_blank">${social.title}</a></li>`;
                    });
                    $('#social-links').html(socials);
                    feather.replace();               
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        })
    }

    
})