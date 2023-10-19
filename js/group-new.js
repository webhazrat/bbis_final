$(function () {

	console.log('species-group.js');

	$(document).on("keyup", "#gName", function(){
		$("#gSlug").val(convertToSlug($(this).val().trim()));
	})
	function convertToSlug(Text) {
		return Text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ' ');
	}
	
	$(document).on("click", "#groupCreateBtn", function (e) {
		e.preventDefault();
        let data = {}
		data.photo 		= $("#featureImage img").attr('dataId') === undefined ? '' : $("#featureImage img").attr('dataId');
		data.name 		= $("#gName").val();
		data.slug 		= $("#gSlug").val();
		data.parent 	= $("#gParent").val();
		data.ordering 	= $("#ordering").val();
        data.status 	= $('#status').val();
		data.endLevel 	= $("#endLevel").is(':checked') ? 'true' : 'false';
		data.description= $("#gDescription").val();
		create(data);
	});
	
	function create(data) {
		$.ajax({
			url: "../back-end/api/group/create.php",
			method: "POST",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
				if (res.status == '200') {
					getSpeciesGroup();
					$("#groupNewForm")[0].reset();
					$("#featureImage").html('');
					$('.selectpicker').selectpicker('refresh');
					alert('Success! '+res.msg)
				} else {
					alert('Sorry! ' + res.msg);
				}
			},
			error: function (e) {
				console.log(e.responseText);
			}
		});
	}

	getSpeciesGroup();
	function getSpeciesGroup() {
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
					$("#gParent").html(option);
					$(".selectpicker").selectpicker('refresh');
				}
			},
			error: function (e) {
				console.log(e.responseText);
			}
		});
		
	}

    
})