<?php
include "main/dbcon.php";
if (isset($_POST['login'])) {
    session_start();
    if (isset($_SESSION['user'])) {
        header("Location: main");
    } else {

        $username = $_POST['user'];
        $password = $_POST['pass'];

        $query = $connection->prepare('select pword from user where uname like ?');
        $query->bind_param('s', $username);
        $query->execute();

        $query = $query->get_result();
        $result = $query->fetch_assoc();
        $hash = $result['pword'];
        if (password_verify($password, $hash)) {
            $_SESSION['user'] = $username;
            header("Location: main");
        } else {
            header("Location: ?error");
        }
    }
}
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body class="container" style="background-image: url(img/backdrop.jpg);background-size: cover">
<div class="page-login"
     style="padding: 10%;background-color: rgba(0,0,0,0.5); background-size: cover;width: 100%;height: 100%">
    <div class="ui centered grid container">
        <div class="nine wide column">
            <div class="ui icon warning message">
                <i class="lock icon"></i>
                <div class="content">
                    <div class="header">
                        Authentication Required!
                    </div>
                    <p>Login To Proceed</p>
                </div>
            </div>
            <div class="ui fluid card">
                <div class="content">
                    <?php if (isset($_GET['error'])) {
                        echo "                    <div class=\"ui icon error message\">
                        <div class=\"content\">
                            <p style='font-weight: bolder'>Invalid Credentials!</p>
                        </div>
                    </div>";

                    } ?>
                    <form class="ui form" method="POST" action="index.php" onsubmit="return checkInput()">
                        <div class="field">
                            <label>User</label>
                            <input type="text" name="user" placeholder="Username">
                        </div>
                        <div class="field">
                            <label>Password</label>
                            <input type="password" name="pass" placeholder="Password">
                        </div>
                        <button class="ui primary labeled icon button" type="submit" name="login">
                            <i class="unlock alternate icon"></i>
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="semantic/semantic.js"></script>
<script>
    $('.ui.form')
        .form({
            fields: {
                user: 'empty',
                pass: 'empty',
            }
        })
    ;
</script>
</body>
</html>