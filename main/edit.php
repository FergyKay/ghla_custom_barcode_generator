<?php
include "dbcon.php";
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
}
$user = $_SESSION['user'];


$query = $connection->prepare("select uid from user where uname like ?");
$query->bind_param('s', $user);
$query->execute();
$query = $query->get_result();
$uid = $query->fetch_assoc();
$_SESSION['uid'] = $uid['uid'];
$recordIsbn = $_GET['rid'];
$class = $_GET['cl'];
$name = $_GET['n'];
$key = $_GET['key'];
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../semantic/semantic.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body style="margin: 3%;background-image: url(../img/backdrop.jpg);background-size: cover;background-attachment: fixed;overflow: hidden;height: 100%">
<div id="nav">
    <div class="ui top fixed menu inverted " style="background-color: #033600">
        <a class="item" href="index">
            Home
        </a>
        <span class="item" style="background: white;color: #033600"><?php echo $_SESSION['user']; ?></span>
        <a class="button teal item right" href="../logoff.php">Log Off</a>
        <!--        <a  class="button teal item right" id="addRecord">New Record</a>-->
    </div>

</div>
<div id="body" style="padding: 3%;background: rgba(0,0,0,0.7);">
    <div class="ui error message">
        <div class="header">
            BE MINDFUL OF THIS ACTION!!!!!
        </div>
    </div>
    <div class="ui card" style="width: 100%;text-align: center">
        <div class="content"
        ">
        <form class="ui form" action="submit_editted.php" method="post">
            <input type="hidden" name="isbn_old" value="<?php echo $recordIsbn ?>">
            <input type="hidden" name="key" value="<?php echo $key ?>">

            <div class="ui grid" style="text-align: center">
                <form>
                    <div class="ui input four wide column">
                        <input type="number" maxlength="13" id="isbn" value="<?php echo $recordIsbn ?>" name="isbn">
                    </div>
                    <div class="ui input four wide column">
                        <input type="text" id="title" value="<?php echo $name ?>" name="title">
                    </div>
                    <div class="ui input four wide column">
                        <input type="text" id="classnumber" placeholder="Class Number" value="<?php echo $class ?>"
                               name="class">
                    </div>
                    <div class="one wide column">
                        <a href="index" class="ui button red">Cancel</a>
                    </div>
                    <div class="two wide column">
                        <button class="ui button blue" type="submit" id="add" name="add">Save</button>
                    </div>
                </form>
            </div>
            <!--            <a class="ui button red" style="float: right" type="" id="cancel">Cancel</a>-->
        </form>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="../semantic/semantic.js"></script>
<script src="../libs/main.js"></script>
</body>
</html>


