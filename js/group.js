$(function () {
  console.log("group.js");

  filterGroup({
    key: "slug",
    value: $("#singleGroupAjax").attr("slug"),
    limit: 1,
  });

  function filterGroup(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/group/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        console.log({ filterGroup: res });
        if (res.status == "200") {
          const result = res.data;
          $("#getTitle").text(result[0].hierarchyName.join(" > "));
          let html = "";
          result.forEach((item) => {
            html += `<h6>${result[0].hierarchyName.join(
              " > "
            )}</h6> <ul class="post-info mt-3 mb-3"> <li><i data-feather="calendar"></i>${
              item.createdAt
            }</li> <li><i data-feather="user"></i>${
              item.authorName
            }</li> </ul>${item.description}</div>`;
          });
          $("#singleGroupAjax").html(html);
          feather.replace();
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
});
