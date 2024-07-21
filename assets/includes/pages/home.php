<?php

// The Page Title
$data['title'] = 'Welcome';

// Checks if User already logged in
if ($data['loggedin']) {
	$controller->redirect('timeline');
}

// Displaying the Header
$controller->render('header', $data, $controller);
?>	


	<div class="row">

		<!-- Features of City Computer Science Study Buddy -->
		<div class="col-md-6">
			<h1 class="title">Computer Science<br>Study Buddy</h1>
			
			<hr class="fancy">

			<table class="home-list">
				<tr>
					<td><i class="fas fa-book-open"></i></td>
					<td>Never revise alone again</td>
				</tr>
				<tr>
					<td><i class="fas fa-clipboard"></i></td>
					<td>Share Notes with your peers</td>
				</tr>
				<tr>
					<td><i class="fas fa-users"></i></td>
					<td>Make new Friends</td>
				</tr>
			</table>

		</div>


		<!-- The Registration -->
		<div class="col-md-6">
			<div class="card home-sign-up">

				<div class="card-title">
					<h3>Register</h3>
				</div>

				<form action="<?php echo HOME; ?>register" method="post">
					<span class="form-element">
						<input class="form-control" type="text" name="username" placeholder="username" required>
					</span>

					<span class="form-element">
						<div class="row">
							<div class="col-6">
								<input class="form-control" type="text" name="email" placeholder="email" required>
							</div>

							<div class="col-6">
								<select class="form-control" name="uni_email" required>
									<option>@city.ac.uk</option>
									<option disabled>@gre.ac.uk</option>
								</select>
							</div>
						</div>
					</span>

					<span class="form-element">
						<input class="form-control" type="password" name="password" placeholder="password" required>
					</span>

					<button type="submit" class="btn btn-dark float-right" name="submit">Register</button>
				</form>
			</div>
		</div>
	</div>
			
<?php
// Displaying the Footer
$controller->render('footer', $data);
?>