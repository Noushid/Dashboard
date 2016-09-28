app.controller('employeeController', function ($scope, $location, $http) {
    $scope.employees = [];
    $scope.error = {};

    var base_url = $scope.baseUrl = $location.protocol() + "://" + location.host;
     $http.get(base_url + '/Employees/get_employees').then(function (response) {
        $scope.employees = response.data;
    });

});

