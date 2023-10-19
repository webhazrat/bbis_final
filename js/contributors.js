console.log('contributors.js')

$(function () {

    $(document).on("click", "#peoplePagination a", function(e){
        e.preventDefault();
        const page = $(this).attr('href');
        window.history.replaceState(null, null, page);
        pageReq();
    });
  
    pageReq();
    function pageReq(){
        const params = new window.URLSearchParams(window.location.search);
        const page = params.get('p') ? params.get('p') : '1';
        let per_page = $("#contributorsAjax").attr('per_page');
        filterContributors({'type':'contributors', per_page, page});
    }
    function filterContributors(data){
        $.ajax({
            url: `${baseUrl}/back-end/api/species-addition/filter.php`,
            method: 'POST',
            data: JSON.stringify(data),
            success: function(res){
                console.log({'filterContributors':res});
                if(res.status == '200'){
                    const result = res.data;
                    let html = '';
                    result.forEach(item => {
                        html += `<div class="col-md-3" id="contributor${item.id}"></div>`;
                        contributor(item.author, item.id); 
                    });                    
                    $('#contributorsAjax').html(html);
                    $("#peoplePagination").html(res.pagination);
                    feather.replace();
                }else if(res.status == '204'){
                    $("#contributorsAjax").html('<div class="col-md-12 text-center">Nothing found</div>');
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        })
    }

    function contributor(id, selector){
        $.ajax({
            url: `${baseUrl}/back-end/api/user/filter.php`,
            method: 'POST',
            data: JSON.stringify({'key':'id', 'value':id}),
            success: function(res){
                console.log({'contributor':res})
                if(res.status == '200'){
                    const result = res.data;
                    html = '';
                    result.forEach(person => {
                    let photo = person.photo ? `<img src="${baseUrl}/uploads/${person.photo}" alt="${person.photo}">` : `<img src="${baseUrl}/assets/images/profile.png" alt="profile">`;
                    let institution = person.profession ? `<strong>${person.profession}</strong>, ${person.institution}` : '';
                       html += `<div class="single-member text-center shadow-sm p-4">
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
                            </div>`;
                    })
                    $(`#contributor${selector}`).html(html);
                }
            },
            error: function(error){
                console.log(error.responseText);
            }
        })
    }
    

})