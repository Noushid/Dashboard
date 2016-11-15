/**
 * Created by noushid on 16/9/16.
 */

var app = angular.module('myApp', ['ngRoute', 'ui.bootstrap','angularUtils.directives.dirPagination']);
app.config(function ($routeProvider) {
    $routeProvider
        //.when('/', {
        //    templateUrl: ''
        //})
        .when('/employees',{
            templateUrl: 'employees',
            controller: 'employeeController'
        })
        .when('/portfolio',{
            templateUrl: 'portfolio',
            controller: 'portfolioController'
        })
});

//Pagination filter
app.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});



//AdminController

app.controller('adminController', function($scope,$location,$http, $rootScope, $filter) {
    $scope.employees = [];
    $scope.error = {};
    var base_url = $scope.baseUrl = $location.protocol() + "://" + location.host;
    $rootScope.base_url = base_url;
    //$scope.url_regex = '^((https?|ftp)://)?([A-Za-z]+\\.)?[A-Za-z0-9-]+(\\.[a-zA-Z]{1,4}){1,2}(/.*\\?.*)?$';
    $scope.regex = RegExp('^((https?|ftp)://)?([a-z]+[.])?[a-z0-9-]+([.][a-z]{1,4}){1,2}(/.*[?].*)?$', 'i');

});


//Employee Controller
app.controller('employeeController', function ($scope, $location, $http, $rootScope) {
    $scope.employees = [];
    $scope.error = {};

    $http.get($rootScope.base_url + '/Employees_Controller/get_employees').then(function (response) {
        $scope.employees = response.data;
    });

});

//Portfolio Controller

app.controller('portfolioController', function ($scope, $location, $http, $rootScope) {



    $scope.newportfolio = {};
    $scope.portfolios = [];
    $scope.curportfolio = {};
    $scope.message = {};
    $scope.error = {};
    $scope.showform = false;

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
        //add protocol to link
        var string = $scope.newportfolio.link;
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
        }
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


