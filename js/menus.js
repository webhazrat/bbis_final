$(function () {
  console.log("menus.js");
  const element = document.querySelector(".navbar-brand");
  const baseUrl = element.getAttribute("href");

  // pages fetch for menu
  filterPages({ key: "postType", value: "page" });
  function filterPages(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/post/filter.php",
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let pages = "";
          let i = 0;
          result.forEach((e) => {
            i++;
            pages +=
              '<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input pagesCheck" id="check' +
              i +
              '" value="' +
              e.slug +
              '"><label class="custom-control-label" for="check' +
              i +
              '">' +
              e.title +
              "</label></div>";
          });
          $(".pagesForMenu").html(pages);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  // Create menu
  $(document).on("click", "#createNewMenu", function (e) {
    e.preventDefault();
    const name = $("#newMenu").val();
    create({ name });
  });
  function create(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/menu/create.php",
      method: "POST",
      data: JSON.stringify(data),
      beforeSend: function () {
        $(".prealoder").show();
      },
      complete: function () {
        $(".prealoder").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          $("#newMenu").val("");
          findAll();
          alert("Success! " + res.msg);
        } else {
          alert("Sorry! " + res.msg);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  findAll();
  function findAll() {
    $.ajax({
      url: baseUrl + "/back-end/api/menu/findAll.php",
      method: "GET",
      beforeSend: function () {
        $(".prealoder").show();
      },
      complete: function () {
        $(".prealoder").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let option = "";
          let selected = "";
          let i = 0;
          result.forEach((e) => {
            i++;
            selected += i == 1 ? e.id : "";
            option += '<option value="' + e.id + '">' + e.name + "</option>";
          });
          $("#menuSelect").html(option).selectpicker("refresh");
          $("#menuSelect").selectpicker("val", selected);
          filter({ menuId: selected });
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
  function filter(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/menu/filter.php",
      method: "POST",
      data: JSON.stringify(data),
      beforeSend: function () {
        $(".prealoder").show();
      },
      complete: function () {
        $(".prealoder").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          $("#nestable").html(res.data);
          feather.replace();
        } else {
          $("#nestable").html("");
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
  $(document).on("change", "#menuSelect", function () {
    let menuId = $(this).val();
    filter({ menuId });
  });

  // Add page to menu
  $(document).on("click", "#addPage", function (e) {
    e.preventDefault();
    const menuId = $("#menuSelect").children("option:selected").val();
    const pages = $(".pagesCheck:checked")
      .map(function (_, el) {
        return $(el).val() + $(el).text();
      })
      .get();
    if (menuId == "") {
      alert("Sorry! Menu select required");
    } else if (pages == "") {
      alert("Sorry! Page select required");
    } else {
      const data = { menuId, slug: pages, label: "" };
      createMenuItem(data);
    }
  });
  $(document).on("click", "#addLinkToMenu", function (e) {
    e.preventDefault();
    const menuId = $("#menuSelect").children("option:selected").val();
    const linkUrl = $("#linkUrl").val();
    const linkText = $("#linkText").val();
    if (linkUrl !== "" && linkText !== "") {
      var data = { menuId, slug: linkUrl, label: linkText };
      createMenuItem(data);
    } else {
      alert("Sorry! URL and text required");
    }
  });
  function createMenuItem(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/menu/createMenuItem.php",
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          $("#pageForMenuForm")[0].reset();
          const menuId = $("#menuSelect").children("option:selected").val();
          filter({ menuId });
        } else {
          console.log("Sorry! " + res.msg);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }

  $(document).on("change", ".dd", function () {
    sortMenuItem();
  });
  function sortMenuItem() {
    $.ajax({
      url: baseUrl + "/back-end/api/menu/sortMenuItem.php",
      method: "POST",
      data: JSON.stringify($("#nestable-output").val()),
      cache: false,
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        console.log(res);
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $(document).on("click", "#menuItemEdit", function (e) {
    e.preventDefault();
    $("#data_id").val($(this).attr("data_id"));
    $("#data_label").val($(this).attr("data_label"));
    $("#menuItemEditModal").modal("show");
  });
  $(document).on("click", "#labelUpdate", function (e) {
    e.preventDefault();
    var id = $("#data_id").val();
    var data_label = $("#data_label").val();
    if (id !== "" && data_label !== "") {
      updateMenuItem(id, { label: data_label });
    } else {
      alert("Sorry! Lebel required");
    }
  });
  function updateMenuItem(id, data) {
    $.ajax({
      url: baseUrl + "/back-end/api/menu/updateMenuItem.php?id=" + id,
      method: "PUT",
      data: JSON.stringify(data),
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          $("#menuItemEditModal").modal("hide");
          const menuId = $("#menuSelect").children("option:selected").val();
          filter({ menuId });
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $(document).on("click", "#menuItemDel", function (e) {
    e.preventDefault();
    const id = $(this).attr("data_id");
    if (id !== "") {
      if (confirm("Delete permanently?")) {
        deleteMenuItem(id);
      }
    }
  });
  function deleteMenuItem(id) {
    $.ajax({
      url: baseUrl + "/back-end/api/menu/deleteMenuItem.php?id=" + id,
      method: "DELETE",
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          const menuId = $("#menuSelect").children("option:selected").val();
          filter({ menuId });
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
});
