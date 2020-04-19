<?php

class User extends DatabaseObject
{

	static protected $table_name = "users";

	static protected $db_columns = ['id', 'username', 'email', 'password', 'username', 'created_at', 'is_activated', 'first_name', 'last_name', 'activation_code', 'requested_reset', 'reset_code', 'receive_notifications'];

	public $id;
	public $username;
	public $email;
	public $first_name;
	public $last_name;
	public $created_at;
	public $password;
	public $is_activated;
	public $activation_code;
	public $requested_reset;
	public $reset_code;
	public $receive_notifications;

	//receive_notifications

	public function __construct($args = [])
	{
		$this->first_name = $args['first_name'] ?? '';
		$this->last_name = $args['last_name'] ?? '';
		$this->username = $args['username'] ?? '';
		$this->email = $args['email'] ?? '';
		$this->password = $args['password'] ?? '';
		$this->is_activated = $args['is_activated'] ?? '0';
		$this->activation_code = $args['activation_code'] ?? '';
		$this->requested_reset = $args['requested_reset'] ?? '0';
		$this->reset_code = $args['reset_code'] ?? '';
		$this->receive_notifications = $args['receive_notifications'] ?? '1';
	}



	public function validate()
	{
		$this->errors = [];

		if (is_blank($this->first_name)) {
			$this->errors[] = "First name can't be empty";
		} elseif (!has_length($this->first_name, array('min' => 2, 'max' => 255))) {
			$this->errors[] = "First name must be between 2 and 255 characters.";
		}

		if (is_blank($this->last_name)) {
			$this->errors[] = "Last name can't be empty";
		} elseif (!has_length($this->last_name, array('min' => 2, 'max' => 255))) {
			$this->errors[] = "Last name must be between 2 and 255 characters.";
		}

		if (is_blank($this->email)) {
			$this->errors[] = "Email can't be empty.";
		} elseif (!has_length($this->email, array('min' => 10, 'max' => 255))) {
			$this->errors[] = "Email must be less than 255 characters.";
		} elseif (!has_valid_email_format($this->email)) {
			$this->errors[] = "Email must be a valid format.";
		}

		if (is_blank($this->username)) {
			$this->errors[] = "Username can't be empty.";
		} elseif (!has_length($this->username, array('min' => 4, 'max' => 20))) {
			$this->errors[] = "Username must be between 8 and 255 characters.";
		}

		//check for unique username

		// elseif (!has_unique_username($this->username, $this->id ?? 0)) {
		// 	$this->errors[] = "Username not allowed. Try another.";
		// }

		//check if secured password


		return $this->errors;
	}

	public function verify_password($password)
	{
		return password_verify($password, $this->password);
	}

	static public function signin()
	{
		global $errors;
		global $session;
		global $mailer;
		if (is_post_request()) {

			if (isset($_POST['submit'])) {
				//echo "sign in";
				$username = $_POST['username'] ?? '';
				$password = $_POST['password'] ?? '';

				// Validations
				if (is_blank($username)) {
					$errors[] = "Username can't be empty.";
				}
				if (is_blank($password)) {
					$errors[] = "Password can't be empty.";
				}
				// if there were no errors, try to login
				if (empty($errors)) {
					$user_arr = User::get_where("username", $username);
					// echo "user:<br>";
					// echo "<pre>";
					// var_dump($user_arr);
					// echo "</pre>";
					// // test if admin found and password is correct
					if ($user_arr != false) {
						$user = User::instantiate($user_arr);
						if ($user != false && $user->verify_password($password)) {
							if ($user->is_activated == "1") {
								$session->login($user);
								redirect_to(PROJECT_URL . 'home');
							} else {

								//send activation link
								$mailer->sendTo = $user->email;
								$mailer->subject = "Camagru: Activate your account";
								$message = "<h3>Activate your account</h3>";
								$message .= "To activate your account go to this link: ";
								$message .= "<a href='" .PROJECT_URL . 'confirm/' . $user->activation_code. "'>Link</a>";;
								$mailer->message = $message;
								$mailer->send();

								$errors[] = "Account is not activated, we have sent you an email to activate your account!";
							}
						} else {
							$errors[] = "Username not found or password does not match";
						}
					} else {
						$errors[] = "Username not found or password does not match";
					}
				}
			}
		}
	}

	public static function hashed_pass($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public function set_hashed_password()
	{
		$this->password = self::hashed_pass($this->password);
	}




	public function create()
	{
		$this->set_hashed_password();
		return parent::create();
	}

	protected function update()
	{
		return parent::update();
	}



	public function save()
	{
		return parent::save();
	}

	static public function signup()
	{
		global $errors;
		global $session;
		global $mailer;
		if (is_post_request()) {

			if (isset($_POST['submit'])) {
				$username = $_POST['username'] ?? '';
				$firstname = $_POST['firstname'] ?? '';
				$lastname = $_POST['lastname'] ?? '';
				$email = $_POST['email'] ?? '';
				$password = $_POST['password'] ?? '';
				$confirmpassword = $_POST['confirmpassword'] ?? '';

				// Validations
				$errors = array_merge($errors, field_errors(1, "Username", $username, 4, 14));
				$errors = array_merge($errors, field_errors(1, "First name", $firstname, 4, 14));
				$errors = array_merge($errors, field_errors(1, "Last name", $lastname, 4, 14));
				$errors = array_merge($errors, field_errors(1, "Email", $email, 0, 0));
				$errors = array_merge($errors, field_errors(1, "Password", $password, 8, 20));
				if ($password != $confirmpassword) {
					$errors[] = "Password and confirm password don't match.";
				}
				//check if email and username already used
				if (!has_unique_field("username", $username, "0")) {
					$errors[] = "Username already taken.";
				}
				if (!has_unique_field("email", $email, "0")) {
					$errors[] = "Email already taken.";
				}
				//check username format 
				if (!has_username_format($username) && !is_blank($username)) {
					$errors[] = "Bad format for username.";
				}
				//check first name format 
				if (!has_name_format($firstname)  && !is_blank($firstname)) {
					$errors[] = "Bad format for first name.";
				}
				//check last name format 
				if (!has_name_format($lastname)  && !is_blank($lastname)) {
					$errors[] = "Bad format for last name.";
				}
				if (empty($errors)) {
					$activation_code = self::gen_activation_code($email);
					$new_user = new User([
						"username" => $username,
						"first_name" => $firstname,
						"last_name" => $lastname,
						"email" => $email,
						"password" => $password,
						"is_activated" => '0',
						"receive_notifications" => '1',
						"activation_code" => $activation_code
					]);
					if ($new_user->save()) {

						//$session->message("User created, you can now sign in");

						$_SESSION['confirm_email'] = $email;

						$mailer->sendTo = $email;
						$mailer->subject = "Camagru: Activate your account";
						$message = "<h3>Activate your account</h3>";
						$message .= "To activate your account go to this link: ";
						$message .= "<a href='" . PROJECT_URL . 'confirm/' . $activation_code . "'>Link</a>";;
						$mailer->message = $message;
						$mailer->send();

						redirect_to(PROJECT_URL . 'confirm');
					} else {
						$session->message("Error creating user");
					}
				}
			}
		}
	}

	public static function save_profile()
	{
		global $errors;
		global $session;
		global $connected_user;
		if (is_post_request()) {

			if (isset($_POST['submit'])) {
				$username = $_POST['username'] ?? $connected_user->username;
				$firstname = $_POST['firstname'] ?? $connected_user->first_name;
				$lastname = $_POST['lastname'] ?? $connected_user->last_name;
				$email = $_POST['email'] ?? $connected_user->email;
				$rn = $_POST['receivenotifications'] ?? $connected_user->receive_notifications;
				$rn = ($rn == 'on') ? '1' : '0';
				$receive_notifications = $rn ?? '0';
				//die($receive_notifications);
				$currentpassword = $_POST['currentpassword'] ?? '';
				$newpassword = $_POST['newpassword'] ?? '';
				$repeatnewpassword = $_POST['repeatnewpassword'] ?? '';

				// // Validations
				$errors = array_merge($errors, field_errors(1, "Username", $username, 4, 14));
				$errors = array_merge($errors, field_errors(1, "First name", $firstname, 4, 14));
				$errors = array_merge($errors, field_errors(1, "Last name", $lastname, 4, 14));
				$errors = array_merge($errors, field_errors(1, "Email", $email, 0, 0));
				//check if email and username already used
				if (!has_unique_field("username", $username, $connected_user->id)) {
					$errors[] = "Username already taken.";
				}
				if (!has_unique_field("email", $email, $connected_user->id)) {
					$errors[] = "Email already taken.";
				}

				//check username format 
				if (!has_username_format($username) && !is_blank($username)) {
					$errors[] = "Bad format for username.";
				}
				//check first name format 
				if (!has_name_format($firstname)  && !is_blank($firstname)) {
					$errors[] = "Bad format for first name.";
				}
				//check last name format 
				if (!has_name_format($lastname)  && !is_blank($lastname)) {
					$errors[] = "Bad format for last name.";
				}

				if ($currentpassword != '') {

					$pass_match = password_verify($currentpassword, $connected_user->password);

					if ($pass_match) {
						$errors = array_merge($errors, field_errors(1, "newpassword", $newpassword, 8, 20));
						if ($newpassword != $repeatnewpassword) {
							$errors[] = "New password and confirm new password don't match. ";
						}
						if (password_verify($newpassword, $connected_user->password)) {
							$errors[] = "Choose a new password";
						}
					} else {
						$errors[] = "Current password is incorrect ";
					}
				}
				if (empty($errors)) {
					$new_user = new User([
						"username" => $username,
						"first_name" => $firstname,
						"last_name" => $lastname,
						"is_activated" => $connected_user->is_activated,
						"email" => $email,
						"password" => $connected_user->password,
						"requested_reset" => $connected_user->requested_reset,
						"receive_notifications" => $receive_notifications
					]);
					$new_user->id = $connected_user->id;
					if ($currentpassword != '') {
						//$new_user->password = self::hashed_pass($newpassword);
						$new_user->password = self::hashed_pass($newpassword);
					}
					if ($new_user->save()) {
						$connected_user_arr = self::get_where("id", $connected_user->id);
						$connected_user = self::instantiate($connected_user_arr);
						$session->message("Profile information saved");
					} else {
						$session->message("Error changing profile");
					}
				}
			}
		}
	}

	public static function gen_activation_code($email)
	{
		return substr(hash('whirlpool', 'activation' . $email), 0, 10);
	}

	public static function gen_reset_code($email)
	{
		return substr(hash('whirlpool', 'reset' . $email), 0, 10);
	}

	public static function is_account_activated($email)
	{
		if (isset($email)) {
			$user =  User::get_where("email", $email);
			if ($user != false) {
				$user_obj = User::instantiate($user);
				if ($user_obj->is_activated == "1")
					return true;
				else
					return false;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function full_name()
	{
		return ucwords(strtolower($this->first_name)) . " " . ucwords(strtolower($this->last_name));
	}


	/*

	static public function find_all() {
		$sql = "SELECT * FROM " . static::$table_name." ORDER BY last_name ASC;";
		return static::query($sql);
	}

	
	*/
}
