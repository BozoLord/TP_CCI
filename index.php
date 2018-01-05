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
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
        <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="default.css">

    </head>
    <body ng-controller="homeCtrl" ng-init="displayedSection = 'main'">
        <header>
            <ul class="navbar navbar-inverse navbar-light mr-auto mt-2 mt-lg-0" style="background-color: #e3f2fd;">
                <li class="nav-item" ng-class="{'activeButton' : displayedSection == 'csv'}">
                    <a class="nav-link" ng-click="displayedSection = 'csv'">Import CSV</a>
                </li>
                <li class="nav-item" ng-class="{'activeButton' : displayedSection == 'main'}">
                    <a class="nav-link" ng-click="displayedSection = 'main'">Tableau Principal</a>
                </li>
                <li class="nav-item" ng-class="{'activeButton' : displayedSection == 'resultat'}">
                    <a class="nav-link" ng-click="displayedSection = 'resultat'">Resultat</a>
                </li>
                <form class="form-inline ">
                    <input type="search" placeholder="Produit, Artisan, Ville" class="form-control mr-sm-2">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Chercher</button>

                </form>
            </ul>
        </header>
        <section ng-show="displayedSection == 'csv'" ng-cloak>
            <h2>Import CSV</h2>
            <p class="errorMessage" ng-bind="errorMessage"></p>
            <form ng-submit="SubmitCsv()" name="csvUploader" enctype="multipart/form-data">
                <input type="file" accept=".csv" id="fichierCsv" class="btn btn-default">
                <input type="submit" class="btn btn-default">
            </form>
        </section>
        <section ng-show="displayedSection == 'main'" ng-cloak>
            <div class="form-group row text-right">
                <label for="searchInMain" class="col-form-label">Recherchez dans le tableau</label>
                <div class="col-5">
                    <input id="searchInMain" class="form-control" type="search" ng-model="search">
                </div>
            </div>
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
                <tr ng-repeat="infoPrd in infosProduits | filter:search track by $index" >
                    <td><span  ng-bind-html="highlight(infoPrd.Nom, search)"></span>
                    </td>
                    <td><span ng-bind-html="highlight(infoPrd.PrixMin, search)"></span>
                    </td>
                    <td><span ng-bind-html="highlight(infoPrd.InfosPrixMin.Enseigne, search)"></span>
                    </td>
                    <td><span ng-bind-html="highlight(infoPrd.InfosPrixMin.Adresse, search)"></span><span class="split">|</span><span ng-bind-html="highlight(infoPrd.InfosPrixMin.Ville, search)"></span>
                    </td>
                    <td><span ng-bind-html="highlight(infoPrd.PrixMax, search)"></span></td>
                    <td><span ng-bind-html="highlight(infoPrd.InfosPrixMax.Enseigne, search)"></span></td>
                    <td><span ng-bind-html="highlight(infoPrd.InfosPrixMax.Adresse, search)"></span><span class="split">|</span><span ng-bind-html="highlight(infoPrd.InfosPrixMax.Ville, search)"></span></td>
                    <td><span ng-bind-html="highlight(infoPrd.Diff, search)"></span></td>
                </tr>
            </table>
        </section>
        <section ng-show="displayedSection == 'resultat'" ng-cloak>
        </section>
    </body>
</html>