console.log("single.js");
$(function () {
  const element = document.querySelector(".navbar-brand");
  const baseUrl = element.getAttribute("href");

  let titleSlug = $("#getTitle").attr("slug");
  getTitle(titleSlug);
  function getTitle(slug) {
    $.ajax({
      url: baseUrl + "/back-end/api/post/getTitle.php?slug=" + slug,
      method: "GET",
      success: function (res) {
        if (res.status == "200") {
          $("#getTitle").text(res.data[0].title);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  let slug = $("#pagePhpAjax").attr("slug");
  let data = { key: "slug", value: slug };
  fetchPageContent(data);

  function fetchPageContent(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/post/filter.php",
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          var result = res.data[0];
          $("#pagePhpAjax").html(result.content);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
});
