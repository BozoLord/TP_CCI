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
        <input type="search" ng-model="search">
        <table class="table">
            <tr>
                <th>Produit</th>
                <th>Prix plus bas</th>
                <th>Artisan bas</th>
                <th>Adresse Artisan</th>
                <th>Prix plus haut</th>
                <th>Artisan haut</th>
                <th>Adresse Artisan</th>
                <th>Différence</th>
            </tr>
            <tr ng-repeat="infoPrd in infosProduits | filter:search track by $index">
                <td><span ng-bind="infoPrd.Nom"></span></td>
                <td><span ng-bind="infoPrd.PrixMin"></span></td>
                <td><span ng-bind="infoPrd.InfosPrixMin.Enseigne"></span></td>
                <td><span ng-bind="infoPrd.InfosPrixMin.Adresse"></span><span ng-bind="infoPrd.InfosPrixMin.Ville"></span></td>
                <td><span ng-bind="infoPrd.PrixMax"></span></td>
                <td><span ng-bind="infoPrd.InfosPrixMax.Enseigne"></span></td>
                <td><span ng-bind="infoPrd.InfosPrixMax.Adresse"></span><span ng-bind="infoPrd.InfosPrixMax.Ville"></span></td>
                <td><span ng-bind="infoPrd.Diff"></span></td>
            </tr>
        </table>
        <div class="infoBulle" ng-show="showInfoBulle == true">
            <p><span ng-bind="infoBulle.Enseigne"></span> <span ng-bind="infoBulle.Adresse"></span> <span ng-bind="infoBulle.Ville"></span></p>
        </div>
    </body>
</html>