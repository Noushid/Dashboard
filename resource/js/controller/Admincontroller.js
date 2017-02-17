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

    $scope.newuser = {};
    $scope.formdisable = false;
    $scope.paginations = [5, 10, 20, 25];
    $scope.numPerPage = 5;

    $scope.format = 'yyyy/MM/dd';
    //$scope.date = new Date();
    $scope.user = {};
    $scope.paginations = [5, 10, 20, 25];
    $scope.numPerPage = 5;

    load_user();

    function load_user() {
        var url = $rootScope.base_url + '/admin/user';
        $http.get(url).then(function (response) {
            if (response.data) {
                $scope.user = response.data.username;
                $scope.newuser.username = $scope.user;
                console.log(response.data.username);
            }
        });
    }

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
                $scope.error = data;
                $scope.showerror = true;
            });
    };

    $scope.changeProfile = function () {
        $rootScope.loading = true;
        var fd = new FormData();
        angular.forEach($scope.newuser, function (item, key) {
            fd.append(key, item);
        });
        var url = $rootScope.base_url + '/admin/change/submit';
        $http.post(url, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false}
        })
            .success(function (data, status, headers) {
                $rootScope.loading = false;
                console.log('profile changed');
                $scope.showmsg = true;
                $scope.formdisable = true;
            })
            .error(function (data, status, headers) {
                $rootScope.loading = false;
                $scope.showerror = true;
            });
    };

    $scope.reset = function () {
        $scope.newuser = {};
        load_user();
        $scope.newuser.username = $scope.user;
        $scope.showerror = false;
        $scope.showmsg = false;
        $scope.formdisable = false;
    };

    $scope.cancel = function () {
        $window.location.href = '/admin/#';
    };

});

