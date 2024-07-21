	</div>
	<!-- End Container -->

	<!-- Footer -->
	<footer class="fixed-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="copy">
						&copy; 2020 CCSSB by Safat Sikder
					</div>
				</div>

				<div class="col-md-6">
					<div class="links float-right">
						terms & conditions | privacy policy
					</div>
				</div>
			</div>
		</div>
	</footer>

	<!-- External Imports -->
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

	<!-- Internal Imports -->
	<script src="<?php echo JS; ?>main.js" type="text/javascript"></script>
	<script src="<?php echo JS; ?>ccssb_api.php" type="text/javascript"></script>

	<?php
	// Checks if any addition js files need to be imported
	if (isset($data['loadjs'])) {
		$jsfiles = $data['loadjs'];
		if (is_array($jsfiles)) {
			foreach ($jsfiles as $value) {
				echo '<script type="text/javascript" src="'.JS.$value.'.js"></script>';
			}
		} else {
			echo '<script type="text/javascript" src="'.JS.$jsfiles.'.js"></script>';
		}
	}
	?>

</body>
</html>