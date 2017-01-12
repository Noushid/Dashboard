/**
 * Created by psybo-03 on 29/11/16.
 */

//AdminController

app.controller('adminController', function ($scope, $location, $http, $rootScope, $filter, $window) {
    $scope.employees = [];
    $scope.error = {};
    var base_url = $scope.baseUrl = $location.protocol() + "://" + location.host;
    $rootScope.base_url = base_url;
    $rootScope.public_url = $scope.baseUrl = $location.protocol() + "://" + location.host;
    $scope.regex = RegExp('^((https?|ftp)://)?([a-z]+[.])?[a-z0-9-]+([.][a-z]{1,4}){1,2}(/.*[?].*)?$', 'i');

    $scope.paginations = [5, 10, 20, 25];
    $scope.numPerPage = 5;

    $scope.format = 'yyyy/MM/dd';
    //$scope.date = new Date();
    $scope.user = {};

    $scope.login = function () {
        console.log('login');
        var fd = new FormData();
        angular.forEach($scope.user, function (item, key) {
            fd.append(key, item);
        });

        var url = $rootScope.base_url + '/login/verify';
        $http.post(url, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false}
        })
            .success(function (data, status, headers) {
                $window.location.href = '/admin/#';
            })
            .error(function (data, status, header) {
                console.log('login error');
                console.log(data);
                $scope.showerror = true;
            });
    };
});

