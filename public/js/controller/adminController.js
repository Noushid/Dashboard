
app.controller('adminController', function($scope,$location,$http) {
   $scope.employees = [];
    $scope.error = {};

    /* var base_url = $scope.baseUrl = $location.protocol() + "://" + location.host;

    $http.get(base_url + '/Dashboard/get_employees').then(function (response) {
        $scope.employees = response.data;
        console.log($scope.employees);
    });*/
});
