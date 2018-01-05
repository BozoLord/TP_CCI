var myApp = angular.module('myApp', []);

myApp.controller('homeCtrl', ['$scope', '$http', '$httpParamSerializer', '$sce', function($scope, $http, $httpParamSerializer, $sce){
    var scp = $scope;
    scp.errorMessage = "";
    scp.infosProduits;

    $http({
        method: 'GET',
        url: 'functions.php',
    }).then(function(result) {
        scp.infosProduits = result.data;
        console.log(result.data);
    })

    scp.SubmitCsv = function(){
        var entree = document.getElementById('fichierCsv').files[0];
        if (entree == undefined){
            scp.errorMessage = "Fichier introuvable";
            return;
        }
        var datas = new FormData();
        datas.append('fichier', entree);
        datas.append('form', 'uploadCsv');
        $http({
            method: 'POST',
            url: 'functions.php',
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined},
            enctype: 'multipart/form-data',
            data: datas
        }).then(function(result) {
            console.log(result.data);

        })
    }
    $scope.highlight = function(text, search) {
        if (!search) {
            return $sce.trustAsHtml(text);
        }
        return $sce.trustAsHtml(text.replace(new RegExp(search, 'gi'), '<span class="searchedText">$&</span>'));
    };
}])
