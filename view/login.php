<?php
namespace view;

?>

<body>
<div class="container">

    <h1>Login here:</h1>
    <br>
    <form action="index.php?target=User&action=login" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">

        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1" >
        </div>

        <button name="login" type="submit" class="btn btn-primary">Login</button>
    </form>
    Don't have an account? Register here: <br>
    <a href="index.php?target=User&action=registerPage"><button name="register" type="submit" class="btn btn-primary">Register</button></a><br><br>
    <a href="index.php?target=User&action=forgottenPassword"><button type="submit" class="btn btn-primary">Forgot your password ?</button></a>
</div>
</body>
