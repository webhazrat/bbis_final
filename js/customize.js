$(function(){

	console.log('customize.ajax.js');

	$(document).on("click", "#siteIdentityBtn", function (e) {
        e.preventDefault();
		let logo = $("#featureImage img").attr('dataId') ? $("#featureImage img").attr('dataId') : '';
		let title = $("#siteTitle").val();
		const data = [
			{'key': 'siteLogo', 'value': logo},
			{'key': 'siteTitle', 'value': title}
		]
		create(data)
	});

	$(document).on('click', '#sliderContentBtn', function(e){
		e.preventDefault()
		let sliderTitle 	= $('#sliderTitle').val()
		let sliderDescription 	= $('#sliderDescription').val()
		const data = [
			{'key':'sliderTitle', 'value':sliderTitle},
			{'key':'sliderDescription', 'value':sliderDescription}
		]
		create(data)
	})

    function create(data){
        $.ajax({
			url: "../back-end/api/customize/create.php",
			method: "POST",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
				alert(res.msg)
			},
            error: function(e){
                console.log(e.responseText);
            }
		});
    }
	

	filter({"keys" : ['siteLogo', 'siteTitle', 'sliderTitle', 'sliderDescription']});

	function filter(data) {
		$.ajax({
			url: "../back-end/api/customize/filter.php",
			method: "POST",
			data: JSON.stringify(data),
			beforeSend: function () {
				$('.preloader').show();
			},
			complete: function () {
				$('.preloader').hide();
			},
			success: function (res) {
                console.log(res)
                if(res.status == '200'){
                    let result = res.data;
                    let siteLogo = result.find(item => item.optionKey == 'siteLogo')
                    let siteTitle = result.find(item => item.optionKey == 'siteTitle')
                    let sliderTitle = result.find(item => item.optionKey == 'sliderTitle')
                    let sliderDescription = result.find(item => item.optionKey == 'sliderDescription')
					
					let logo = siteLogo.mediaName ? `<img dataId="`+siteLogo.optionValue+`" src="../uploads/`+siteLogo.mediaName+`" alt="`+siteLogo.mediaName+`">` : ''
                    $("#featureImage").html(logo)
                    $("#siteTitle").val(siteTitle.optionValue)
                    $("#sliderTitle").val(sliderTitle.optionValue)
                    $("#sliderDescription").val(sliderDescription.optionValue)
                }
			},
            error: function(e){
                console.log(e.responseText);
            }
		});
	}

})