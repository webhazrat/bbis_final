$(function () {

	console.log('group-edit.js');

	$(document).on("keyup", "#gName", function(){
		$("#gSlug").val(convertToSlug($(this).val().trim()));
	})
	function convertToSlug(Text) {
		return Text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ' ');
	}
	
	function getSpeciesGroup(selectId) {
		$.ajax({
			url: `../back-end/api/group/filter.php`,
			method: "POST",
			data: JSON.stringify({ "key": "endLevel", "value": "false" }),
			success: function (res) {
				console.log({'getSpeciesGroup':res})
				if (res.status == '200') {
					let result = res.data;
					let option = '<option value="">None</option>';
					result.forEach(item => {
						option += `<option value="${item.id}">${item.hierarchyName.join(' > ')}</option>`;
					});
					$("#gParent").html(option).selectpicker('refresh');
					$("#gParent").selectpicker('val', selectId);
				} else {
					console.log("Sorry! " + res.msg);
				}
			},
			error: function (e) {
				console.log(e.responseText);
			}
		});
	}
    
    findOneForEdit($('#groupUpdateBtn').attr('dataId'))
	function findOneForEdit(id) {
		$.ajax({
			url: `../back-end/api/group/findOne.php?id=${id}`,
			method: "GET",
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
				console.log({'findOneForEdit':res});
				if (res.status == '200') {
					const result = res.data[0];
                    $("#pageTitle").text('Update - ' + result.name);
					$("#groupUpdateBtn").attr('dataId', result.id);
					if (result.photoName !== null) {
						$("#featureImage").html(`<span><a href="#" class="chipsBtn"><i data-feather="x"></i></a><img dataId="${result.photo}" src="../uploads/${result.photoName}" alt="${result.photoName}"></span>`);
					}else{
						$("#featureImage").html('')
					}
					let endLevel = result.endLevel === 'true' ? true : false;
					$("#gName").val(result.name);
					$("#gSlug").val(result.slug);
                    
					
					getSpeciesGroup(result.parent);
					
					$("#status").selectpicker('val', result.status);
					$("#ordering").val(result.ordering);
					$('#endLevel').prop('checked', endLevel);
					$("#gDescription").html(result.description);
					feather.replace();
				} else {
					console.log(`Sorry! ${res.msg}`);
				}
			},
			error: function (e) {
				console.log(e.responseText);
			}
		});
	}
    
	$(document).on("click", "#groupUpdateBtn", function (e) {
		e.preventDefault();
		let id 			    = $(this).attr('dataId');
        let data = {}
		data.photo 		    = $("#featureImage img").attr('dataId') === undefined ? '' : $("#featureImage img").attr('dataId');
		data.name 		    = $("#gName").val();
		data.slug 		    = $("#gSlug").val();
		data.parent 		= $("#gParent").val();
		data.ordering 	    = $("#ordering").val();
		data.endLevel 	    = $("#endLevel").is(':checked') ? 'true' : 'false';
		data.description    = $("#gDescription").val();
		data.status         = $("#status").val();
		
		update(id, data);
	});

	function update(id, data) {
		$.ajax({
			url: `../back-end/api/group/update.php?id=${id}`,
			method: "PUT",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
				if (res.status == '200') {
                    alert('Success! '+res.msg);
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