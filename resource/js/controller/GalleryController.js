/**
 * Created by psybo-03 on 23/12/16.
 */

app.controller('GalleryController', function ($scope, $rootScope, $http, action, fileUpload) {
    $scope.galleries = [];
    $scope.newgallery = {};
    $scope.curgallery = {};
    $scope.galleryfiles = {};
    $scope.files = [];
    $scope.item_files = {};
    $scope.message = {};
    $scope.show_error = false;
    $scope.error = [];
    $scope.showform = false;

    $scope.numPerPage = 8;


    loadGallery();
    function loadGallery() {
        $http.get($rootScope.base_url + '/admin/gallery/get-all').then(function (response) {
            if (response.data) {
                $scope.galleries = response.data;
                console.log($scope.galleries);
            } else {
                console.log('No data found');
                $scope.message = 'No data found';
            }
        });
    }

    $scope.showForm = function (item) {
        $scope.showform = true;
        $scope.curgallery = item;
        $scope.newgallery = angular.copy(item);
        $scope.item_files = item.files;
    };

    $scope.hideForm= function () {
        $scope.showform = false;
    };

    $scope.newGallery = function () {
        $scope.newgallery = {};
        $scope.showform = true;
        angular.element("input[type='file']").val(null);
        $scope.filespre = [];
    };

    $scope.addGallery = function () {
        var fd = new FormData();
        //append posted to form data except files
        angular.forEach($scope.newgallery, function (item, key) {
            fd.append(key, item);
        });
        //    append posted files data to form data
        angular.forEach($scope.files.image, function (item,key) {
            fd.append('files[]', item);
        });

        console.log(fd.getAll('name'));


        if ($scope.newgallery['id']) {
            console.log('edit');

            var url = $rootScope.base_url + '/admin/gallery/edit/' + $scope.newgallery['id'];

            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log('edit success');
                    if (data['error'] != undefined) {
                        $scope.show_error = true;
                        $scope.error = data['error'];
                    }
                    loadGallery();
                    console.log($scope.error);
                    $scope.showform = false;
                    angular.element("input[type='file']").val(null);
                    $scope.item_files = [];
                    $scope.filespre = [];
                })
                .error(function (data, status, hedears) {
                    console.log('edit error');
                    console.log(data);
                });


        }else {
            console.log($scope.newgallery);
            console.log($scope.files.image);
            var url = $rootScope.base_url + '/admin/gallery/add';

            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log('add success');
                    if (data['error'] != undefined) {
                        $scope.show_error = true;
                        $scope.error = data['error'];
                    }

                    $scope.showform = false;
                    loadGallery();
                    angular.element("input[type='file']").val(null);
                    $scope.item_files = [];
                    $scope.filespre = [];

                    console.log($scope.error);
                })
                .error(function (data, status, hedears) {
                    console.log('add error');
                    console.log(data);
                });
        }
    };

    $scope.showGalleryFiles= function (item) {
        console.log(item);
        $scope.galleryfiles = item;
    };

    $scope.deleteImage= function (item) {
        console.log(item);
        var url = $rootScope.base_url + '/admin/gallery/delete-image';
        var data = item;
        action.post(data, url)
            .success(function (data, status, headers) {
                console.log('image deleted');
                console.log(data);
                var index = $scope.item_files.indexOf(item);
                $scope.item_files.splice(index, 1);
                console.log($scope.item_files);
            })
            .error(function (data, status, headers) {
                console.log('delete image error');
                console.log(data);
            });
    };

    $scope.deleteGallery= function (item) {
        var url = $rootScope.base_url + '/admin/gallery/delete';
        action.post(item, url)
            .success(function (data, status, headers) {
                console.log('gallery deleted');
                var index = $scope.galleries.indexOf(item);
                $scope.galleries.splice(index, 1);
            })
            .error(function (data,status,headers) {
                console.log('delete error');
                console.log(data);
            });
    };

});