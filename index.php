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
        <script src="script.js" charset="utf-8"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" charset="utf-8"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" charset="utf-8"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    </head>
    <body ng-controller="homeCtrl" ng-init="load()">
        <header ng-cloak>
            <ul class="navbar navbar-inverse navbar-light mr-auto mt-2 mt-lg-0" style="background-color: #e3f2fd;">
                <li class="nav-item" ng-class="{'activeButton' : displayedSection == 'csv'}">
                    <a class="nav-link" ng-click="displayedSection = 'csv'">Import CSV</a>
                </li>
                <li class="nav-item" ng-class="{'activeButton' : displayedSection == 'main'}">
                    <a class="nav-link" ng-click="displayedSection = 'main'">Tableau Principal</a>
                </li>
                <li class="nav-item" ng-class="{'activeButton' : displayedSection == 'resultat', 'linkDisabled' : infoDetailsPage.details == '' || !infoDetailsPage.details}">
                    <a class="nav-link" ng-click="displayedSection = 'resultat'">Resultat</a>
                </li>
                <form class="form-inline deepSearchForm">
                    <input type="search" placeholder="Produit, Artisan, Ville" class="form-control mr-sm-2 inputDeep" ng-model="deepSearch.input" ng-keyup="deepSearch()" ng-blur="closeDeepSearch()" ng-click="deepSearch()" ng-disabled="!deepSearch.type">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" ng-disabled="!deepSearch.type" >Chercher</button>
                    <div class="deepSearchChoixContainer">
                        <input type="radio" id="artisan" name="typeSearch" value="Magasins" ng-model="deepSearch.type">
                        <label for="artisan">Artisan</label>
                        <input type="radio" id="patisserie" name="typeSearch" value="Produits" ng-model="deepSearch.type">
                        <label for="patisserie">Patisserie</label>
                        <input type="radio" id="ville" name="typeSearch" value="Villes" ng-model="deepSearch.type">
                        <label for="ville">Ville</label>
                    </div>
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
            <table class="table table-hover" >
              <thead>
                <tr class="thead-dark">
                    <th>Produit</th>
                    <th>Prix plus bas</th>
                    <th>Artisan bas</th>
                    <th>Adresse Artisan</th>
                    <th>Prix plus haut</th>
                    <th>Artisan haut</th>
                    <th>Adresse Artisan</th>
                    <th>Différence</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="infoPrd in infosProduits | filter:search track by $index" >
                    <td><span class="clickable" ng-bind-html="highlight(infoPrd.Nom, search)" ng-click="$parent.displayDetailsFromDeepSearch(infoPrd.ID_Produit, 'Produits')"></span>
                    </td>
                    <td><span ng-bind-html="highlight(infoPrd.PrixMin, search)"></span>
                    </td>
                    <td><span class="clickable" ng-bind-html="highlight(infoPrd.InfosPrixMin.Enseigne, search)" ng-click="$parent.displayDetailsFromDeepSearch(infoPrd.InfosPrixMin.ID_Artisan, 'Magasins')"></span>
                    </td>
                    <td><span ng-bind-html="highlight(infoPrd.InfosPrixMin.Adresse, search)"></span><span class="split">|</span><span ng-bind-html="highlight(infoPrd.InfosPrixMin.Ville, search)"></span>
                    </td>
                    <td><span ng-bind-html="highlight(infoPrd.PrixMax, search)"></span></td>
                    <td><span class="clickable" ng-bind-html="highlight(infoPrd.InfosPrixMax.Enseigne, search)" ng-click="$parent.displayDetailsFromDeepSearch(infoPrd.InfosPrixMax.ID_Artisan, 'Magasins')"></span></td>
                    <td><span ng-bind-html="highlight(infoPrd.InfosPrixMax.Adresse, search)"></span><span class="split">|</span><span ng-bind-html="highlight(infoPrd.InfosPrixMax.Ville, search)"></span></td>
                    <td><span ng-bind-html="highlight(infoPrd.Diff, search)"></span></td>
                </tr>
              </tbody>
            </table>
        </section>
        <section ng-show="displayedSection == 'resultat'" class="resultatSeek" ng-cloak>
            <article ng-show="infoDetailsPage.type == 'Villes' || infoDetailsPage.type == 'Magasins'">
                <h2 class="text-center"><span ng-bind="infoDetailsPage.details[0].Enseigne"></span>
                    <span class="split">|</span>
                    <span ng-bind="infoDetailsPage.details[0].Adresse"></span>
                    <span class="split">|</span>
                    <span ng-bind="infoDetailsPage.details[0].villeNom"></span>
                </h2>
                <table class="table table-hover" >
                  <thead>
                    <tr class="thead-dark">
                        <th>Produits</th>
                        <th>Prix</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="infoPrd in infoDetailsPage.details">
                        <td><span class="clickable" ng-bind="infoPrd.Nom" ng-click="$parent.displayDetailsFromDeepSearch(infoPrd.ID_Produit, 'Produits')"></span>
                        </td>
                        <td><span ng-bind="infoPrd.Prix"></span>
                        </td>
                    </tr>
                  </tbody>
                </table>
            </article>
            <article ng-show="infoDetailsPage.type == 'Produits'">
                <h2 class="text-center"><span ng-bind="infoDetailsPage.details[0].prdtNom"></span></h2>
                <table class="table table-hover" >
                  <thead>
                    <tr class="thead-dark">
                        <th>Enseignes</th>
                        <th>Adresse</th>
                        <th>Villes</th>
                        <th>Prix</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr ng-repeat="infoPrd in infoDetailsPage.details | filter:search track by $index" >
                        <td><span class="clickable" ng-bind="infoPrd.Enseigne" ng-click="$parent.displayDetailsFromDeepSearch(infoPrd.FK_ID_Magasin, 'Magasins')"></span>
                        </td>
                        <td><span class="clickable" ng-bind="infoPrd.Adresse" ng-click="$parent.displayDetailsFromDeepSearch(infoPrd.FK_ID_Magasin, 'Magasins')"></span>
                        </td>
                        <td><span ng-bind="infoPrd.Nom" ></span>
                        </td>
                        <td><span ng-bind="infoPrd.Prix"></span>
                        </td>
                    </tr>
                  </tbody>
                </table>
            </article>
        </section>
        <section ng-show="deepSearchResultat.length > 0">
            <article ng-show="deepSearchResultat.length > 0 && deepSearchResultatType == 'Villes' && deepSearch.input.length > 2"  class="deepSearchResultatPopup" ng-cloak>
                <p class="titleResultat" ng-bind="deepSearchResultat[0].Nom"></p>
                <div ng-repeat="info in deepSearchResultat track by $index" class="infoDeepSearch" ng-click="$parent.displayDetailsFromDeepSearch(info.ID_Magasin, 'Villes')">
                    <p ng-bind="info.Enseigne"></p><span class="split">|</span><p ng-bind="info.Adresse"></p>
                </div>
            </article>
            <article ng-show="deepSearchResultat.length > 0 && deepSearchResultatType == 'Produits' && deepSearch.input.length > 2"  class="deepSearchResultatPopup" ng-cloak>
                <p class="titleResultat" ng-bind="deepSearch.input"></p>
                <div ng-repeat="info in deepSearchResultat track by $index" class="infoDeepSearch" ng-click="$parent.displayDetailsFromDeepSearch(info.ID_Produit, 'Produits')">
                    <p ng-bind="info.Nom"></p><span class="split">|</span><p class="lowestPrice" ng-bind="info.prixMin"></p><span class="split">|</span><p class="highestPrice"ng-bind="info.prixMax"></p>
                </div>
            </article>
            <article ng-show="deepSearchResultat.length > 0 && deepSearchResultatType == 'Magasins' && deepSearch.input.length > 2"  class="deepSearchResultatPopup" ng-cloak>
                <p class="titleResultat" ng-bind="deepSearchResultat[0].Enseigne"></p>
                <div ng-repeat="info in deepSearchResultat track by $index" class="infoDeepSearch" ng-click="$parent.displayDetailsFromDeepSearch(info.ID_Magasin, 'Magasins')">
                    <p ng-bind="info.Adresse"></p><span class="split">|</span><p ng-bind="info.Nom"></p>
                </div>
            </article>
        </section>
    </body>
</html>
