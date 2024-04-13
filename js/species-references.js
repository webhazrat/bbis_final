console.log("species-references.js");
$(function () {
  const speciesCode = $("#speciesCode").attr("code");
  let data = { key: "spCode", value: speciesCode };
  speciesReferences(data);

  function speciesReferences(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/species/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        console.log({ "Species References": res });
        if (res.status == "200") {
          const result = res.data[0];
          const ref = result.spRef;
          let html = `<h6>References for <i>${result.spScName}</i></h6>`;
          if (ref.length) {
            html += '<ol class="m-0">';
            ref.forEach((e, index) => {
              if (index !== 0) {
                html += `<li>${e}</li>`;
              }
            });
            html += "</ol>";
            $("#references").html(html);
          } else {
            html += `<span>No references<span>`;
          }
        }
      },
      error: function (e) {
        console.log(e.responseText);
      },
    });
  }
});
