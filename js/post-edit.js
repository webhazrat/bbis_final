$(function () {
  console.log("post-edit.js");

  const element = document.querySelector(".navbar-brand");
  const baseUrl = element.getAttribute("href");

  $(document).on("keyup", "#postTitle", function () {
    $("#postSlug").val(convertToSlug($(this).val().trim()));
  });
  function convertToSlug(Text) {
    return Text.toLowerCase()
      .replace(/ /g, "-")
      .replace(/[^\w-]+/g, "");
  }

  const id = $("#postUpdate").attr("dataId");
  findOne(id);
  function findOne(id) {
    $.ajax({
      url: baseUrl + "/back-end/api/post/findOne.php?id=" + id,
      method: "GET",
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        console.log({ findOne: res });
        if (res.status == "200") {
          var result = res.data[0];
          $("#postTitle").val(result.title);
          $("#postSlug").val(result.slug);
          $("#postExcerpt").val(result.excerpt);
          if (result.mediaName !== null) {
            $("#featureImage").html(
              '<span><a href="#" class="chipsBtn"><i data-feather="x"></i></a><img dataId="' +
                result.image +
                '" src="../uploads/' +
                result.mediaName +
                '" alt=""></span>'
            );
          }
          $("#template").selectpicker("val", result.isTemplate);
          $("#isSpeciesGroup").selectpicker("val", result.isSpeciesGroup);
          $("#ordering").val(result.ordering);
          $(".summerNote").summernote("code", result.content);
          $("#postCategory").selectpicker("val", result.category);
          $("#status").selectpicker("val", result.status);
          feather.replace();
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }

  $(document).on("click", "#postUpdate", function (e) {
    e.preventDefault();
    const id = $(this).attr("dataId");
    const data = {};
    data.postType = $(this).attr("type");
    data.title = $("#postTitle").val();
    data.slug = $("#postSlug").val();
    data.content = $("#postContent").val();
    data.excerpt = $("#postExcerpt").val();
    data.image = $("#featureImage img").attr("dataId")
      ? $("#featureImage img").attr("dataId")
      : "";
    data.template = $("#template").val() ? $("#template").val() : "";
    data.category = $("#postCategory").val() ? $("#postCategory").val() : "";
    data.status = $("#status").val();
    data.ordering = $("#ordering").val() ? $("#ordering").val() : "";
    update(id, data);
  });
  function update(id, data) {
    $.ajax({
      url: baseUrl + "/back-end/api/post/update.php?id=" + id,
      method: "PUT",
      data: JSON.stringify(data),
      beforeSend: function () {
        $(".prealoder").show();
      },
      complete: function () {
        $(".prealoder").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          findOne(id);
          alert("Success! " + res.msg);
        } else {
          alert("Sorry! " + res.msg);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }
});
