$(function(){

    console.log('faq.js');
    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');
    
    var data = { "key": "postType", "value": "faq", "ordering":'ASC' };
    getFaq(data);
    
    function getFaq(data) {
    $.ajax({
        url: baseUrl + "/back-end/api/post/filter.php",
        method: "POST",
        data: JSON.stringify(data),
        success: function (res) {
            //console.log({"Faqs":res});
            if (res.status == '200') {
                var result = res.data;
                let html = '';
                let i = 0;
                result.forEach(item => {
                    i++;  
                    let remove = item.sn == 1 ? '' : 'collapsed';
                    let add = item.sn == 1 ? 'show' : '';
                    html += '<div class="card shadow-sm"> <a href="#" class="btn-link '+remove+'" data-toggle="collapse" data-target="#collapse'+i+'"> '+item.title+' <i data-feather="chevron-down"></i> </a> <div id="collapse'+i+'" class="collapse '+add+'" data-parent="#accordion"> <div class="card-body"> '+item.content+' </div> </div> </div>';
                })
                $(".faq-area").html('<div id="accordion">'+html+'</div>');
                feather.replace();
            }else if(res.status == '204'){
                $(".faq-area").html('<h6 class="text-center">কোন তথ্য পাওয়া যায়নি।</h6>');
            }
        },
        error: function (e) {
        console.log(e.responseText);
        }
    });
    }

})