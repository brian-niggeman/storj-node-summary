<!doctype html>
<html lang="en">
    <head>
        <style>
            #disk {
                background-color: grey;
            }

            #diskused {
                width: 0%;
                height: 30px;
                background-color: green;
            }
        </style>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script>
        </script>
        <title>Storj Node Summary</title>
    </head>
    <body>

        <div class="container">
            <br>
            <h3>Node Summary</h3>
            <br>
            <?php
            // -----------------------  settings ----------------
            // set total capacity
            $Capacity = "2000";

            // set up nodes here
            $nodes = array(
                "1" => "192.168.1.93",
                "2" => "192.168.1.158",
                "3" => "192.168.1.94",
                "4" => "192.168.1.160",
            );
            // --------------------------------------------------
            function getNodeMainJson($ip) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://$ip:14002/api/sno/");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                return $output;
            }

            echo "<table class='table'>";
            echo "<tr><th>Node</th><th>Used</th><th>IP</th><th>nodeID</th></tr>";

            $total = 0;

            foreach ($nodes as $node => $ip) {
                $data = json_decode(getNodeMainJson($ip));
                $nodeID = $data->nodeID;
                $used = round($data->diskSpace->used / 1000 / 1000 / 1000, 4);
                echo "<tr><td>$node</td><td>$used GB</td><td><a href='http://$ip:14002'>$ip</a></td><td>$nodeID</td></tr>";
                $total = $total + $used;
            }

            $Percent = round(($total / $Capacity) * 100, 4);

            echo "</table>";

            echo "<div class=\"alert alert-success\" role=\"alert\"><h4 class=\"alert-heading\">Total: $total GB</h4></div>";
            echo "<div class=\"alert alert-info\" role=\"alert\"><h4 class=\"alert-info\">Used: $Percent% / $Capacity GB</h4></div>";

            ?>
            <h5>Total Used</h5>
            <div id="disk">
                <div id="diskused"></div>
            </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script>
            var percent = "<?php echo $Percent; ?>";
            $('#diskused').css('width',percent + '%');
        </script>
    </body>
</html>



