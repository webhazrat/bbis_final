$(function(){

    console.log('single.js')
    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');

    let titleSlug = $("#getTitle").attr('slug');
    getTitle(titleSlug);
    function getTitle(slug) {
        $.ajax({
            url: baseUrl + "/back-end/api/post/getTitle.php?slug=" + slug,
            method: "GET",
            success: function (res) {
                if (res.status == '200') {
                    $("#getTitle").text(res.data[0].title);
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        })
    }

    let slug = $("#singlePhpAjax").attr('slug');
    let data = { "key": "slug", "value": slug };
    fetchSingle(data);

    function fetchSingle(data) {
        $.ajax({
            url: baseUrl + "/back-end/api/post/filter.php",
            method: "POST",
            data: JSON.stringify(data),
            success: function (res) {
                console.log({'single':res})
                if (res.status == '200') {
                    var result = res.data[0];
                    let html = '';
                    if (result.postType == 'post' || result.postType == 'slider') {
                        html += '<div class="img-responsive shadow"> <img src="uploads/' + result.mediaName + '" alt=""> </div> <div class="content shadow-sm p-4"> <h6>' + result.title + '</h6> <ul class="post-info mt-3 mb-3"> <li><i data-feather="calendar"></i> ' + result.createdMod + '</li> <li><i data-feather="user"></i>' + result.authorName + '</li><li>  <div class="dropdown"> <a href="#" data-toggle="dropdown"> <i data-feather="share-2"></i> Share </a> <div class="dropdown-menu dropdown-menu-right shadow"> <a href="https://www.facebook.com/sharer/sharer.php?u=' + baseUrl + '/' + result.slug + '" target="_blank">Facebook</a> <a href="https://twitter.com/intent/tweet?url=' + baseUrl + '/' + result.slug + '" target="_blank">Twitter</a> <a href="https://www.linkedin.com/sharing/share-offsite/?url=' + baseUrl + '/' + result.slug + '" target="_blank">Linkedin</a> </div></div></li> </ul> ' + result.content + '</div>';
                    } else {
                        html += '<div class="bg-white shadow-sm p-4"> <h6>' + result.title + '</h6> <ul class="post-info mt-3 mb-3 notranslate"> <li><i data-feather="calendar"></i> ' + result.createdMod + '</li> <li><i data-feather="user"></i>' + result.authorName + '</li> </ul> ' + result.content + '</div>';
                    }
                    $("#singlePhpAjax").html(html);
                    feather.replace();
                }
            },
            error: function (e) {
                console.log(e.responseText);
            }
        });
    }
})