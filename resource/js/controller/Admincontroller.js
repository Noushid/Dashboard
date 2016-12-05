/**
 * Created by psybo-03 on 29/11/16.
 */

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
