$(function (e) {
  $(document).on("click", "#alert a", function (e) {
    e.preventDefault();
    $("#alert").removeClass("show").html("");
  });

  $(document).on("click", "#viewPassword", function () {
    var field = $(this).parents(".input-group").children(".field");
    var fieldType = field.attr("type");
    if (fieldType == "password") {
      field.attr("type", "text");
    } else {
      field.attr("type", "password");
    }
  });

  $(document).on("click", "#viewPassword2", function () {
    var field = $(".field2");
    var fieldType = field.attr("type");
    if (fieldType == "password") {
      field.attr("type", "text");
    } else {
      field.attr("type", "password");
    }
  });
});
