$(function () {
  fetchSlider({ key: "postType", value: "slider", ordering: "ASC" });
  function fetchSlider(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/post/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let html = "";
          result.forEach((e) => {
            html += `<div class="slider-item" style='background-image:url("uploads/${e.mediaName}")'> </div>`;
          });
          $("#sliderAjax").html(html);
          $(".banner-item").owlCarousel({
            loop: true,
            margin: 30,
            items: 1,
            nav: true,
            autoplay: true,
            mouseDrag: true,
            dots: false,
            navText: [
              "<i data-feather='chevron-left'></i>",
              "<i data-feather='chevron-right'></i>",
            ],
          });
          feather.replace();
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  filter({ keys: ["sliderTitle", "sliderDescription"] });
  function filter(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/customize/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          let result = res.data;
          let sliderTitle = result.find(
            (item) => item.optionKey == "sliderTitle"
          );
          let sliderDescription = result.find(
            (item) => item.optionKey == "sliderDescription"
          );
          $("#sliderTitle").text(sliderTitle.optionValue);
          $("#sliderDescription").text(sliderDescription.optionValue);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $(document).on("keyup", "#searchSpecies", function () {
    speciesSearch({ key: $(this).val() });
  });

  function speciesSearch(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/species/search.php`,
      method: "POST",
      data: JSON.stringify(data),
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        if (res.status === "200" || res.status === "204") {
          let result = res.data;
          let html =
            '<div class="list-group-area shadow"><div class="list-group">';
          if (result.length > 0) {
            result.forEach((item) => {
              html += `<a href="species/${item.spCode}" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-2"><i>${item.spScName} </i> ${item.spScNameAuth}</h6>
                <small>${item.createdAt}</small>
              </div>
              <span><strong>Family:</strong> ${item.spFamily} </span>
              <p class="mb-2">${item.spEngName}</p>
              <small> <i data-feather="user"></i> ${item.authorName} </small>
            </a>`;
            });
          } else {
            html +=
              `<span class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-2">` +
              data.key +
              `</h6>
            </div>
            <p class="mb-0">Nothing found</p>
          </span>`;
          }
          html += `</div></div>`;
          $(".search-result").html(html);
        } else {
          $(".search-result").html("");
        }
        feather.replace();
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }

  // about page content fetch
  fetchPageContent({ key: "slug", value: "about" });

  function fetchPageContent(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/post/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          var result = res.data[0];
          let parsehtml = $.parseHTML(result.content);
          let str = $(parsehtml).text();
          var content = str.substring(0, 500) + "...";
          var html = `<div class="section-header mb-3">
          <span>Who we are</span>
          <h2>${result.title}</h2>
        </div>
        <div><p>${content}</p></div>
        <a href="${result.slug}" class="btn btn-success">Read More</a>`;
          $("#aboutPageContent").html(html);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  fetchMission({
    key: "postType",
    value: "mission",
    ordering: "ASC",
    limit: 4,
  });
  function fetchMission(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/post/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let html = "";
          let bgColor = ["#F9F2C6", "#E1F4F8", "#E4FCF3", "#FFEFE0"];
          result.forEach((e, i) => {
            let parsehtml = $.parseHTML(e.content);
            let str = $(parsehtml).text();
            var content = str.substring(0, 80) + "...";
            let bg = bgColor[i];
            html +=
              `<div class="col-md-6"> 
            <div class="single-about-type p-3" style="background-color:` +
              bg +
              `">
              <div class="icon mb-2">
                <img src="${baseUrl}/uploads/${e.mediaName}" alt="${e.mediaName}">
              </div>
              <div class="body">
                <h6><a href="${baseUrl}/${e.slug}">${e.title}</a></h6>
                <p>${content}</p>
              </div>
            </div>
          </div>`;
          });
          $("#missionAjax").html(html);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  filterGroup({ key: "endLevel", value: "true", ordering: "ASC", limit: "10" });
  function filterGroup(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/group/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let html = "";
          result.forEach((item) => {
            html += `<div class="single-group"><div><img class="shadow" src="uploads/${
              item.mediaName
            }"><div class="body"><div class="d-flex justify-content-between align-items-center mt-3 mb-2"><span><i data-feather="calendar"></i> ${
              item.createdAt
            } </span> <a href="species/${
              item.slug
            }"><span> <i data-feather="file-text"></i> ${
              item.spApprovedCount
            } Species</span></a> </div> <a href="group/${
              item.slug
            }"><h5> ${item.hierarchyName.join(
              " > "
            )} </h5></a> </div></div></div>`;
          });
          $("#groupAjax").html(html);
          $("#groupAjax").owlCarousel({
            margin: 30,
            nav: true,
            dots: false,
            navText: [
              "<i data-feather='chevron-left'></i>",
              "<i data-feather='chevron-right'></i>",
            ],
            responsive: {
              1000: {
                items: 4,
              },
              770: {
                items: 2,
              },
              0: {
                items: 1,
              },
            },
          });
          feather.replace();
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  fetchSpecies({ limit: 10, order: "DESC" });
  function fetchSpecies(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/species/filter.php`,
      method: "POST",
      async: false,
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          let result = res.data;
          let html = "";
          result.forEach((item) => {
            var image = "";
            var photoNum = 0;
            if (item.addition.length) {
              item.addition.forEach((add) => {
                if (add.photos.length) {
                  image = add.photos[0].name;
                }
                photoNum += add.photos.length;
              });
            }

            photoNum += item.spPhotos.length;
            if (image == "" && item.spPhotos.length) {
              image = item.spPhotos[0].name;
            }

            const photo = image
              ? `<img src="${baseUrl}/uploads/${image}">`
              : `<img src="${baseUrl}/assets/images/no-img.png" alt="no-img">`;

            html += `<div class="single-species"><a href="species/${
              item.spCode
            }"><div><div class="img"> ${photo} <span class="shadow-sm"><i data-feather="image"></i> ${photoNum} </span></div><div class="body"><span class="fact"> ${item.hierarchyName.join(
              " > "
            )} </span><h6> ${item.spEngName} </h6> <i> ${item.spScName} </i> ${
              item.spScNameAuth
            }</div><div class="sp-footer d-flex justify-content-between align-items-center"><span> <i data-feather="user"></i> ${
              item.authorName
            } </span> <span> <i data-feather="calendar"></i> ${
              item.createdAt
            } </span></div></div></a></div>`;
          });
          $("#speciesAjax").html(html);
          $("#speciesAjax").owlCarousel({
            margin: 30,
            nav: true,
            dots: false,
            navText: [
              "<i data-feather='chevron-left'></i>",
              "<i data-feather='chevron-right'></i>",
            ],
            responsive: {
              1000: {
                items: 4,
              },
              770: {
                items: 2,
              },
              0: {
                items: 1,
              },
            },
          });
        }
        feather.replace();
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  fetchPartner({ key: "postType", value: "partner", ordering: "ASC" });
  function fetchPartner(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/post/filter.php",
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let html = "";
          result.forEach((e) => {
            html += `<div class="single-partner text-center" title="${e.title}"> 
            <a href="${e.excerpt}"> <img src="uploads/${e.mediaName}" alt="${e.mediaName}"> 
              <h6>${e.title}</h6>
            </a> 
            </div>`;
          });
          $("#partnersAjax").html(html);
          $("#partnersAjax").owlCarousel({
            margin: 30,
            loop: false,
            dots: true,
            autoplay: true,
            responsive: {
              1000: {
                items: 4,
              },
              800: {
                items: 4,
              },
              500: {
                items: 2,
              },
              0: {
                items: 1,
              },
            },
          });
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  fetchNews({
    key: "postType",
    value: "post",
    operator: "AND",
    key2: "category",
    value2: "news",
    limit: "4",
  });
  function fetchNews(data) {
    $.ajax({
      url: baseUrl + "/back-end/api/post/filter.php",
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let html = "";
          result.forEach((e) => {
            let parsehtml = $.parseHTML(e.content);
            let str = $(parsehtml).text();
            var content = str.substring(0, 60) + "...";
            html +=
              `<div class="col-md-3"><div class="single-news shadow-sm"><a href="` +
              e.slug +
              `"><div><img src="uploads/` +
              e.mediaName +
              `" alt=""><div class="body p-3"><span> <i data-feather="calendar"></i> ` +
              e.createdMod +
              `</span><h6>` +
              e.title +
              `</h6>` +
              content +
              `</div></div></a></div></div>`;
          });
          $("#newsAjax").html(html);
          feather.replace();
        } else if (res.status == "204") {
          $("#newsAjax").html(
            '<div class="col-md-12"><h6 class="text-center">কোন তথ্য পাওয়া যায়নি।</h6></div>'
          );
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
});
