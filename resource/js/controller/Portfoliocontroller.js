/**
 * Created by psybo-03 on 29/11/16.
 */

//Portfolio Controller

app.controller('portfolioController', function ($scope, $location, $http, $rootScope) {



    $scope.newportfolio = {};
    $scope.portfolios = [];
    $scope.curportfolio = {};
    $scope.message = {};
    $scope.error = {};
    $scope.showform = true;

    $scope.regex = '^((https?|ftp)://)?([A-Za-z]+\\.)?[A-Za-z0-9-]+(\\.[a-zA-Z]{1,4}){1,2}(/.*\\?.*)?$';

    loadPortfolio();

    function loadPortfolio() {
        $http.get($rootScope.base_url +'/Portfolio_Controller/get').then(function(response) {
            $scope.portfolios = response.data;
            console.log($scope.portfolios);
        })
    };

    //show the form
    $scope.showForm = function (item) {
        $scope.showform = true;
        $scope.curportfolio = item;
        $scope.newportfolio = angular.copy(item);
    };

    //Hide the form
    $scope.hideForm=function() {
        $scope.showform = false;
    };

    $scope.newPortfolio = function() {
        $scope.newportfolio = {};
        $scope.showform = true;
    };

    //Add
    $scope.addPortfolio = function () {

        console.log($scope.newportfolio.desktop);

        var formData = new FormData();
        formData.append('test', $scope.newportfolio.desktop);
        console.log(formData);

        $http({
            method: 'post',
            url: $rootScope.base_url + '/Portfolio_Controller/store',
            //data: $scope.newportfolio,
            data: formData,
            //header: {'Content-type': 'application/x-www-form-urlencoded'}
            header: {'Content-type': 'undefined'}
        }).success(function (data, status, headers) {
            console.log(headers);
            //$scope.portfolios.push(data);
            //loadPortfolio();
            //$scope.newportfolio = {};
            //$scope.showform = false;
        }).error(function (data, status, headers) {
            console.log(data);
            /*console.log('header');
             if (data['error']) {
             alert(data['error']);
             }*/
        });

        //add protocol to link
        /*var string = $scope.newportfolio.link;
         if (!~string.indexOf("http")) {
         $scope.newportfolio.link = "http://" + string;
         }

         if ($scope.newportfolio['id']) {
         console.log('edit');
         var id = $scope.newportfolio['id'];
         $http({
         method: 'post',
         url: $rootScope.base_url + '/Portfolio_Controller/edit_record',
         data: $scope.newportfolio,
         header: {'Content-type': 'application/x-www-form-urlencoded'}
         }).success(function (data, status, headers) {
         $scope.portfolios.push(data);
         loadPortfolio();
         $scope.newportfolio = {};
         $scope.showform = false;
         }).error(function (data, status, headers) {
         if (data['error']) {
         alert(data['error']);
         }
         });
         }else{
         $http({
         method: 'post',
         url: $rootScope.base_url + '/Portfolio_Controller/store',
         data: $scope.newportfolio,
         header: {'Content-type': 'application/x-www-form-urlencoded'}
         }).success(function (data, status, headers) {
         console.log(headers);
         $scope.portfolios.push(data);
         loadPortfolio();
         $scope.newportfolio = {};
         $scope.showform = false;
         }).error(function (data, status, headers) {
         console.log(data);
         console.log('header');
         if (data['error']) {
         alert(data['error']);
         }
         });
         }*/
    };

    $scope.deletePortfolio = function (item) {
        var conf = confirm('Do you want to delete this Record?');
        if (conf) {
            var id = item['id'];
            $http.delete($rootScope.base_url + '/Portfolio_Controller/delete/' + id)
                .success(function (data, status, headers) {
                    console.log(data);
                    alert(data);
                    loadPortfolio();
                });
        }

    };
    /*
     // Not using this method in this controll
     $scope.validateUrl = function(item){
     var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
     var regex = new RegExp(expression);
     var t = 'www.google.com';

     if (item.match(regex)) {
     alert("Successful match");
     } else {
     alert("No match");
     }
     }*/
});

