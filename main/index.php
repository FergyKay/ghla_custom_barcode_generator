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


$recordsCount = $connection->query("select count(*) from record");
$results = $recordsCount->fetch_array();
$value = $results[0];
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
    <?php
    if (isset($_GET['err'])) {
        echo '<div class="ui mini modal">
        <i class="close icon"></i>
        <div class="header" style="color: red">Error. Please recheck information
        </div>
    </div>';
    }
    ?>
    <div id="newEntry">
        <h3 style="color: white">Add a record</h3>
        <form class="ui form" action="../core/newRecord.php" method="post">
            <div class="ui input ">
                <input type="text" id="title" placeholder="Record Title" name="title">
            </div>
            <div class="ui input ">
                <input type="text" id="isbn" placeholder="ISBN" name="isbn">
            </div>
            <div class="ui input">
                <input type="text" id="cn" placeholder="Class Number" name="class">
            </div>
            <div class="ui input">
                <input type="number" id="qty" placeholder="Quantity" name="quantity">
            </div>
            <button class="ui button blue" style="float: right" type="submit" id="add" name="add">Save</button>
            <!--            <a class="ui button red" style="float: right" type="" id="cancel">Cancel</a>-->
        </form>
    </div>
    <div>
        <div class="ui grid" style="padding-bottom: 1%">
            <div class="eight wide column">
                <h3 style="color: white">Total records: <?php echo $value ?></h3>
            </div>
            <div class="eight wide column" style="text-align: right">
                <div class="ui search">
                    <div class="ui icon input">
                        <input class="prompt" type="text" id="search-string" placeholder="Search for record by ISBN"
                               onkeyup="myFunction()">
                    </div>
                </div>
            </div>
        </div>
        <div style="overflow-y: auto;height: 70%">
            <table class="ui striped table" id="data-table">
                <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Record Title</th>
                    <th>Class Number</th>
                    <th>Number of Copies</th>
                    <th>Accession number range</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = "Select * from record left join acession on record.record_isbn = acession.record_isbn and record.c_key = acession.c_key order by record.timestamp desc";
                $results = $connection->query($stmt);
                while ($record = $results->fetch_assoc()) {
                    echo '<form action="printout.php" method="get">';
                    echo "<tr style='color:".$record['print_status']."'>";
                    echo "<td>" . $record['record_isbn'] . "</td>";
                    echo "<td>" . $record['record_name'] . "</td>";
                    echo "<td>" . $record['class_number'] . "</td>";
                    echo "<td>" . $record['quantity'] . "</td>";
                    echo "<td>" . sprintf("%09d", $record['accession_start']) . " - " . sprintf("%09d", $record['accession_stop']) . "</td>";
                    echo "<td><a class=\"ui button \" href='printout?rid=" . $record['record_isbn'] . "&as=" . $record['accession_start'] . "&ae=" . $record['accession_stop'] . "&c=" . $record['class_number'] . "&key=".$record['c_key'] ."'><i class=\"fas fa-print\"></i></a>";
                    echo "</tr>";
                    echo '</form>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="../semantic/semantic.js"></script>
<script src="../libs/main.js"></script>
<script>
    $('.ui.form')
        .form({
            fields: {
                title: 'empty',
                quantity: 'empty',
                class: 'empty'
            }
        })
    ;
</script>
<script>
    $('.ui.mini.modal')
        .modal('show')
    ;

    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search-string");
        filter = input.value.toUpperCase();
        table = document.getElementById("data-table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
</body>
</html>


