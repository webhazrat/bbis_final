$(function () {
  console.log("species-add.js");

  speciesGroup();
  function speciesGroup() {
    $.ajax({
      url: `../back-end/api/group/filterAuth.php`,
      method: "POST",
      data: JSON.stringify({ key: "endLevel", value: "true" }),
      success: function (res) {
        console.log({ speciesGroup: res });
        if (res.status == "200") {
          let result = res.data;
          let option = "";
          result.forEach((item) => {
            option += `<option value="${item.id}">${item.hierarchyName}</option>`;
          });
          $("#spGroup").html(option).selectpicker("refresh");
        } else {
          console.log("Sorry! " + res.msg);
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }

  let selectedFile;
  $(document).on("change", "#speciesFile", function (event) {
    let fileName = event.target.files[0].name;
    let extArr = fileName.split(".");
    let ext = extArr[extArr.length - 1];
    if (ext === "xls" || ext === "xlsx") {
      selectedFile = event.target.files[0];
    } else {
      alert("Sorry! xls, xlsx file format supported");
      $(this).val("");
    }
  });

  const speciesAddBtn = $("#speciesSubmitBtn");

  $(speciesAddBtn).click(function (e) {
    e.preventDefault();
    let spGroup = $("#spGroup").val();
    if (spGroup === "") {
      alert("Sorry! Species group required");
    } else if (selectedFile === undefined) {
      alert("Sorry! Species File required");
    } else {
      let fileReader = new FileReader();
      fileReader.readAsBinaryString(selectedFile);
      fileReader.onload = (event) => {
        let data = event.target.result;
        let workSheet = XLSX.read(data, { type: "binary" });
        let obj = workSheet.Sheets[workSheet.SheetNames[0]];
        for (const item in obj) {
          let iL = obj[item].l;
          if (iL !== undefined) {
            obj[
              item
            ].v = `<a target='_blank' href='${iL.Target}'>${obj[item].v}</a>`;
          }
        }
        let rows = XLSX.utils.sheet_to_json(obj, { defval: "" });
        if (rows.length > 0) {
          rows.forEach((row) => {
            row = Object.entries(row).reduce((acc, curr) => {
              let [key, value] = curr;
              acc[typeof key === "string" ? key.trim() : key] = value;
              return acc;
            }, {});
            row.spGroup = spGroup;
            row.count = rows.length;
            speciesCreate({ ...row });
          });
          //$(".output").text(JSON.stringify(rows, undefined, 2));
        } else {
          alert("Sorry! File data required");
        }
      };
    }
  });

  function speciesCreate(data) {
    $.ajax({
      url: "../back-end/api/species/createAuth.php",
      method: "POST",
      data: JSON.stringify(data),
      beforeSend: function () {
        speciesAddBtn.attr("disabled", true);
        $("#addSpeciesForm button[type='submit']").attr("disabled", "disabled");
        $("#progress").removeClass("hide");
      },
      complete: function () {
        speciesAddBtn.removeAttr("disabled");
        $("#addSpeciesForm button[type='submit']").attr("disabled", false);
      },
      success: function (res) {
        console.log({ speciesCreate: res });
        if (res.status == "200") {
          $("#addSpeciesForm")[0].reset();
          $(".selectpicker").selectpicker("refresh");
          progress(res.count, 1);
        } else {
          console.log(res);
        }
      },
      error: function (error) {
        speciesAddBtn.removeAttr("disabled");
        console.log(error.responseText);
      },
    });
  }

  let items = 0;
  function progress(count, item) {
    items += item;
    let percentage = Math.round((items / count) * 100);
    $(".progress-bar").css({ width: percentage + "%" });
    $(".progress-bar").text(items + "/" + count);
  }
});
