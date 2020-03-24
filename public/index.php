<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use helpers\Session;

function handleExceptions(Exception $exception)
{
    $statusCode = $exception instanceof \exception\BaseException ? $exception->getStatusCode() : 500;
    $msg = $exception->getMessage();
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $msg; ?>
    </div>
    <?php
}

set_exception_handler("handleExceptions");


spl_autoload_register(function ($class) {

    require_once str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";

});
Session::getInstance();
if (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')){ // AJAX HEADER REQUEST

?>

<html lang="en">
<head>
    <link rel="icon" href="icons/favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>eMAG</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<?php
ob_start();
include_once "view/header.php";
}
require "routes/routes.php"; // Routing
?>



