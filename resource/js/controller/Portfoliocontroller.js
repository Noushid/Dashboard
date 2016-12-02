/**
 * Created by psybo-03 on 29/11/16.
 */

//Portfolio Controller

app.controller('portfolioController', function ($scope, $location, $http, $rootScope,fileUpload,insert) {



    $scope.newportfolio = {};
    $scope.portfolios = [];
    $scope.curportfolio = {};
    $scope.message = {};
    $scope.error = {};
    $scope.showform = true;
    $scope.files= [];

    $scope.regex = '^((https?|ftp)://)?([A-Za-z]+\\.)?[A-Za-z0-9-]+(\\.[a-zA-Z]{1,4}){1,2}(/.*\\?.*)?$';

    loadPortfolio();

    function loadPortfolio() {
        $http.get($rootScope.base_url +'/Portfolio_Controller/get').then(function(response) {
            $scope.portfolios = response.data;
            console.log($scope.portfolios);
        })
    };

    //show the form
    $scope.showForm = function (item) {
        $scope.showform = true;
        $scope.curportfolio = item;
        $scope.newportfolio = angular.copy(item);
    };

    //Hide the form
    $scope.hideForm=function() {
        $scope.showform = false;
    };

    $scope.newPortfolio = function() {
        $scope.newportfolio = {};
        $scope.showform = true;
    };

    //Add
    $scope.addPortfolio = function () {

        var file = $scope.files.desktop;

        var upload_data = data;

        //Add http to url
        if ($scope.newportfolio.link != undefined) {
            var string = $scope.newportfolio.link;
            if (!~string.indexOf("http")) {
                $scope.newportfolio.link = "http://" + string;
            }
        }

        if ($scope.newportfolio['id']) {
            console.log('edit');
            var url =  $rootScope.base_url + '/Portfolio_Controller/edit_record';
            var data = $scope.newportfolio;
            var header= {'Content-type': 'application/x-www-form-urlencoded'}

            //call insert service
            var update = insert.insertDataToUrl(data, url, header);
            update.success(function (data, status, headers) {
                $scope.portfolios.push(data);
                loadPortfolio();
                $scope.newportfolio = {};
                $scope.showform = false;
            });
            update.error(function (data, status, headers) {
                if (data['error']) {
                    alert(data['error']);
                }
            });
        }else{
            console.log('add');
            if (file != undefined) {
                var uploadUrl = $rootScope.base_url + '/Portfolio_Controller/upload_file';
                //call upload service.
                var upload = fileUpload.uploadFileToUrl(file, uploadUrl, 'desktop');
                upload.success(function (data) {
                    var upload_data = data;

                    var url = $rootScope.base_url + '/Portfolio_Controller/store';
                    var insert_data = $scope.newportfolio;

                    var addPortfolio = insert.insertDataToUrl(insert_data, url);

                    console.log(addPortfolio);
                    addPortfolio.success(function (data, status, headers) {
                        console.log(data);

                        //insert files information
                        var portfolio_id = data['id'];
                        var url = $rootScope.base_url + '/Portfolio_Controller/add_file/' + portfolio_id;
                        var data = upload_data;
                        var addFiles = insert.insertDataToUrl(data, url);
                        addFiles.success(function (data, status, headers) {
                            console.log('add files');
                            console.log(data);
                        });
                        addFiles.error(function (data, status, headers) {
                            console.log('error');
                            console.log(data);
                        });

                        $scope.portfolios.push(data);
                        loadPortfolio();
                        $scope.newportfolio = {};
                        $scope.showform = false;
                    });
                    addPortfolio.error(function (data, status, headers) {
                        console.log(data);
                        console.log(headers);
                        if (data['error']) {
                            alert(data['error']);
                        }
                    });
                });
                upload.error(function (data) {
                    console.log('error');
                    console.log(data);
                });

            }else {
                alert('Please select any image!');
            }


        }













        //var uploadUrl = $rootScope.base_url + '/Portfolio_Controller/upload_file';
        ////call upload service.
        //var upload = fileUpload.uploadFileToUrl(file, uploadUrl, 'desktop');
        //upload.success(function (data) {
        //
        //});
        //upload.error(function (data) {
        //    console.log('error');
        //    console.log(data);
        //});

        //add protocol to link
       /* var string = $scope.newportfolio.link;
         if (!~string.indexOf("http")) {
             $scope.newportfolio.link = "http://" + string;
         }

         if ($scope.newportfolio['id']) {
             console.log('edit');
             var id = $scope.newportfolio['id'];
             $http({
                 method: 'post',
                 url: $rootScope.base_url + '/Portfolio_Controller/edit_record',
                 data: $scope.newportfolio,
                 header: {'Content-type': 'application/x-www-form-urlencoded'}
                 })
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
         }else{
         $http({
             method: 'post',
             url: $rootScope.base_url + '/Portfolio_Controller/store',
             data: $scope.newportfolio,
             header: {'Content-type': 'application/x-www-form-urlencoded'}
             })
             .success(function (data, status, headers) {
                 console.log(headers);
                 $scope.portfolios.push(data);
                 loadPortfolio();
                 $scope.newportfolio = {};
                 $scope.showform = false;
             })
             .error(function (data, status, headers) {
                 console.log(data);
                 console.log('header');
                 if (data['error']) {
                 alert(data['error']);
                 }
             });
         }*/
    };

    $scope.deletePortfolio = function (item) {
        var conf = confirm('Do you want to delete this Record?');
        if (conf) {
            var id = item['id'];
            $http.delete($rootScope.base_url + '/Portfolio_Controller/delete/' + id)
                .success(function (data, status, headers) {
                    console.log(data);
                    alert(data);
                    loadPortfolio();
                });
        }

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

