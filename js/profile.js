console.log("profile.js");

$(function () {
  // summernote
  $(".summerNote").summernote();

  findOne("");
  function findOne(id) {
    $.ajax({
      url: `${baseUrl}/back-end/api/user/findOne.php?id=${id}`,
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
          const result = res.data[0];

          let profilePhoto = "";
          if (!result.photo) {
            profilePhoto += `<div id="uploaded_image">
                        <img src="${baseUrl}/assets/images/profile.png" alt=""> 
                        </div>`;
          } else {
            profilePhoto += `<div id="uploaded_image">
                        <img src="${baseUrl}/uploads/${result.photo}" alt=""> 
                        </div>`;
          }
          profilePhoto += `<div class="profile-upload"> <i data-feather="camera"></i> <input type="file" name="upload_image" id="upload_image"> </div>`;
          $("#profilePhoto").html(profilePhoto);
          $("#profile_name").html("<span>" + result.name + " </span>");

          var profile_info_list = "";
          profile_info_list +=
            '<li id="m_id" class="btn btn-soft-primary mb-2 text-center">ID NO: <span>' +
            result.mId +
            "</span></li>";
          profile_info_list +=
            '<li><i data-feather="calendar"></i>Joined ' +
            result.createdAt +
            "</li>";
          profile_info_list +=
            '<li> <i data-feather="smartphone"></i>+88' +
            result.phone +
            "</li>";
          profile_info_list +=
            '<li> <i data-feather="mail"></i>' + result.email + "</li>";
          profile_info_list +=
            '<li> <i data-feather="bookmark"></i> ' +
            result.userRoles +
            "</li>";

          $(".profile_info_list").html(profile_info_list);

          let personal_info = "";
          personal_info +=
            "<li><strong>Date of Birth :</strong> " +
            result.dob +
            ", <strong>Gender :</strong> " +
            result.gender;
          personal_info +=
            "<li><strong>Profession :</strong> " +
            result.profession +
            ", <strong>Institution :</strong> " +
            result.institution +
            "</li>";
          personal_info +=
            "<li><strong>Address :</strong> " +
            result.zoneName +
            ", " +
            result.areaName +
            ", " +
            result.districtName +
            "</li>";
          personal_info +=
            "<li><strong>About :</strong> <br>" + result.about + "</li>";
          $("#personal_info").html(personal_info);
          feather.replace();
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $image_crop = $("#image_demo").croppie({
    enableExif: true,
    viewport: {
      width: 180,
      height: 180,
      type: "square",
    },
    boundary: {
      width: 250,
      height: 250,
    },
  });
  $(document).on("change", "#upload_image", function () {
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop
        .croppie("bind", {
          url: event.target.result,
        })
        .then(function () {
          console.log("jQuery bind complete");
        });
    };
    reader.readAsDataURL(this.files[0]);
    $("#uploadImageModal").modal("show");
  });

  $(".crop_image").click(function (event) {
    $image_crop
      .croppie("result", {
        type: "canvas",
        size: "viewport",
      })
      .then(function (response) {
        let data = { image: response };
        profilePhoto(data);
      });
  });
  function profilePhoto(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/user/profilePhoto.php`,
      method: "POST",
      data: JSON.stringify(data),
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          $("#uploadImageModal").modal("hide");
          findOne("");
        } else {
          alert("Sorry! " + res.msg);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $(document).on("click", "#editProfile", function (e) {
    e.preventDefault();
    findForEdit("");
    $("#profileUpdateModal").modal("show");
  });
  function findForEdit(id) {
    $.ajax({
      url: `${baseUrl}/back-end/api/user/findOne.php?id=${id}`,
      method: "GET",
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        console.log({ findForEdit: res });
        if (res.status == "200") {
          var result = res.data[0];
          $("#updateProfileBtn").attr("data_id", result.id);
          $("#name").val(result.name);
          $("#gender").selectpicker("val", result.gender);
          $("#phone").val(result.phone);
          $("#dob").val(result.dob);
          $("#profession").val(result.profession);
          $("#institution").val(result.institution);
          $("#about").summernote("code", result.about);
          $("#district").selectpicker("val", result.district);
          filterArea(result.district, result.area);
          filterZone(result.area, result.zone);
        }
      },
    });
  }
  function filterArea(id, areaId) {
    $.ajax({
      url: baseUrl + "/back-end/api/location/area.php?districtId=" + id,
      method: "GET",
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let area = "";
          result.forEach((e) => {
            area += "<option value='" + e.id + "'>" + e.name + "</option>";
          });
          $("#area").html(area).selectpicker("refresh");
          setTimeout(function () {
            $("#area").selectpicker("val", areaId);
          }, 10);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
  function filterZone(id, zoneId) {
    $.ajax({
      url: baseUrl + "/back-end/api/location/zone.php?areaId=" + id,
      method: "GET",
      success: function (res) {
        if (res.status == "200") {
          const result = res.data;
          let zone = "";
          result.forEach((e) => {
            zone += "<option value='" + e.id + "'>" + e.name + "</option>";
          });
          $("#zone").html(zone).selectpicker("refresh");
          setTimeout(function () {
            $("#zone").selectpicker("val", zoneId);
          }, 10);
        } else {
          $("#zone").html("").selectpicker("refresh");
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $(document).on("click", "#updateProfileBtn", function (e) {
    e.preventDefault();
    const id = $(this).attr("data_id");
    const name = $("#name").val();
    const gender = $("#gender").val();
    const dob = $("#dob").val();
    const phone = $("#phone").val();
    const district = $("#district").val();
    const area = $("#area").val();
    const zone = $("#zone").val();
    const profession = $("#profession").val();
    const institution = $("#institution").val();
    const about = $("#about").val();
    update(id, {
      name,
      gender,
      dob,
      phone,
      district,
      area,
      zone,
      profession,
      institution,
      about,
    });
  });
  function update(id, data) {
    $.ajax({
      url: baseUrl + "/back-end/api/user/update.php?id=" + id,
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
          $("#profileUpdateModal").modal("hide");
          findOne("");
        } else {
          alert("Sorry!" + res.msg);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }

  $(document).on("click", "#profileSocialBtn", function (e) {
    e.preventDefault();
    const title = $("#socialTitle").val();
    const url = $("#socialURL").val();
    createSocial({ title, url });
  });
  function createSocial(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/user-social/create.php`,
      method: "POST",
      data: JSON.stringify(data),
      beforeSend: function () {
        $(".preloader4").show();
      },
      complete: function () {
        $(".preloader4").hide();
      },
      success: function (res) {
        if (res.status == "200") {
          $("#profileSocialModal").modal("hide");
          $("#profileSocialForm")[0].reset();
          $(".selectpicker").selectpicker("refresh");
          filterSocial();
        } else {
          alert(`Sorry! ${res.msg}`);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }

  filterSocial();
  function filterSocial() {
    $.ajax({
      url: `${baseUrl}/back-end/api/user-social/filterAuth.php`,
      method: "POST",
      data: JSON.stringify({ key: "memberId", value: "-1" }),
      success: function (res) {
        console.log({ filterSocial: res });
        if (res.status == "200") {
          const result = res.data;
          let html = "";
          result.forEach((item) => {
            html += `<li><a href="${item.url}" target="_blank">${item.title}</a> <span id="removeSocial" data_id="${item.id}" class="chipsBtn"><i data-feather="x"></i></span></li>`;
          });
          $("#social-links").html(html);
          feather.replace();
        } else {
          console.log(`Sorry! ${res.msg}`);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }

  $(document).on("click", "#removeSocial", function (e) {
    e.preventDefault();
    const id = $(this).attr("data_id");
    if (confirm("Are you sure to delete?")) {
      deleteSocial(id);
    }
  });
  function deleteSocial(id) {
    $.ajax({
      url: `${baseUrl}/back-end/api/user-social/delete.php?id=${id}`,
      method: "DELETE",
      success: function (res) {
        console.log({ deleteSocial: res });
        if (res.status == "200") {
          filterSocial();
        } else {
          alert(`Sorry! ${res.msg}`);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }

  speciesReviewNotify();
  function speciesReviewNotify() {
    $.ajax({
      url: `${baseUrl}/back-end/api/species/filterAuth.php`,
      method: "POST",
      data: JSON.stringify({ key: "author", status: "5" }),
      success: function (res) {
        console.log({ speciesReviewNotify: res });
        if (res.status == "200") {
          $("#species-review").html(`<div class="alert alert-danger">
                        <strong>${res.rows}</strong> amount of species contribution are canceled. <a href="my-contributions?status=canceled" class="alert-link">Please check and resubmit</a>
                    </div>`);
        } else {
          console.log(`Sorry! ${res.msg}`);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }
});
