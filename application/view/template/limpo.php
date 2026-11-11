<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
    <link rel="icon" href="../view/img/logo_estado_da_bahia.png" type="image/png">

    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/fonts/poppins/poppins.css" rel="stylesheet">
    <link href="/assets/portal.css" rel="stylesheet">
</head>

<body class="bg-body-secondary d-flex flex-column min-vh-100">




    <?= $content ?>



    <!-- Bootstrap 5 JS Bundle -->
    <script src="/assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>

</html>
