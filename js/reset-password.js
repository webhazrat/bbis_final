$(function(){

    console.log('reset-password.js');

    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');
	
    
	const preloader 		= document.querySelector('.preloader')
	const alert 			= document.getElementById('alert')
	const changePassFormBtn = document.getElementById('changePassFormBtn')
    
    const action = document.getElementById('action').value
    const verifyToken = document.getElementById('verifyToken').value
	const verifyEmail = document.getElementById('verifyEmail').value
    verify({action, verifyToken, verifyEmail})

	async function verify(data){
		try{
			let response = await fetch(baseUrl+'/back-end/api/user/verify.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json;charset=utf-8'
				},
				body: JSON.stringify(data)
			})
			let result = await response.json();
			console.log(result)
			if(result.status == '200'){

			}else{
				alert.classList.add('show');
				alert.innerHTML = `<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${result.msg} </div> <a href="">OK</a> </div>`
			}
		}catch(error){
			console.log(error)
		}
	}

	changePassFormBtn.addEventListener('click', function(e){
		e.preventDefault()
		let password = document.getElementById('password').value
		let cPassword = document.getElementById('cPassword').value
		changePassword({verifyToken, verifyEmail, password, cPassword})
	})
	async function changePassword(data){
		preloader.classList.remove('hide')
		try{
			let response = await fetch(baseUrl+'/back-end/api/user/changePassword.php', {
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
				alert.innerHTML = `<div class="alert-body text-success"> <div> <strong> Success! </strong> ${result.msg}. Please login </div> <a href="">OK</a> </div>`
				setTimeout(() => {
					window.location = 'login'
				}, 1000);
			}else{
				alert.classList.add('show');
				alert.innerHTML = `<div class="alert-body text-danger"> <div> <strong> Sorry! </strong> ${result.msg} </div> <a href="">OK</a> </div>`
			}
		}catch(error){
			console.log(error)
		}
	}

	
})