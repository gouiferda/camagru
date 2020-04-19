<?php
ob_start();

require_once('config/env.vars.php');
require_once('app/env.php');
require_once('config/database.php');
require_once('app/functions/database.php');
require_once('app/functions/stickers.php');
require_once('app/functions/utils.php');
require_once('app/functions/validation.php');
require_once('app/functions/routing.php');
require_once('app/functions/render.php');
require_once('app/functions/messages.php');
require_once('app/functions/cookies.php');
require_once('app/functions/theme.php');
require_once('app/functions/image.php');
require_once('app/functions/widgets.php');

function my_autoload($class)
{
    if (preg_match('/\A\w+\Z/', $class)) {
        include('app/classes/' . $class . '.class.php');
    }
}
spl_autoload_register('my_autoload');

$database = db_connect($DB_DSN, $DB_USER, $DB_PASSWORD);
DatabaseObject::set_database($database);

$session = new Session;
if ($session->is_logged_in() && !isset($connected_user)) {
    $connected_user_arr = User::get_where("id", $session->get_user_id());
    $connected_user = User::instantiate($connected_user_arr);
}
if (!$session->is_logged_in()) {
    unset($connected_user);
}

$mailer = new Mailer([
    "from" => ENV_MAIL,
]);
$errors = [];
$site_title = "Camagru";

if (ENV_DEPLOY_MODE == 'prod') {
    error_reporting(0);
}
