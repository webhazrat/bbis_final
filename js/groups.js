console.log('groups.js');

$(function () {
    
  filterGroup({ "key": "endLevel", "value": "true", "ordering":'ASC'});
  function filterGroup(data) {
    $.ajax({
      url: `${baseUrl}/back-end/api/group/filter.php`,
      method: "POST",
      data: JSON.stringify(data),
      success: function (res) {
        console.log({'filterGroup':res})
        if (res.status == '200') {
          const result = res.data;
          let html = '';
          result.forEach((item) => {
            html += `<div class="col-md-3"><div class="single-group mb-4"><div><img class="shadow" src="${baseUrl}/uploads/${item.mediaName}"><div class="body"><div class="d-flex justify-content-between align-items-center mt-3 mb-2"><span><i data-feather="calendar"></i> ${item.createdAt} </span> <a href="${baseUrl}/species/${item.slug}"><span> <i data-feather="file-text"></i> ${item.spApprovedCount} Species</span></a> </div> <a href="${baseUrl}/group/${item.slug}"><h5> ${item.hierarchyName.join(' > ')} </h5></a> </div></div></div></div>`;
          })
          $("#groupAjax").html(html);
          feather.replace();
        }
      },
      error: function (e) {
        console.log(e.responseText);
      }
    });
  }
  
  

})