console.log("users.js");

$(function () {
  const userAll = $("#userAll").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: `../back-end/api/user/filterAuth.php`,
      type: "POST",
      data: function (d) {
        d.key = "all";
        d.value = "1";
        d.type = "dataTable";
        return JSON.stringify(d);
      },
    },
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"],
    ],
    columns: [
      { data: "sn" },
      { data: "mId" },
      { data: "namePhoto" },
      { data: "phoneAction" },
      { data: "emailAction" },
      { data: "address" },
      { data: "roles" },
      { data: "groups" },
      { data: "type" },
      { data: "statusName" },
      { data: "createdMod" },
      { data: "action" },
    ],
    columnDefs: [{ targets: [11], orderable: false, className: "text-center" }],
    order: [],
    drawCallback: function (settings) {
      feather.replace();
    },
  });

  $(document).on("click", "#userAuthStatus", function (e) {
    e.preventDefault();
    const id = $(this).attr("data_id");
    $("#userId").val(id);
    userAuthStatus(id);
    $("#userStatusModal").modal("show");
  });

  async function userAuthStatus(id) {
    try {
      await findAllRole();
      await speciesGroupEndLevel();
      await peopleType();
      findOneForAuth(id);
    } catch (error) {
      console.log(error);
    }
  }

  function peopleType() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../back-end/api/people/filter.php",
        method: "POST",
        data: JSON.stringify({ key: "status", value: "6" }),
        success: function (res) {
          console.log({ peopleType: res });
          if (res.status == "200") {
            let result = res.data;
            let option = '<option value="">None</option>';
            result.forEach((item) => {
              option +=
                '<option value="' + item.id + '">' + item.name + "</option>";
            });
            $("#userType").html(option).selectpicker("refresh");
            resolve();
          } else {
            reject(`Sorry! ${res.msg}`);
          }
        },
        error: function (e) {
          reject(e.responseText);
        },
      });
    });
  }

  function findAllRole() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `../back-end/api/role/findAll.php`,
        method: "GET",
        success: function (res) {
          console.log({ findAllRole: res });
          if (res.status == "200") {
            var result = res.data;
            var role = "";
            for (var i = 0; i < result.length; i++) {
              role +=
                "<option value='" +
                result[i]["id"] +
                "'>" +
                result[i]["role"] +
                "</option>";
            }
            $("#userRole").html(role).selectpicker("refresh");
            resolve();
          }
        },
        error: function (error) {
          reject(error.responseText);
        },
      });
    });
  }

  function speciesGroupEndLevel() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `../back-end/api/group/filter.php`,
        method: "POST",
        data: JSON.stringify({ key: "endLevel", value: "true" }),
        success: function (res) {
          console.log({ speciesGroupEndLevel: res });
          if (res.status == "200") {
            let result = res.data;
            let option = "";
            result.forEach((item) => {
              option += `<option value="${item.id}">${item.hierarchyName.join(
                " > "
              )}</option>`;
            });
            $("#managedGroup").html(option).selectpicker("refresh");
            resolve();
          } else {
            reject(`Sorry! ${res.msg}`);
          }
        },
        error: function (e) {
          reject(e.responseText);
        },
      });
    });
  }

  function findOneForAuth(id) {
    $.ajax({
      url: `../back-end/api/user/findOne.php?id=${id}`,
      method: "GET",
      beforeSend: function () {
        $(".preloader").show();
      },
      complete: function () {
        $(".preloader").hide();
      },
      success: function (res) {
        console.log({ findOneForAuth: res });
        if (res.status === "200") {
          let result = res.data[0];
          let role = result.role?.split(",");
          let manageGroup = result.manageGroup?.split(",");
          $("#userRole").selectpicker("val", role);
          if (role.includes("3")) {
            $("#managed_group_block").removeClass("hide");
          } else {
            $("#managedGroup").selectpicker("val", "");
            $("#managed_group_block").addClass("hide");
          }
          $("#managedGroup").selectpicker("val", manageGroup);
          $("#userType").selectpicker("val", result.peopleType);
          $("#userStatus").selectpicker("val", result.status);
        } else {
          console.log(res.msg);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $(document).on("change", "#userRole", function () {
    if ($(this).val().includes("3")) {
      $("#managed_group_block").show();
    } else {
      $("#managed_group_block").hide();
    }
  });

  $(document).on("click", "#userStatusBtn", function (e) {
    e.preventDefault();
    const id = $("#userId").val();
    const role = $("#userRole").val().toString();
    const manageGroup = $("#managedGroup").val().toString();
    const peopleType = $("#userType").val();
    const status = $("#userStatus").val();
    updateAuth(id, { role, manageGroup, peopleType, status });
  });

  function updateAuth(id, data) {
    $.ajax({
      url: `../back-end/api/user/updateAuth.php?id=${id}`,
      method: "PUT",
      data: JSON.stringify(data),
      success: function (res) {
        if (res.status == "200") {
          userAll.ajax.reload();
          $("#userStatusModal").modal("hide");
        } else {
          alert("Sorry! " + res.msg);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  $(document).on("click", "#userView", function (e) {
    e.preventDefault();
    const id = $(this).attr("data_id");
    findOne(id);
    filterSocial(id);
    $("#userViewModal").modal("show");
  });

  function findOne(id) {
    $.ajax({
      url: `../back-end/api/user/findOne.php?id=${id}`,
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
          const photo = result.photo
            ? `<img src="../uploads/${result.photo}" alt="">`
            : `<img src="assets/images/profile.png" alt="">`;

          $("#profilePhoto").html(photo);
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

  function filterSocial(id) {
    $.ajax({
      url: `../back-end/api/user-social/filterAuth.php`,
      method: "POST",
      data: JSON.stringify({ key: "memberId", value: id }),
      success: function (res) {
        console.log({ filterSocial: res });
        if (res.status == "200") {
          const result = res.data;
          let html = "";
          result.forEach((item) => {
            html += `<li><a href="${item.url}" target="_blank">${item.title}</a></li>`;
          });
          $("#social-links").html(html);
        } else {
          $("#social-links").html("");
          console.log(`Sorry! ${res.msg}`);
        }
      },
      error: function (error) {
        console.log(error.responseText);
      },
    });
  }
});
