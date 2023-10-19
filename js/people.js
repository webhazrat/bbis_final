console.log('people.js')

$(function () {

    peopleType();
    function peopleType(){
        $.ajax({
            url: `${baseUrl}/back-end/api/people/filter.php`,
            method: 'POST',
            data: JSON.stringify({'key':'status', 'value':'6', 'ordering':'ASC'}),
            success: function(res){
                console.log({'peopleType':res})
                if(res.status == '200'){
                    const result = res.data;
                    html = `<div>`;
                    result.forEach(type => {
                        html += `<div class="people-title">
                            <h6><strong>${type.name}</strong></h6>
                        </div> <div id="people${type.id}" class="row"></div>`;
                        people(type.id);                        
                    })       
                    html += `</div>`;

                    $('#peopleAjax').html(html);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        })
    }

    function people(id){
        $.ajax({
            url: `${baseUrl}/back-end/api/user/filter.php`,
            method: 'POST',
            data: JSON.stringify({'key':'peopleType', 'value':id}),
            success: function(res){
                console.log({'people':res})
                if(res.status == '200'){
                    const result = res.data;
                    html = '';
                    result.forEach(person => {
                    let photo = person.photo ? `<img src="${baseUrl}/uploads/${person.photo}" alt="${person.photo}">` : `<img src="${baseUrl}/assets/images/profile.png" alt="profile">`;
                    let institution = person.profession ? `<strong>${person.profession}</strong>, ${person.institution}` : '';
                       html += `<div class="col-md-3">
                            <div class="single-member text-center shadow-sm p-4">
                                ${photo}
                                <div>
                                    <h6 style="font-size:15px"><a href="${baseUrl}/user/${person.userName}">${person.name}</a></h6>
                                    <ul class="mb-2">
                                        <li>${institution}</li>
                                    </ul>
                                    <div class="socials"> `;
                                    person.socials.forEach(social => {
                                        html += `<a href="${social.url}" target="_blank" class="badge badge-pill badge-light">${social.title}</a>`;
                                    })
                            html += `</div>
                                </div>
                            </div></div>`;
                    })
                    $(`#people${id}`).html(html);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        })
    }
    
})