<html>
	<header>
		<script src="./js/jquery.inc.js"></script>
		<script src="./js/jquery-validation.inc.js"></script>
		<script src="./js/topMenu.inc.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		<!-- <script src="./js/validation.inc.js" ></script> //-->
		<link href="./css/layout_modelo.css" rel="stylesheet">
		<link href="./css/topMenu.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	</header>
	<body>
		<div class="container">
			<nav class="navbar navbar-expand-lg bg-body-tertiary">
				<div class="container-fluid">
					<a class="navbar-brand" href="#">Navbar</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNavDropdown">
						<ul class="navbar-nav">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Cadastro
								</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="/usuario">Usuario</a></li>
								<li><a class="dropdown-item" href="/perfil">Perfil</a></li>
							</ul>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class='container'>
			<form name='form-login' class='needs-validation' action='/usuarioSalvar'  method='POST' >
			@csrf
			<input type='hidden' name='COD_USUARIO' value='' />
			<div class="container col-3">
				<div class="row">
					<label>Cadastro</label>
				</div>
				<div class="row">
					<label for="validationLogin" class="form-label">Nome:</label>
					<input type="text" name='nome' class="form-control" id="validationNome" data-validation='required' placeholder="Nome Completo">
				</div>
				<div class="row">
					<label for="validationLogin" class="form-label">Usuário:</label>
					<input type="text" name='usuario' class="form-control" id="validationNome" data-validation="length required" data-validation-length="min6" placeholder="Usuário">
				</div>
				<div class="row">
					<label for="validationLogin" class="form-label">E-mail:</label>
					<input type="text" name='email' class="form-control" data-validation="required email" id="validationNome" placeholder="Email" >
				</div>
				<div class="row">
					<label for="validationPass" class="form-label">Senha:</label>
				 	<input type="password" name='senha' class="form-control" data-validation="required alphanumeric length" data-validation-length="min8"  id="validationPass" placeholder="Another input placeholder">
				</div>
				<div class="row">
					<label for="validationPass" class="form-label">Perfil:</label>
				 	<select name='perfil' data-validation="required">
				 		<option value=''>Selecione um Perfil</option>
				 		@foreach($arrPerfil as $perfil)
				 			<option value='{{ $perfil->COD_PERFIL }}'>{{ $perfil->NOM_PERFIL }}</option>
				 		@endforeach
				 	</select>
				</div>
				<br/>
				<div class="row">
					<br/>
			  		<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</div>
		</form>
		<script>
    		$.validate();
		</script>
	    </div>
	</body>
</html>