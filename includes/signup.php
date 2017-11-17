<?php
	if (isset($_POST['signup'])) {
		$screenName = $_POST['screenName'];
		$email      = $_POST['email'];
		$password   = $_POST['password'];

		if (empty($screenName) OR empty($email) OR empty($password)) {
			$error = 'All Fields are required !';
		} else {
			$screenName = $getFromU->checkInput($screenName);
			$email      = $getFromU->checkInput($email);
			$password   = $getFromU->checkInput($password);

			if (!filter_var($email)) {
				$error = 'Invalid Email';
			}
			if (strlen($screenName) > 20 OR strlen($screenName) < 5) {
				$error = 'Name must be in between 6-20 characters';
			}
			if (strlen($password) < 8 OR strlen($password) > 20) {
				$error = 'Password must be in between 8-20 characters';
			}
			if ($getFromU->checkExistingEmail($email) === true) {
				$error = 'Email is already in use please login with that or choose another email';
			}
			if (!isset($error)) {
				$user_data = array(
					'email'        => $email,
					'password'     => md5($password),
					'screenName'   => $screenName,
					'profileImage' => 'assets/images/defaultProfileImage.png',
					'profileCover' => 'assets/images/defaultCoverImage.png'
				);
				$user_id = $getFromU->create('users', $user_data);
				$_SESSION['user_id'] = $user_id;
				header('Location: includes/signup-full.php?step=1');
			}
		}
	}
?>
<form method="post">
<div class="signup-div">
	<h3>Sign up </h3>
	<ul>
		<li>
		    <input type="text" name="screenName" placeholder="Full Name"/>
		</li>
		<li>
		    <input type="email" name="email" placeholder="Email"/>
		</li>
		<li>
			<input type="password" name="password" placeholder="Password"/>
		</li>
		<li>
			<input type="submit" name="signup" Value="Signup for Twitter">
		</li>
	</ul>
	<?php
		if (isset($error)) {
			echo '<li class="error-li">
			  <div class="span-fp-error">' . $error . '</div>
			 </li>
			';
		}
	?>
</div>
</form>
