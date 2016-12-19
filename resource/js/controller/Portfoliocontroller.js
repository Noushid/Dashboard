/**
 * Created by psybo-03 on 29/11/16.
 */

//Portfolio Controller

app.controller('portfolioController', function ($scope, $location, $http, $rootScope,fileUpload,action) {



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

    $scope.regex = '^((https?|ftp)://)?([A-Za-z]+\\.)?[A-Za-z0-9-]+(\\.[a-zA-Z]{1,4}){1,2}(/.*\\?.*)?$';

    loadPortfolio();

    function loadPortfolio() {
        $http.get($rootScope.base_url +'/portfolio/get-all').then(function(response) {
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

        if ($scope.newportfolio['id']) {
            console.log('edit');
            var upload_inform = [];
            if (file != undefined) {
                var uploadUrl = $rootScope.base_url + '/portfolio/upload';
                fileUpload.uploadFileToUrl(file, uploadUrl, 'desktop')
                    .success(function (desk_data) {
                        var portfolio_id = $scope.newportfolio['id'];
                        var url = $rootScope.base_url + '/portfolio/insert-file/' + portfolio_id;
                        var data = desk_data;

                        action.post(data, url)
                         .success(function (data, headers) {
                         console.log(data);
                         console.log('new file data inserted');
                         })
                         .error(function (data, headers) {
                         console.log(data);
                         console.log('error');
                         });
                    })
                    .error(function(data,headers) {
                        console.log('desktop image upload error');
                        console.log(data);
                        return false;
                    })
            }

            if (file_mob != undefined) {
                var uploadUrl = $rootScope.base_url + '/portfolio/upload';
                fileUpload.uploadFileToUrl(file_mob, uploadUrl, 'mobile')
                    .success(function (mob_data) {
                        var portfolio_id = $scope.newportfolio['id'];
                        var url = $rootScope.base_url + '/portfolio/insert-file/' + portfolio_id;
                        var data = mob_data;

                        action.post(data, url)
                            .success(function (data, headers) {
                                console.log(data);
                                console.log('new file data inserted');
                            })
                            .error(function (data, headers) {
                                console.log(data);
                                console.log(headers);
                                console.log('file upload error');
                            });
                    })
                    .error(function(data) {
                        console.log('mobile image uploaad error');
                        console.log(data);
                        return false;
                    })
            }

            var url =  $rootScope.base_url + '/portfolio/edit';
            var data = $scope.newportfolio;

            //insert data to table
            action.post(data, url)
            .success(function (data, status, headers) {
                $scope.portfolios.push(data);
                loadPortfolio();
                $scope.newportfolio = {};
                $scope.showform = false;
            })
            .error(function (data, status, headers) {
                if (data['error']) {
                    alert(data['error']);
                }
            });

            $scope.loading = false;
        }else{
            if (file != undefined) {
                var uploadUrl = $rootScope.base_url + '/portfolio/upload';
                //call upload service for upload desktop images.
                fileUpload.uploadFileToUrl(file, uploadUrl, 'desktop')
                    .success(function (data_desk) {
                        var upload_data = [];

                        angular.forEach(data_desk, function (item) {
                            upload_data.push(item);
                        });

                        //call upload service for upload desktop images.
                        fileUpload.uploadFileToUrl(file_mob, uploadUrl, 'mobile')
                            .success(function (data_mob) {
                                angular.forEach(data_mob, function (item) {
                                    upload_data.push(item);
                                });
                            })
                            .error(function (error) {
                                console.log(error);
                                return false;
                            });
                        //insert portfolio data to db
                        var url = $rootScope.base_url + '/portfolio/insert';
                        var insert_data = $scope.newportfolio;

                        action.post(insert_data, url)
                            .success(function (data, status, headers) {
                                console.log(upload_data);
                                //insert uploaded files information
                                var portfolio_id = data['id'];
                                var url = $rootScope.base_url + '/portfolio/insert-file/' + portfolio_id;
                                var data = upload_data;

                                action.post(data, url)
                                    .error(function (data, status, headers) {
                                        console.log('error');
                                        console.log(data);
                                    });

                                $scope.portfolios.push(data);
                                loadPortfolio();
                                $scope.newportfolio = {};
                                $scope.showform = false;
                            })
                            .error(function (data, status, headers) {
                                console.log(data);
                                var url = $rootScope.base_url + '/portfolio/delete-file';
                                var data = upload_data;
                                action.post(data, url)
                                    .success(function (data, status, headers) {
                                        console.log('deleted');
                                    })
                                    .error(function(data,status,headers) {
                                        console.log('error');
                                        console.log(data);
                                    })
                                if (data['error']) {
                                    alert(data['error']);
                                }
                            });
                    })
                    .error(function (data) {
                        console.log('error');
                        console.log(data);
                    });

            }else{
                alert('Please select any image!');
            }
            $scope.loading = false;
        }
    };

    $scope.deletePortfolio = function (item) {
        var conf = confirm('Do you want to delete this Record?');
        if (conf) {
            var id = item['id'];
            $http.delete($rootScope.base_url + '/portfolio/delete/' + id)
                .success(function (data, status, headers) {
                    console.log(data);
                    alert(data);
                    loadPortfolio();
                });
        }

    };

    $scope.deleteImage = function(item,key) {
        console.log(item);
        console.log(key);

        var url = $rootScope.base_url + '/portfolio/delete-image';
        //var conf = confirm('Do you want to delete this Image?!');
        var data = item;
        action.post(data, url)
            .success(function (data, headers, status) {

                console.log('portfolio file deleted');
                console.log(headers);
                console.log(data);
            })
            .error(function (data,headers,status) {
                console.log('portfolio file delete error');
                console.log(headers);
                console.log(data);
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

