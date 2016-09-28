/**
 * Created by noushi on 16/9/16.
 */

var app = angular.module('myApp', ['ngRoute']);
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
//AdminController

app.controller('adminController', function($scope,$location,$http, $rootScope) {
    $scope.employees = [];
    $scope.error = {};
    var base_url = $scope.baseUrl = $location.protocol() + "://" + location.host;
    $rootScope.base_url = base_url;
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
    $scope.message = {};
    $scope.error = {};
    $scope.showform = false;

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
        $scope.newportfolio = item;
    };

    //Hide the form
    $scope.hideForm=function() {
        $scope.showform = false;
    };

    $scope.newPortfolio = function() {
        //$scope.newportfolio = [];
        $scope.showform = true;
    };

    //Add
    $scope.addPortfolio = function () {
        //if ($scope.newportfolio['id']) {
        //}else{
            $http({
                method: 'post',
                url: $rootScope.base_url + '/Portfolio_Controller/store',
                data:$scope.newportfolio,
                header:{'Content-type':'application/x-www-form-urlencoded'}
            }).success(function(data,status,headers) {
                console.log(headers);
                $scope.portfolios.push(data);
                loadPortfolio();
                $scope.newportfolio= {};
            }).error(function(data,status,header) {
                console.log(data);
                console.log('header');
                if (data['error']) {
                    alert(data['error']);
                }
            })
        //}
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
});
