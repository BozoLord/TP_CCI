var myApp = angular.module('myApp', []);

myApp.controller('homeCtrl', ['$scope', '$http', '$httpParamSerializer', function($scope, $http, $httpParamSerializer){
    var scp = $scope;
    scp.errorMessage = "";
    
    $http({
        method: 'GET',
        url: 'functions.php',
    }).then(function(result) {
        console.log(result);
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
}])