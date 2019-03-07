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
    <div id="newEntry">
        <h3 style="color: white">Add a record</h3>
        <form class="ui form" action="../core/newRecord.php" method="post">
            <div class="ui grid">
                <div class="ui input seven wide column">
                    <input type="text" id="title" placeholder="Record Title" name="title">
                </div>
                <div class="ui input four wide column ">
                    <input type="number" maxlength="13" minlength="10" size="13" id="isbn" placeholder="ISBN"
                           name="isbn">
                </div>
                <div class="ui input two wide column">
                    <input type="text" id="cn" placeholder="Class Number" name="class">
                </div>
                <div class="ui input two wide column">
                    <input type="number" min="1" id="qty" placeholder="Quantity" name="quantity">
                </div>
                <div class="one wide column">
                    <button class="ui button blue" style="float: right" type="submit" id="add" name="add">Save</button>
                </div>
            </div>
            <!--            <a class="ui button red" style="float: right" type="" id="cancel">Cancel</a>-->
        </form><?php if (isset($_GET['sup'])) {
            echo '<div id="message" class="ui error message transition ">
            <i class="close icon"></i>
            <div class="header">
                There were some errors with your submission
            </div>
            <ul class="list">
                <li>Record Already Exists.</li>
                <li>If this is an additional copy of an already existing record, kindly use the add button attached to the record to add extra copies</li>
            </ul>
        </div>
    </div>
    <br>';

        }
        if (isset($_GET['err'])) {
            echo '<div id="message" class="ui error message">
            <i class="close icon"></i>
            <div class="header">
                An Error Occured
            </div>
        </div>
    <br>';
        }
        if (isset($_GET['success'])) {
            echo '<div id="message" class="ui success message">
            <i class="close icon"></i>
            <div class="header">
                Update Successful
            </div>
        </div>
    <br>';
        }

        ?>
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
                        <th>Print</th>
                        <th>Edit</th>
                        <th>Add</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stmt = "Select * from record left join acession on record.record_isbn = acession.record_isbn and record.c_key = acession.c_key order by record.timestamp desc";
                    $results = $connection->query($stmt);
                    while ($record = $results->fetch_assoc()) {

                        echo "<tr id='" . $record['record_isbn'] . ":" . $record['c_key'] . "' style='color:" . $record['print_status'] . "'>";
                        echo "<td>" . $record['record_isbn'] . "</td>";
                        echo "<td>" . $record['record_name'] . "</td>";
                        echo "<td>" . $record['class_number'] . "</td>";
                        echo "<td>" . $record['quantity'] . "</td>";
                        echo "<td>" . sprintf("%09d", $record['accession_start']) . " - " . sprintf("%09d", $record['accession_stop']) . "</td>";
                        echo "<td><a class=\"ui button \" href='printout?rid=" . $record['record_isbn'] . "&as=" . $record['accession_start'] . "&ae=" . $record['accession_stop'] . "&c=" . $record['class_number'] . "&key=" . $record['c_key'] . "'><i class=\"fas fa-print\"></i></a>";
                        echo "<td><a id='editbtn' class=\"ui button \" href='edit?rid=" . $record['record_isbn'] . "&key=" . $record['c_key'] . "&cl=" . $record['class_number'] . "&n=" . $record['record_name'] . "'><i class=\"fas fa-pen\"></i></a>";
                        echo "<td style='white-space: nowrap' id='q'><a href='add?rid=" . $record['record_isbn'] . "&key=" . $record['c_key'] . "&cl=" . $record['class_number'] . "&n=" . $record['record_name'] . "' class=\"ui button\"><i class=\"fas fa-plus\"></i></a></td>";
                        echo "</tr>";

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--    <div id="edit" class="ui mini modal">-->
    <!--        <div class="ui segment">-->
    <!--            <div class="ui  form">-->
    <!--                <div class="one field">-->
    <!--                    <div class="field">-->
    <!--                        <label>ISBN: 12345678</label>-->
    <!--                    </div>-->
    <!--                    <div class="field">-->
    <!--                        <label>Title</label>-->
    <!--                        <input placeholder="Title" type="text">-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="field">-->
    <!--                    <label>Class</label>-->
    <!--                    <input placeholder="Class Number" type="text">-->
    <!--                </div>-->
    <!--                <div class="ui submit button green center">Update</div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
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
    $('.close')
        .on('click', function () {
            document.getElementById('message').style.display = "none";
        })
    ;

    $('.fa-pen').on('click', function () {

        if ($(this).hasClass('fa-pen')) {
            $(this).removeClass('fa-pen').addClass('fa-save');
        } else {
            $(this).removeClass('fa-save').addClass('fa-pen');
        }
        var trow = $(this).parent().parent().parent();
        var rid = $(this).parent().parent().parent().attr('id');
        console.log(rid);
    });

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


