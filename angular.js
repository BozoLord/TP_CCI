var myApp = angular.module('myApp', []);

myApp.controller('homeCtrl', ['$scope', '$http', '$httpParamSerializer', '$sce', function($scope, $http, $httpParamSerializer, $sce){
    var scp = $scope;
    scp.errorMessage = "";
    scp.infosProduits;
    scp.deepSearch = {
        input : "",
        type : "",
        timer : ""
    }
    scp.deepSearch.input = "";
    scp.deepSearch.type;
    scp.deepSearch.timer;
    scp.deepSearchResultat;
    scp.deepSearchResultatType;

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

    scp.deepSearch = function(){
        if (!scp.deepSearch.type || !scp.deepSearch.input){
            scp.closeDeepSearch();
            return;
        }
        clearTimeout(scp.deepSearch.timer);
        scp.deepSearch.timer = setTimeout(function(){
            // Recherche du contenu 
            if (scp.deepSearch.input.length < 2 ){
                scp.closeDeepSearch();
            }
            var datas = {
                form: "deepSearch",
                type : scp.deepSearch.type,
                input: scp.deepSearch.input
            }
            $http({
                method: 'POST',
                url: 'functions.php',
                data: $httpParamSerializer(datas),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).then(function(result){
                console.log(result.data);
                scp.deepSearchResultat = result.data;
                scp.deepSearchResultatType = datas.type;
            })
        }, 150)
    }
    scp.closeDeepSearch = function(){
        scp.$applyAsync(function(){
            scp.deepSearchResultat = "";
            scp.deepSearchResultatType = "";
        })        
    }
}])
