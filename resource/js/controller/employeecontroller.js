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
    $scope.showtable = true;
    $scope.message = {};


    loademployee();

    function loademployee() {
        $http.get($rootScope.base_url + '/Employees_Controller/get_employees').then(function (response) {
            if (response.data) {
                $scope.employees = response.data;
                $scope.showtable = true;
            }else{
                $scope.showtable = false;
                $scope.message = 'No data Found';
            }


        });
    }

    $scope.showForm = function (item) {
        console.log(item);
        $scope.showform = true;
        $scope.curemploye = item;
        $scope.newemployee = angular.copy(item);
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
           console.log('edit');
           if ($scope.files.photo) {
               console.log('file seleect');
               //upload file
               var file = $scope.files.photo;
               var uploadUrl = $rootScope.base_url + '/Employees_Controller/upload_file';
               fileUpload.uploadFileToUrl(file, uploadUrl, 'dp')
                   .success(function (upload_data,status,headers) {
                       //add uploaded data to db
                       var url = $rootScope.base_url + '/Employees_Controller/add_file';

                       action.post(upload_data, url)
                           .success(function (data, status, headers) {
                               console.log('file uploaded');
                               console.log(data);
                               $scope.newemployee.files_id = data['files_id'];

                               var url = $rootScope.base_url + '/Employees_Controller/update';
                               var data = $scope.newemployee;
                               action.post(data, url)
                                   .success(function (data, status, headers) {
                                       console.log('edit success');
                                       console.log($scope.newemployee);
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
                   })
                   .error(function(data,status,headers) {
                       console.log('uploadng error');
                   })
           }else{
               var url = $rootScope.base_url + '/Employees_Controller/update';
               var data = $scope.newemployee;
               console.log(url);
               console.log(data);
               action.post(data, url)
                   .success(function (data, status, headers) {
                       console.log('edit success');
                       console.log($scope.newemployee);
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
           console.log('add');
           console.log($scope.files.photo);
           if ($scope.files.photo) {
               var file = $scope.files.photo;
               var uploadUrl = $rootScope.base_url + '/Employees_Controller/upload_file';
               fileUpload.uploadFileToUrl(file, uploadUrl, 'dp')
                   .success(function (upload, headers) {
                       var upload_data = [];
                       angular.forEach(upload, function (item) {
                           upload_data.push(item);
                       });
                       console.log('uploaded');
                       console.log(upload_data);
                       //    insert uploaded files information to db
                       var url = $rootScope.base_url + '/Employees_Controller/add_file';

                       action.post(upload_data, url)
                           .success(function (data, status, headers) {
                               console.log('insert file');
                               console.log(data['files_id']);

                               //    Add employee information to db
                               var url = $rootScope.base_url + '/Employees_Controller/store'
                               $scope.newemployee.files_id = data['files_id'];
                               var emp_data = $scope.newemployee;

                               action.post(emp_data, url)
                                   .success(function (data, status, headers) {
                                       console.log('all success');
                                       console.log(data);
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
            var url = $rootScope.base_url + '/Employees_Controller/delete/' + id;
            var data = item;
            action.post(data,url)
                .success(function (data, status, headers) {
                    console.log('deleted');
                    alert(data);
                    loademployee();
                })
                .error(function (data, status, headers) {
                    console.log('delete error');
                    console.log(headers);
                    console.log(status);
                });
        }
    };

    $scope.deleteImage = function(item) {
        console.log(item);

    };

});