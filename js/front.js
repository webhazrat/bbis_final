console.log("App Version 2.0 | front.js");
var element = document.querySelector(".navbar-brand");
var baseUrl = element.getAttribute("href");

$(function () {
  navMenus();
  async function navMenus() {
    try {
      let response = await fetch(baseUrl + "/back-end/api/menu/navMenus.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json;charset=utf-8",
        },
        body: JSON.stringify({ name: "Header Menu" }),
      });
      let result = await response.json();
      if (result.status == "200") {
        const navMenus = document.getElementById("navMenus");
        navMenus.innerHTML = result.data;
        feather.replace();
      }
    } catch (error) {
      console.log(error);
    }
  }

  const path = window.location.href;
  setTimeout(function () {
    $(".navbar-nav a").each(function (idx) {
      if (this.href === path) {
        $(this).addClass("active");
        $(this)
          .parent("li")
          .parent("ul")
          .parent("li")
          .children("a")
          .addClass("active");
      }
    });
  }, 500);

  filterCustomize({ keys: ["siteLogo", "siteTitle"] });
  function filterCustomize(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/customize/filter.php",
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          let result = res.data;
          let siteLogo = result.find((item) => item.optionKey == "siteLogo");
          let logo = siteLogo.mediaName
            ? `<img src="${baseUrl + "/uploads/" + siteLogo.mediaName}" alt="${
                siteLogo.mediaName
              }">`
            : `<img src="${baseUrl + "/assets/images/logo.png"}" alt="logo">`;
          $(".navbar-brand").html(logo);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
});
