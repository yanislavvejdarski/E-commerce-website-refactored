<?php
namespace view;

?>

<body>
<div class="container">

    <h1>Enter your email :</h1>
    <br>
    <?php
    if (isset($msg) && $msg!=""){
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $msg; ?>
        </div>
        <?php
    } ?>

    <form action="index.php?target=User&action=sendNewPassword" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1"></label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">

            <input type="submit" class="btn btn-primary" name="forgotPassword" value="Send new password">
        </div>
    </form>

</div>
</body>
