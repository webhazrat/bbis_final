$(function () {
  console.log("join.js");

  const element = document.querySelector(".navbar-brand");
  const baseUrl = element.getAttribute("href");

  const joinFormBtn = document.getElementById("joinFormBtn");
  const preloader = document.querySelector(".preloader");
  const alert = document.getElementById("alert");

  joinFormBtn.addEventListener("click", function (e) {
    e.preventDefault();
    const data = {};
    data.name = document.getElementById("name").value;
    data.phone = document.getElementById("phone").value;
    data.email = document.getElementById("email").value;
    data.password = document.getElementById("password").value;
    data.cPassword = document.getElementById("cPassword").value;
    data.profession = document.getElementById("profession").value;
    data.institution = document.getElementById("institution").value;
    data.terms = document.getElementById("terms").checked ? "true" : "false";
    create(data);
  });

  async function create(data) {
    preloader.classList.remove("hide");
    joinFormBtn.setAttribute("disabled", true);
    try {
      let response = await fetch(baseUrl + "/back-end/api/user/create.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json;charset=utf-8",
        },
        body: JSON.stringify(data),
      });

      let result = await response.json();
      preloader.classList.add("hide");
      joinFormBtn.removeAttribute("disabled");
      if (result.status == "200") {
        document.getElementById("joinForm").reset();
        alert.classList.add("show");
        alert.innerHTML = `<div class="alert-body text-success"> <div> <strong> Success! </strong> ${result.msg} </div> <a href="">OK</a> </div>`;
        window.location = "login";
      } else {
        alert.classList.add("show");
        alert.innerHTML = `<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${result.msg} </div> <a href="">OK</a> </div>`;
      }
    } catch (error) {
      preloader.classList.add("hide");
      joinFormBtn.removeAttribute("disabled");
      console.log(error);
    }
  }
});
