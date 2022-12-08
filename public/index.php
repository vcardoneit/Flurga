<?php
$config = parse_ini_file("../app.ini", true);
$frigateIP = $config['config']['ip'];
?>
<html>

<head>
    <title>Flurga</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-italia@2.0.9/dist/css/bootstrap-italia.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</head>

<body>

    <div class="container-fluid bg-primary p-2">
        <div class="row">
            <div class="col-sm text-center">
                <h3 class="text-white">Flurga</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm text-center">
                <a href="events.php" class="text-white">Events</a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form method="post">
            <div class="row justify-content-center align-items-center" style="margin-top:40px">
                <div class="form-group col-md-3">
                    <label class="active" for="giorno">Data</label>
                    <input type="date" id="giorno" name="giorno">
                </div>
                <div class="form-group col-md-3 text-center" style="margin-bottom:0px">
                    <?php
                    $i = 0;
                    while ($config['config']['cameras'][$i] ?? null) {
                        echo ('<div class="form-check form-check-inline">');
                        echo ('<input name="CAM" type="radio" id="' . $config['config']['cameras'][$i] . '" value="' . $config['config']['cameras'][$i] . '" required>');
                        echo ('<label for="' . $config['config']['cameras'][$i] . '">' . $config['config']['cameras'][$i] . '</label>');
                        echo ('</div>');
                        $i++;
                    }
                    ?>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <div class="form-group col-md-3">
                    <label class="active" for="oraInizio">Ora Inizio</label>
                    <input class="form-control" id="oraInizio" name="oraInizio" type="time" required>
                </div>
                <div class="form-group col-md-3">
                    <label class="active" for="oraFine">Ora Fine</label>
                    <input class="form-control" id="oraFine" name="oraFine" type="time" required>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <div class="form-group col-md-3" style="margin-top:-25px">
                    <button type="submit" name="button" formmethod="post" class="btn btn-primary" style="width:100%">Invia</button>
                </div>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['button'])) {
        $data = $_POST['giorno'];
        $oraI = $_POST['oraInizio'];
        $oraF = $_POST['oraFine'];
        $cam = $_POST['CAM'];
        $dataInizio = $data . " " . $oraI;
        $dataFine = $data . " " . $oraF;
        $timestampI = \DateTime::createFromFormat('Y-m-d H:i', $dataInizio)->getTimestamp();
        $timestampF = \DateTime::createFromFormat('Y-m-d H:i', $dataFine)->getTimestamp();
        $link = 'http://' . $frigateIP . '/vod/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/index.m3u8';
        $downLink = 'http://' . $frigateIP . '/' . $cam . '/start/' . $timestampI . '/end/' . $timestampF . '/clip.mp4';
        #echo ($timestampI . " " . $timestampF . " " . $link);
        echo ('<div class="container" style="width:100%;height:50%;padding-bottom:25px">');
        echo ('<video id="my_video_1" class="video-js" controls preload="auto" style="width:100%;height:100%" data-setup="{}">');
        echo ('<source src="' . $link . '" type="application/x-mpegURL">');
        echo ('</video>');
        echo ('<a href="' . $downLink . '" target="_blank" download="a.mp4">Download video</a>');
        echo ('</div>');
    }
    ?>
    <br>

</body>
<script src="https://unpkg.com/bootstrap-italia@2.0.9/dist/js/bootstrap-italia.bundle.min.js"></script>
<script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>

</html>