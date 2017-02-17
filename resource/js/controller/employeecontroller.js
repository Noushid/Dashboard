 /**
 * Created by psybo-03 on 13/12/16.
 */

app.controller('employeeController', function ($scope, $location, $http, $rootScope, fileUpload, action) {
    $scope.newemployee= {};
    $scope.employees = [];
    $scope.curemploye = [];
    $scope.files = [];
    $scope.showform = false;
    $scope.loading = false;
    $scope.message = {};
    $scope.filespre = [];


    loademployee();

    function loademployee() {
        $scope.loading = true;
        $http.get($rootScope.base_url + '/admin/employee').then(function (response) {
            if (response.data) {
                $scope.employees = response.data;
                $scope.showtable = true;
                $scope.loading = false;
            }else {
                console.log('No data found!');
                $scope.showtable = false;
                $scope.message = 'No Data Found';
                $scope.loading = false;
            }
        });
    }

    $scope.showForm = function (item) {
        console.log(item);
        $scope.showform = true;
        $scope.curemploye = item;
        $scope.newemployee = angular.copy(item);
        angular.element("input[type='file']").val(null);
        $scope.filespre = [];
    };

    $scope.hideForm = function () {
        $scope.showform = false;
    };

    $scope.newEmployee = function () {
        $scope.newemployee = {};
        $scope.showform = true;
        angular.element("input[type='file']").val(null);
        $scope.filespre = [];
    };

    $scope.addEmployee = function () {
        $scope.loading = true;
        if ($scope.newemployee.linkedin != undefined) {
            var string = $scope.newemployee.linkedin;
            if (!~string.indexOf("http")) {
                $scope.newemployee.linkedin = "http://" + string;
            }
        }
        if ($scope.newemployee.facebook != undefined) {
            var string = $scope.newemployee.facebook;
            if (!~string.indexOf("http")) {
                $scope.newemployee.facebook = "http://" + string;
            }
        }
        if ($scope.newemployee.twitter != undefined) {
            var string = $scope.newemployee.twitter;
            if (!~string.indexOf("http")) {
                $scope.newemployee.twitter = "http://" + string;
            }
        }
        if ($scope.newemployee.googleplus != undefined) {
            var string = $scope.newemployee.googleplus;
            if (!~string.indexOf("http")) {
                $scope.newemployee.googleplus = "http://" + string;
            }
        }
        if ($scope.newemployee.github != undefined) {
            var string = $scope.newemployee.github;
            if (!~string.indexOf("http")) {
                $scope.newemployee.github = "http://" + string;
            }
        }

        //Add data to form data
        var fd = new FormData();
        var i = 0;
        angular.forEach($scope.newemployee, function (item, key) {
            fd.append(key, item);
        });
        angular.forEach($scope.files.photo, function (item, key) {
            fd.append('photo', item);
            i++;
        });

        if ($scope.newemployee.id) {
            console.log('edit');
            var url = $rootScope.base_url + '/admin/employee/edit/' + $scope.newemployee.id;

            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    $scope.newemployee = {};
                    $scope.showform = false;
                    $scope.employees.push(data);
                    loademployee();
                    $scope.newemployee = {};
                    $scope.filespre = [];
                    $scope.loading = false;
                })
                .error(function (data, status, heders) {
                    console.log(data);
                    $scope.loading = false;
                });
        }else {
            var url = $rootScope.base_url + '/admin/employee/add';
            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log(data);
                    $scope.employees.push(data);
                    loademployee();
                    $scope.newemployee = {};
                    $scope.showform = false;
                    $scope.filespre = [];
                    $scope.loading = false;
                })
                .error(function (data, status, heders) {
                    console.log(data);
                    $scope.loading = false;
                });
        }

    };

    $scope.deleteEmployee = function (item) {
        $scope.loading = true;
        console.log(item);
        var id = item['id'];
        var url = $rootScope.base_url + '/admin/employee/delete/' + id;
        var data = item;
        action.post(data,url)
            .success(function (data, status, headers) {
                console.log('deleted');
                var index = $scope.employees.indexOf(item);
                $scope.employees.splice(index, 1);
                alert(data);
                loademployee();
                $scope.loading = false;
            })
            .error(function (data, status, headers) {
                console.log('delete error');
                console.log(data);
                $scope.loading = false;
            });
    };
});