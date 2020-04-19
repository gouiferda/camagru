<?php

function is_blank($value)
{
  return !isset($value) || trim($value) === '';
}

function has_presence($value)
{
  return !is_blank($value);
}

function has_length_greater_than($value, $min)
{
  $length = strlen($value);
  return $length > $min;
}

function has_length_less_than($value, $max)
{
  $length = strlen($value);
  return $length < $max;
}

function has_length_exactly($value, $exact)
{
  $length = strlen($value);
  return $length == $exact;
}

function has_length($value, $options)
{
  if (isset($options['min']) && !has_length_greater_than($value, $options['min'] - 1)) {
    return false;
  } elseif (isset($options['max']) && !has_length_less_than($value, $options['max'] + 1)) {
    return false;
  } elseif (isset($options['exact']) && !has_length_exactly($value, $options['exact'])) {
    return false;
  } else {
    return true;
  }
}

function has_inclusion_of($value, $set)
{
  return in_array($value, $set);
}

function has_exclusion_of($value, $set)
{
  return !in_array($value, $set);
}

function has_string($value, $required_string)
{
  return strpos($value, $required_string) !== false;
}

function has_valid_email_format($value)
{
  $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
  return preg_match($email_regex, $value) === 1;
}

function has_username_format($value)
{
  $regex = "/^[a-zA-Z0-9]+$/";
  return preg_match($regex, $value) === 1;
}

function has_name_format($value)
{
  $regex = "/^[a-zA-Z\s]+$/";
  return preg_match($regex, $value) === 1;
}

function has_unique_field($fieldname, $value, $current_id = "0")
{
  if ($current_id == "0") {
    $user = User::get_where($fieldname, $value);
  } else {
    $conditions = [];
    $parameters = [];
    $conditions[] = $fieldname . ' LIKE ?';
    $parameters[] = $value;
    $conditions[] = 'id NOT LIKE ?';
    $parameters[] = $current_id;
    $user = User::get_where_conditions($conditions, $parameters);
  }
  if ($user === false) {
    // is unique
    return true;
  } else {
    // not unique
    return false;
  }
}

function has_upper_case($value)
{
  return preg_match('/[A-Z]/', $value);
}

function has_lower_case($value)
{
  return preg_match('/[a-z]/', $value);
}

function has_numbers($value)
{
  return preg_match('/[0-9]/', $value);
}

function has_special_char($value)
{
  $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
  return preg_match($pattern, $value);
}

function is_strong_password($password)
{
  /*
    Characteristics of strong passwords
    At least 8 characters—the more characters, the better
    A mixture of both uppercase and lowercase letters
    A mixture of letters and numbers
    Inclusion of at least one special character, e.g., ! @ # ? ]
    Note: do not use < or > in your password, as both can cause problems in Web browsers
  */
  $result = 1;
  if (has_length_less_than($password, 8))
    $result = 0;
  if (!(has_lower_case($password) && has_upper_case($password)))
    $result = 0;
  if (!has_numbers($password))
    $result = 0;
  if (!has_special_char($password))
    $result = 0;
  return ($result);
}

function field_errors($is_new_value = 0, $field_name, $value, $min, $max)
{
  $password_fields_arr = ['password','newpassword','confirmpassword'];
  $errors = [];
  if (is_blank($value)) {
    $errors[] = $field_name . " can't be empty.";
  }
  if (!is_blank($value) && !has_length($value, ['min' => $min, 'max' => $max]) && $is_new_value && strtolower($field_name) != 'email') {
    $errors[] =  $field_name . " should be at least " . $min . " characters and less than " . $max . " characters.";
  }
  if (!is_blank($value) && strtolower($field_name) == 'email' && !has_valid_email_format($value)) {
    $errors[] = "Email has a bad format.";
  }
  if (!is_blank($value) && !is_strong_password($value) && in_array(strtolower($field_name),$password_fields_arr)) {
    $errors[] = $field_name." not strong (use mixture of uppercase and lowecase, numbers, and special characters)";
  }
  return $errors;
}
