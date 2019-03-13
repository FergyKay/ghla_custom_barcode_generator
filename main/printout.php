<?php
include "dbcon.php";
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../");
}
if (isset($_GET['rid'])) {
    $rid = $_GET['rid'];
    $class = $_GET['c'];
    $key = $_GET['key'];
    $as = $_GET['as'];
    $ae = $_GET['ae'];
//    ?>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="../semantic/semantic.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <style>
            .item-holder {
                z-index: 3;
                border: 1px solid white;
                border-radius: 1%;
                margin-bottom: 0.5in;
                background: white;
            }

            p {
                font-size: 12pt;
                font-weight: bold;
                margin: 0;
                text-transform: uppercase;
            }

            img {
                height: 20mm;
                width: 50mm;
            }

            body {
                text-align: center;
                padding: 5%;
                background-image: url(../img/backdrop.jpg);
                background-size: cover;
                background-attachment: fixed;
            }

            #print-button {
                position: fixed;
                margin: 10%;
            }

            #print-canvas {
                display: inline-block;
                background: transparent;
                width: 3.5in;
                height: auto;
            }

            @media print {
                @page {
                    margin: 0;
                }

                #nav {
                    visibility: hidden;
                }

                #print-canvas {
                    top: 0;
                    left: 0;
                    margin: 0;
                    visibility: visible;
                    position: absolute;
                    overflow: visible;
                    background: transparent;
                }

                #print-button {
                    visibility: hidden;
                }

                #regen {
                    visibility: hidden;
                }
            }
        </style>
    </head>
    <body class="container" style="padding: 5%">
    <div id="nav">
        <div class="ui top fixed menu inverted " style="background-color: #033600">
            <a class="item" href="index">
                Home
            </a>
            <a class="button teal item right" href="../logoff.php">
                Log Off
            </a>
        </div>
    </div>
    <div id="print-canvas">
        <?php
        $dir = "../barcodes/*";
        foreach (glob($dir) as $file) {
            $filename = explode("/", $file);
            $filename = $filename[2];
            $record = explode("@", $filename);
            $record_number = $record[1];
            $record_id = $record[0];
            $ac_cpy_num = explode(".", $record_number);
            $ac_cpy_num = $ac_cpy_num[0];
            $ac_cpy_num = explode("-", $ac_cpy_num);
            $ac_num = $ac_cpy_num[0];
            $cpy_num = $ac_cpy_num[1];
            $formatted = sprintf("%09d", $ac_num);
            if ($record_id == $rid && $ac_num >= $as && $ac_num <= $ae) {
                echo "<div class='item-holder'>";
                echo '<p>Ghana Library Authority</p>';
                echo '<img src="' . $file . '">';
                echo '<p>' . $class . '</p>';
                echo '<p>' . $formatted . '</p>';
                echo '<p>C. ' . $cpy_num . '</p>';
                echo "</div>";
            }
        }
        ?>
    </div>
    <button class="ui button blue" style="float: right" type="" id="print-button">Print</button>
    <form action="regen" method="post">
        <input type="hidden" name="isbn" value="<?php echo $rid ?>">
        <input type="hidden" name="as" value="<?php echo $as ?>">
        <input type="hidden" name="ae" value="<?php echo $ae ?>">
        <input type="hidden" name="class" value="<?php echo $class ?>">
        <input type="hidden" name="key" value="<?php echo $key ?>">

        <button class="ui button blue" style="float: right" id="regen" type="submit">Regenerate
            Barcodes
        </button>
    </form>
    </body>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script type="text/javascript" src="../libs/main.js"></script>


    <script>
        function doPrint() {
            var val = "<?php echo $key ?>";
            window.location = "printed?key=" + val;
            window.print();
        }

        $('#print-button').on("click", function () {
                doPrint();
            }
        )
        ;
    </script>
    </html>
<?php } else {
    header("Location: index");
}