<!DOCTYPE html>
<?php require_once('config.php') ?> 
<html lang="en" ng-app="myApp">
    <head>
        <meta charset="UTF-8">
        <title>TP Augustin et Dylan</title>
        <script src="node_modules/popper.js/dist/umd/popper.js"></script>
        <script src="node_modules/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="node_modules/angular/angular.js"></script>
        <script type="text/javascript" src="angular.js"></script>
        <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
        <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="default.css">
    </head>
    <body ng-controller="homeCtrl">
        <section>
            <h2>Import CSV</h2>
            <p class="errorMessage" ng-bind="errorMessage"></p>
            <form ng-submit="SubmitCsv()" name="csvUploader" enctype="multipart/form-data">
                <input type="file" accept=".csv" id="fichierCsv">
                <input type="submit">
            </form>
        </section>
        
    </body>
</html>