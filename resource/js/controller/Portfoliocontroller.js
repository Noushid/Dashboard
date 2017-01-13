/**
 * Created by psybo-03 on 29/11/16.
 */

//Portfolio Controller

app.controller('portfolioController', function ($scope, $location, $http, $rootScope,$filter, fileUpload,action) {



    $scope.newportfolio = {};
    $scope.portfolios = [];
    $scope.curportfolio = {};
    $scope.message = {};
    $scope.error = {};
    $scope.showform = false;
    $scope.showtable = true;
    $scope.files= [];
    $scope.loading = false;
    $scope.item_files = [];
    $scope.show_error = false;

    //$scope.regex = '^((https?|ftp)://)?([A-Za-z]+\\.)?[A-Za-z0-9-]+(\\.[a-zA-Z]{1,4}){1,2}(/.*\\?.*)?$';

    loadPortfolio();

    function loadPortfolio() {
        $http.get($rootScope.base_url +'/admin/portfolio/get-all').then(function(response) {
            if (response.data) {
                $scope.showtable = true;
                $scope.portfolios = response.data;
                console.log($scope.portfolios);
            }else{
                console.log('No data found');
                $scope.showtable = false;
                $scope.message = 'No data Found';
            }
        })
    };

    //show the form
    $scope.showForm = function (item) {
        $scope.showform = true;
        $scope.show_error=false
        $scope.curportfolio = item;
        $scope.newportfolio = angular.copy(item);
        angular.element("input[type='file']").val(null);
        $scope.filespre = [];
        $scope.item_files = item.files;
        console.log($scope.item_files);
    };

    //Hide the form
    $scope.hideForm=function() {
        $scope.showform = false;
    };

    $scope.newPortfolio = function() {
        $scope.newportfolio = {};
        $scope.showform = true;
        angular.element("input[type='file']").val(null);
        $scope.filespre = [];
    };

    //Add
    $scope.addPortfolio = function () {

        $scope.loading = true;
        var file = $scope.files.desktop;
        var file_mob = $scope.files.mobile;

        //Add http to url
        if ($scope.newportfolio.link != undefined) {
            var string = $scope.newportfolio.link;
            if (!~string.indexOf("http")) {
                $scope.newportfolio.link = "http://" + string;
            }
        }

        //Change date format
        $scope.newportfolio.date = $filter("date")(Date.parse($scope.newportfolio.date), 'yyyy-MM-dd');


        var fd = new FormData();
    //    append posted data to formData except file
        angular.forEach($scope.newportfolio, function (item, key) {
            fd.append(key, item);
        });

    //    append posted  files.desktop data to FormData
        angular.forEach($scope.files.desktop, function (item, key) {
            fd.append('desktop[]', item);
        });

    //append posted  files.desktop data to FormData
        angular.forEach($scope.files.mobile, function (item, key) {
            fd.append('mobile[]', item);
        });


        if ($scope.newportfolio.id != undefined) {
            console.log('edit');
            var url = $rootScope.base_url + '/admin/portfolio/edit/' + $scope.newportfolio.id;
            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log('edit succes');
                    console.log(data);
                    if (data['error'] != undefined) {
                        $scope.show_error = true;
                        $scope.error = data['error'];
                    }
                    loadPortfolio();
                    $scope.showform = false;
                    angular.element("input[type='file']").val(null);
                    $scope.item_files = [];
                    $scope.filespre = [];
                })
                .error(function (data, status, headers) {
                    console.log('edit error');
                    console.log(data);
                    if (data['error'] != undefined) {
                        $scope.show_error = true;
                        $scope.error = data['error'];
                    }
                    loadPortfolio();
                    angular.element("input[type='file']").val(null);
                    $scope.item_files = [];
                    $scope.filespre = [];
                });

        }else {
            console.log('add');

            var url = $rootScope.base_url + '/admin/portfolio/add';

            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log('add succes');
                    console.log(data);
                    console.log(data['error']);
                    if (data['error'] != undefined) {
                        $scope.show_error = true;
                        $scope.error = data['error'];
                    }
                    loadPortfolio();
                    $scope.showform = false;
                    angular.element("input[type='file']").val(null);
                    $scope.item_files = [];
                    $scope.filespre = [];
                })
                .error(function (data, status, headers) {
                    console.log('add error');

                    if (data['error'] != undefined) {
                        $scope.show_error = true;
                        $scope.error = data['error'];
                    }
                    loadPortfolio();
                    angular.element("input[type='file']").val(null);
                    $scope.item_files = [];
                    $scope.filespre = [];
                });
        }
    };

    $scope.deletePortfolio = function (item) {
        var id = item['id'];
        $http.delete($rootScope.base_url + '/admin/portfolio/delete/' + id)
            .success(function (data, status, headers) {
                console.log(data);
                alert(data);
                loadPortfolio();
            });
    };

    $scope.deleteImage = function(item) {
            var url = $rootScope.base_url + '/admin/portfolio/delete-image';
            var data = item;
            action.post(data, url)
                .success(function (data, headers, status) {
                    console.log('portfolio file deleted');
                    var index = $scope.item_files.indexOf(item);
                    $scope.item_files.splice(index, 1);
                })
                .error(function (data,headers,status) {
                    console.log('portfolio file delete error');
                });
    };
    /*
     // Not using this method in this controll
     $scope.validateUrl = function(item){
     var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
     var regex = new RegExp(expression);
     var t = 'www.google.com';

     if (item.match(regex)) {
     alert("Successful match");
     } else {
     alert("No match");
     }
     }*/

});

