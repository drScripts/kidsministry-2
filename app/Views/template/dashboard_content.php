<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" href="<?= base_url('/assets/img/Artboard.png'); ?>">
    <link rel="icon" type="image/png" href="<?= base_url('/assets/img/Artboard.png'); ?>">
    <title>
        GBI PPL Absen <?= (isset($title) ? " | $title" : ' '); ?>
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="<?= base_url(); ?>/assets/default/css/black-dashboard.min.css?v=1.0.0" rel="stylesheet" />

    <script src="<?= base_url(); ?>/assets/default/js/core/jquery.min.js"></script>
    <!--  Google Maps Plugin    -->
    <script src="<?= base_url(); ?>/assets/default/js/plugins/bootstrap-notify.js"></script>
    <script src="<?= base_url(); ?>/assets/demo/demo.js"></script>

<body>
    <button type="button" onclick="showNotify()" class="animate__animated animate__bounceInRight">Show Notify</button>

    <script>
        demo.warningNotification('top', 'right', '<b>Failed Add Absensi</b><br>Check Your Input');
    </script>
</body>

</html>