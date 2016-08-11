<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel = "stylesheet" type = "text/css" href = "<?php echo $pageCSS?>">
    <script type = "application/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script type = "application/javascript" src = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type = "application/javascript" src = "<?php echo $pageScript?>"></script>
</head>
<body>
    <div id = "wrapper">
        <header>
            <div class = "row">
                <div class = "col-md-4">
                    <h2><?php echo $pageTitle; ?></h2>
                </div>
                <div class = "col-md-4 col-md-offset-4">
                    <!--<p>Signed in as tnguyen</p>-->
                </div>
            </div
        </header><!--End of Header-->
    </div>
    <nav class = "nav navbar-default">
        <div class = "container">
            <ul class = "nav navbar-nav">
                <li><a href = "../home.php">Home</a></li>
                <!--<li><a href = "../management/sale.php">Sale</a></li>-->
                <!--<li><a href = "#">Gift Certificate</a></li>-->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle = "dropdown" role="button"
                       aria-haspopup="true" aria-expanded="false">Technician <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../technicians/tech-sale-entry.php">Daily Sale Entry</a></li>
                    </ul>

                </li>
                <li><a href = "../technicians/payday.php">Pay Day</a></li>
            </ul>
            <ul class = "nav navbar-nav navbar-right">
                <li><a class = "pull-right" href = "../signout.php">Sign Out</a></li>
            </ul>
        </div>
    </nav>








