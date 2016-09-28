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

    $http.get($rootScope.base_url +'/Portfolio_Controller/get').then(function(response) {
        $scope.portfolios = response.data;
        console.log($scope.portfolios);
    })

    $scope.addPortfolio = function () {
        $http({
            method: 'post',
            url: $rootScope.base_url + '/Portfolio_Controller/store',
            data:$scope.newportfolio,
            header:{'Content-type':'application/x-www-form-urlencoded'}
        }).success(function(data) {
            $scope.message = data;
            //console.log($scope.message);
            console.log('success');
        }).error(function(data) {
            console.log('failed');
        })
    };
});
