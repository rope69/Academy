<?php
session_start();

if(isset($_POST["ign"])  && isset($_POST["pass"]) && isset($_POST["role"]) && isset($_POST["job"]) && isset($_POST["iLvl"]) && isset($_POST["statement"])){

    require_once("params.php");

    $con = mysqli_connect($host, $user, $pass, $db);

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", "Connect failed.");	//mysqli_connect_error()
        exit();
    }


    $sql = "INSERT INTO stm (ign, role, iLvl, statement, job, password) VALUES (?, ?, ?, ?, ?, ?)";
    if(!$stmt = mysqli_prepare($con, $sql)){
        echo mysqli_error($con);
        return;
    }

    $intlvl = (int)$_POST["iLvl"];

    mysqli_stmt_bind_param($stmt, "ssisss", $_POST["ign"], $_POST["role"], $intlvl, $_POST["statement"], $_POST["job"], $_POST["pass"]);

    if(mysqli_stmt_execute($stmt)){
        $_SESSION["user"] = $_POST["ign"];
        header("location:index.php");
    }else{
        die("<h1>Could not sign you in.</h1>" .  mysqli_error($con));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}

if(isset($_POST["logInIgn"]) && isset($_POST["password"])){

    require_once("params.php");

    $con = mysqli_connect($host, $user, $pass, $db);

    if (mysqli_connect_errno()) {
        die("Connect failed:" . mysqli_connect_error());
    }


    $sql = "SELECT ign, password FROM stm WHERE ign = ?";
    $stmt = mysqli_prepare($con, $sql);

    $intlvl = (int)$_POST["iLvl"];

    mysqli_stmt_bind_param($stmt, "s", $_POST["logInIgn"]);

    if(mysqli_stmt_execute($stmt)){

        $result = mysqli_stmt_get_result($stmt);
        var_dump($result);
        $res = mysqli_fetch_assoc($result);
        var_dump($res);

        if($res["ign"] == $_POST["logInIgn"] &&  $res["password"] == $_POST["password"]){
            $_SESSION["user"] = $_POST["logInIgn"];
            header("location:index.php");
        }else{
            die("Wrong username and/or password");
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

    }else{
        die("<h1>Could not log you in.</h1>" .  mysqli_error($con));
    }

}

?>



<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Senpai">
        <title>Evergrande static</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    </head>

    <body>

        <nav class="navbar navbar-inverse">
            <div class="container-fluid">

                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Gallery</a></li>
                        <li><a href="#">Sign in</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="container">

            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#login" data-toggle="tab">Log in</a></li>
                <li class="nav"><a href="#signup" data-toggle="tab">Sign up</a></li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane fade in active" id="login">
                    <h2> Log in </h2>
                    <form class="form-horizontal" method="post">
                        <div class="form-group">
                            <label for="logInIgn" class="control-label col-sm-2">In-game name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="logInIgn" id="logInIgn" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label col-sm-2">Password:</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Log in" class="btn btn-default">
                            </div>
                        </div>
                    </form>
                </div>


                <div class="tab-pane fade" id="signup">
                    <h2> Sign up </h2>
                    <form class="form-horizontal" method="post">

                        <div class="form-group">
                            <label for="ign" class="control-label col-sm-2">In-game name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="ign" id="ign" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pass" class="control-label col-sm-2">Password:</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="pass" id="pass" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role" class="control-label col-sm-2">Role</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="role" id="role" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job" class="control-label col-sm-2">Job</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="job" id="job" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="iLvl" class="control-label col-sm-2">Item level</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="iLvl" id="iLvl" placeholder="320" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="statement" class="control-label col-sm-2">Your note</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="statement" id="statement">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Sign in" class="btn btn-default">
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div> <!-- container -->



        <script src="js/jquery.min.js"></script>

        <!-- Latest compiled and minified CSS
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

        <!-- Optional theme
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

        -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="bootstrap/js/bootstrap.min.js"></script>

    </body>

</html>

