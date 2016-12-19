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


    loademployee();

    function loademployee() {
        $http.get($rootScope.base_url + '/employee').then(function (response) {
            if (response.data) {
                $scope.employees = response.data;
                $scope.showtable = true;
            }else {
                console.log('No data found!');
                $scope.showtable = false;
                $scope.message = 'No Data Found';
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
        if ($scope.newemployee.linkedin != undefined) {
            var string = $scope.newemployee.linkedin;
            if (!~string.indexOf("http")) {
                $scope.newemployee.linkedin = "http://" + string;
            }
        }
       if ($scope.newemployee['id']) {

           /* dont remove before complete.
           console.log($scope.newemployee);

           var fd = new FormData();
           var i = 0;
           angular.forEach($scope.newemployee, function (item,key) {
               fd.append(key, item);
           });
           angular.forEach($scope.files.photo, function (item) {
               fd.append(i, item);
               i++;
           });


           $http.post($rootScope.base_url + '/Employees_Controller/update', fd,{
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': true}
           });*/

           var temp = [];
           if ($scope.files.photo) {
               console.log('file seleect');
               //upload file
               var file = $scope.files.photo;
               var uploadUrl = $rootScope.base_url + '/employee/upload';
               fileUpload.uploadFileToUrl(file, uploadUrl, 'dp')
                   .success(function (upload_data,status,headers) {
                       //add uploaded data to db
                       var url = $rootScope.base_url + '/employee/insert-file';

                       action.post(upload_data, url)
                           .success(function (data, status, headers) {
                               console.log('file uploaded');
                               console.log($scope.newemployee);
                               $scope.newemployee.files_id = data['files_id'];
                               console.log($scope.newemployee);
                               var url = $rootScope.base_url + '/employee/edit';
                               var data = $scope.newemployee;

                               action.post(data, url)
                                   .success(function (data, status, headers) {
                                       console.log('edit success');
                                       $scope.employees.push(data);
                                       loademployee();
                                       $scope.newemployee = {};
                                       $scope.showform = false;
                                   })
                                   .error(function (data, status, headers) {
                                       console.log('edit error');
                                       console.log(data);
                                       if (data['error']) {
                                           alert(data['error']);
                                       }
                                   });
                           })
                   });
           }else{
               var url = $rootScope.base_url + '/employee/edit';
               var data = $scope.newemployee;
               action.post(data, url)
                   .success(function (data, status, headers) {
                       console.log('edit success');
                       $scope.employees.push(data);
                       loademployee();
                       $scope.newemployee = {};
                       $scope.showform = false;
                   })
                   .error(function (data, status, headers) {
                       console.log('edit error');
                       console.log(data);
                       if (data['error']) {
                           alert(data['error']);
                       }
                   });
           }
        }else {
           if ($scope.files.photo) {
               var file = $scope.files.photo;
               var uploadUrl = $rootScope.base_url + '/employee/upload';
               fileUpload.uploadFileToUrl(file, uploadUrl, 'dp')
                   .success(function (upload, headers) {
                       var upload_data = [];
                       angular.forEach(upload, function (item) {
                           upload_data.push(item);
                       });
                       //    insert uploaded files information to db
                       var url = $rootScope.base_url + '/employee/insert-file';

                       action.post(upload_data, url)
                           .success(function (data, status, headers) {

                               //    Add employee information to db
                               var url = $rootScope.base_url + '/add'
                               $scope.newemployee.files_id = data['files_id'];
                               var emp_data = $scope.newemployee;

                               action.post(emp_data, url)
                                   .success(function (data, status, headers) {
                                       console.log('all success');
                                       $scope.employees.push(data);
                                       loademployee();
                                       $scope.newemployee = {};
                                       $scope.showform = false;
                                   })
                                   .error(function (data, status, headers) {
                                       console.log('employee insert error');
                                       console.log(data);
                                   })
                           })
                           .error(function (data,status,headers) {
                               console.log('error');
                               console.log(data);
                           });

                   })
                   .error(function (data, headers) {
                       console.log('upload error');
                       console.log(data);
                   });
           }
       }
    };

    $scope.deleteEmployee = function (item) {
        console.log(item);
        var conf = confirm('Do you want to delete this record?');
        if (conf) {
            var id = item['id'];
            var url = $rootScope.base_url + '/employee/edit/' + id;
            var data = item;
            action.post(data,url)
                .success(function (data, status, headers) {
                    console.log('deleted');
                    var index = $scope.employees.indexOf(item);
                    $scope.employees.splice(index, 1);
                    alert(data);
                    loademployee();
                })
                .error(function (data, status, headers) {
                    console.log('delete error');
                    console.log(data);
                });
        }
    };
});