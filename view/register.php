<?php
namespace view;
?>



<body>

<?php
if (isset($msg) && $msg!=""){
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $msg;?>
    </div>
    <?php
} ?>


<div class="container">
    <h1>Register here:</h1>
    <form action="index.php?target=User&action=register" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>

        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Confirm Password</label>
            <input name="confirmPassword" type="password" class="form-control" id="exampleInputPassword1" required>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">First Name</label>
            <input type="text" name="first_name" class="form-control" required>

        </div>
        <div class="form-group">
            <label >Last Name</label>
            <input type="text" name="last_name" class="form-control" required>

        </div>
        <div class="form-group">
            <label >Age</label>
            <input type="number" name="age" class="form-control" min="0" max="100" required>

        </div>
        <div class="form-group">
            <label >Phone number</label>
            +359<input type="number" name="phone_number" class="form-control" placeholder="8## ### ###" required>

        </div>
        <div class="form-group">
            <label >Subscribe for notification about latest promotions:</label>
            <input type="checkbox" name="subscription">

        </div>

        <button name="register" type="submit" class="btn btn-primary">Submit</button>
    </form>

    <a href="index.php?target=user&action=loginPage"><button name="register" type="submit" class="btn btn-primary"><-- Back to login page</button></a>
</div>
</body>

