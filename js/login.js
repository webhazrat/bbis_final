$(function () {
  console.log("login.js");
  const element = document.querySelector(".navbar-brand");
  const baseUrl = element.getAttribute("href");

  const loginFormBtn = document.getElementById("loginFormBtn");
  const preloader = document.querySelector(".preloader");
  const alert = document.getElementById("alert");

  loginFormBtn.addEventListener("click", function (e) {
    e.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    login({ email, password });
  });

  async function login(data) {
    preloader.classList.remove("hide");
    try {
      let response = await fetch(baseUrl + "/back-end/api/user/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json;charset=utf-8",
        },
        body: JSON.stringify(data),
      });

      let result = await response.json();
      preloader.classList.add("hide");
      if (result.status == "200") {
        alert.classList.add("show");
        alert.innerHTML = `<div class="alert-body text-success"> <div> <strong> Success! </strong> ${result.msg} </div> <a href="">OK</a> </div>`;
        window.location = "profile";
      } else if (result.status == "401") {
        alert.classList.add("show");
        alert.innerHTML = `<div class="alert-body text-success"> <div> <strong> Success! </strong> ${result.msg} </div> <a href="">OK</a> </div>`;
      } else {
        alert.classList.add("show");
        alert.innerHTML = `<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${result.msg} </div> <a href="">OK</a> </div>`;
      }
    } catch (error) {
      preloader.classList.add("hide");
      console.log(error);
    }
  }

  if (localStorage.chkbx && localStorage.chkbx != "") {
    $("#remember_me").attr("checked", "checked");
    $("#email").val(localStorage.email);
    $("#password").val(localStorage.password);
  } else {
    $("#remember_me").removeAttr("checked");
    $("#email").val("");
    $("#password").val("");
  }

  $("#remember_me").click(function () {
    if ($("#remember_me").is(":checked")) {
      localStorage.email = $("#email").val();
      localStorage.password = $("#password").val();
      localStorage.chkbx = $("#remember_me").val();
    } else {
      localStorage.email = "";
      localStorage.password = "";
      localStorage.chkbx = "";
    }
  });

  const action = document.getElementById("action").value;
  const verifyToken = document.getElementById("verifyToken").value;
  const verifyEmail = document.getElementById("verifyEmail").value;
  if (action == "verify") {
    verify({ action, verifyToken, verifyEmail });
  }

  async function verify(data) {
    try {
      let response = await fetch(baseUrl + "/back-end/api/user/verify.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json;charset=utf-8",
        },
        body: JSON.stringify(data),
      });
      let result = await response.json();
      console.log(result);
      if (result.status == "200") {
        alert.classList.add("show");
        alert.innerHTML = `<div class="alert-body text-success"> <div> <strong> Success! </strong> Account verified. Please login </div> <a href="">OK</a> </div>`;
      } else {
        alert.classList.add("show");
        alert.innerHTML = `<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${result.msg} </div> <a href="">OK</a> </div>`;
      }
    } catch (error) {
      console.log(error);
    }
  }
});
