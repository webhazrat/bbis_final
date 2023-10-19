$(function(){

    console.log('forgot.js');

    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');
	
	const forgotFormBtn 	= document.getElementById('forgotFormBtn')
	const preloader 		= document.querySelector('.preloader')
	const alert 			= document.getElementById('alert')

	forgotFormBtn.addEventListener('click', function(e){
		e.preventDefault()
		let email = document.getElementById('email').value
		forgot({email})
	})

	async function forgot(data){
		preloader.classList.remove('hide')
		try{
			let response = await fetch(baseUrl+'/back-end/api/user/forgot.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json;charset=utf-8'
				},
				body: JSON.stringify(data)
			})
			let result = await response.json();
			console.log(result)
			preloader.classList.add('hide');
			if(result.status == '200'){
				alert.classList.add('show');
				alert.innerHTML = `<div class="alert-body text-success"> <div> <strong> Success! </strong> ${result.msg} </div> <a href="">OK</a> </div>`
			}else{
				alert.classList.add('show');
				alert.innerHTML = `<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${result.msg} </div> <a href="">OK</a> </div>`
			}
		}catch(error){
			console.log(error)
		}
	}
	
})