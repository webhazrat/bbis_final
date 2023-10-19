$(function () {

	console.log('species-group.js');

	const groupAll = $('#groupAll').DataTable({
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: "../back-end/api/group/filterAuth.php",
			type: "POST",
			data: function (d) {
                d.type = 'dataTable';
				return JSON.stringify(d);
			}
		},
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"columns": [
			{ "data": "sn" },
			{ "data": "groupName" },
			{ "data": "hierarchyName" },
			{ "data": "endLevel" },
			{ "data": "spTotalCount" },
			{ "data": "spApprovedCount" },
			{ "data": "authorName" },
			{ "data": "ordering" },
			{ "data": "statusMod" },
			{ "data": "createdMod" },
			{ "data": "action" }
		],
		"columnDefs": [
			{ "targets": [10], 'orderable': false, 'className': 'text-center' }
		],
		"order": [],
		"drawCallback": function (settings) {
			feather.replace();
		}
	});

	$(document).on("click", "#groupDelete", function (e) {
		e.preventDefault();
		if (confirm("Are you sure?")) {
			deleteGroup($(this).attr('dataId'));
		}
	})

	function deleteGroup(id) {
		$.ajax({
			url: "../back-end/api/group/delete.php?id=" + id,
			method: "DELETE",
			success: function (res) {
				console.log(res);
				if (res.status == '200') {
					groupAll.ajax.reload();
				} else {
					alert('Sorry! ' + res.msg);
				}
			},
			error: function (e) {
				console.log(e.responseText);
			}
		});
	}



})