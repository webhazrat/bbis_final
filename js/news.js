$(function () {

    console.log('news.js');
    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');
      
    $(document).on("click", "#newsPagination a", function(e){
      e.preventDefault();
      const page = $(this).attr('href');
      window.history.replaceState(null, null, page);
      newsReq();
    });
    newsReq();
    function newsReq(){
      const params = new window.URLSearchParams(window.location.search);
      const page = params.get('p');
      var per_page = $("#newsAjax").attr('per_page');
      var data = { "key": "postType", "value": "post", "operator":"AND", "key2":"category", "value2":"news", "per_page":per_page, "page":page};
      fetchNews(data);
    }
  
    function fetchNews(data) {
      $.ajax({
        url: baseUrl + "/back-end/api/post/filter.php",
        method: "POST",
        data: JSON.stringify(data),
        success: function(res){
          if (res.status == '200'){
            const result = res.data;
            let news = '';
            result.forEach(e => {
                let parsehtml = $.parseHTML(e.content);
                let str = $(parsehtml).text();
                var content = str.substring(0, 60) + '...';
                news += `<div class="col-md-3"><div class="single-news shadow-sm"><a href="`+e.slug+`"><div><img src="uploads/` + e.mediaName + `" alt=""><div class="body p-3"><span> <i data-feather="calendar"></i> ` + e.createdMod + `</span><h6>` + e.title + `</h6>` + content + `</div></div></a></div></div>`;
            })
            $("#newsAjax").html(news);
            $(".pagination").html(res.pagination);
            feather.replace();
          }else if(res.status == '204'){
            $("#newsAjax").html('<div class="col-md-12"><h6 class="text-center">কোন তথ্য পাওয়া যায়নি।</h6></div>');
          }
        },
        error: function (e) {
          console.log(e.responseText);
        }
      });
    }
    
  
  })