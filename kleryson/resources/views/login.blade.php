<html>
	<header>
		<script src="./js/jquery.inc.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		<!-- <script src="./js/validation.inc.js" ></script> //-->

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	</header>
	<body>
		<form name='form-login' class='needs-validation' action='/logar'  method='POST' >
			@csrf
			<div class="container col-mb-3">
				<div class="row-mb-3">
					<label for="validationLogin" class="form-label">Login:</label>
					<input type="text" name='usuario' class="form-control" id="validationLogin" placeholder="Usuario" required>
				</div>
				<div class="row-mb-3">
					<label for="validationPass" class="form-label">Senha:</label>
				 	<input type="password" name='senha' class="form-control" id="validationPass" placeholder="Senha" required>
				</div>
				<div class="row-mb-3">
					<br/>
			  		<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</div>
		</form>
	</body>
</html>