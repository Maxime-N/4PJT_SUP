<?php

//Contenu traduit
include('translation/createCompteT.php');

	$formregister = lireDonneePost("formregister", "");
	if ($formregister == 1){
		registerUser($connect);
	}
?>
<div class="container">
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3" id="registerForm">
			<h3><?php echo $tabTexteCreerCompte[0]; ?></h3>
			<form method="post" role="form" data-toggle="validator">
				<fieldset>
					<legend></legend>
					<div class="form-group">
						<label for="firstname"><?php echo $tabTexteCreerCompte[1]; ?></label>
						<input type="text" name="firstname" id="firstname" class="form-control" data-error="" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="lastname"><?php echo $tabTexteCreerCompte[2]; ?></label>
						<input type="text" name="lastname" id="lastname" class="form-control" data-error="" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="email"><?php echo $tabTexteCreerCompte[3]; ?></label>
						<input type="email" name="email" id="email" class="form-control" data-error="" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="username"><?php echo $tabTexteCreerCompte[4]; ?></label>
						<input type="text" name="username" id="username" class="form-control" data-error="" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="matchingPasswords"><?php echo $tabTexteCreerCompte[5]; ?></label>
						<input type="password" name="password" class="form-control" id="matchingPasswords" data-error="" data-minlength="6" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="confpassword"><?php echo $tabTexteCreerCompte[6]; ?></label>
						<input type="password" name="confpassword" id="confpassword" class="form-control" data-match="#matchingPasswords" data-match-error="" data-error="" required>
						<div class="help-block with-errors"></div>
					</div>
				</fieldset>
				<input type="hidden" name="formregister" value="1"/>
				<button type="submit" class="btn btn-primary"><?php echo $tabTexteCreerCompte[7]; ?></button>
			</form>
		</div>
	</div>
</div>