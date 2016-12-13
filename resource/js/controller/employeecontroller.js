/**
 * Created by psybo-03 on 13/12/16.
 */

app.controller('employeeController', function ($scope, $location, $http, $rootScope, fileUpload, action) {
    $scope.newemployee= {};
    $scope.employees = [];
    $scope.curemploye = [];
    $scope.showform = true;
    $scope.loading = false;


    loademployee();

    function loademployee() {
        $http.get($rootScope.base_url + '/Employees_Controller/get_employees').then(function (response) {
            $scope.employees = response.data;
        });
    }

    $scope.showForm = function (item) {

    };

    $scope.hideForm = function () {
        $scope.showform = false;
    };

    $scope.newEmployee = function () {
        $scope.newemployee = {};
        $scope.showform = true;
    };

    $scope.addEmployee = function () {
        console.log('test');
        console.log($scope.newemployee);
    };
});