/**
 * Created by psybo-03 on 19/12/16.
 */

app.controller('testimonialController', function ($scope, $http, $rootScope, action) {

    $scope.testimonials = [];
    $scope.newtestimonial = {};
    $scope.curtestimonial = {};
    $scope.files = [];
    $scope.showform = false;
    $scope.loading = false;
    $scope.message = {};


    loadTestimonial();

    function loadTestimonial() {
        $scope.loading = true;
        $http.get($rootScope.base_url + '/Testimonial_Controller/get_all').then(function (response) {
            if (response.data) {
                $scope.testimonials = response.data;
                $scope.showtable = true;
                console.log($scope.testimonials);
                $scope.loading = false;
            } else {
                console.log('No data Found');
                $scope.showtable = false;
                $scope.message = 'No data found';
                $scope.loading = false;
            }
        });
    }

    $scope.newTestimonial = function() {
        $scope.newtestimonial = {};
        angular.element("input[type='file']").val(null);
        $scope.filespre = [];
        $scope.showform = true;
    };

    $scope.showForm = function (item) {
        console.log(item);
        $scope.showform = true;
        $scope.curtestimonial = item;
        angular.element("input[type='file']").val(null);
        $scope.newtestimonial = angular.copy(item);
        $scope.filespre = [];
    };

    $scope.hideForm = function () {
        $scope.showform = false;
    };

    $scope.addTestimonial = function () {
        $scope.loading = true;
        if ($scope.newtestimonial.link != undefined) {
            var string = $scope.newtestimonial.link;
            if (!~string.indexOf("http")) {
                $scope.newtestimonial.link = "http://" + string;
            }
        }

        var fd = new FormData();
        var i = 0;
        angular.forEach($scope.newtestimonial, function (item, key) {
            fd.append(key, item);
        });
        angular.forEach($scope.files.photo, function (item, key) {
            fd.append('photo', item);
            i++;
        });

        if ($scope.newtestimonial['id']) {
            console.log('edit');
            console.log($scope.newtestimonial);


            var url = $rootScope.base_url + '/admin/testimonial/edit/' + $scope.newtestimonial.id;
            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log('test add success');
                    console.log(data);
                    $scope.testimonials.push(data);
                    loadTestimonial();
                    $scope.newtestimonial = {};
                    $scope.showform = false;
                    $scope.loading = false;
                })
                .error(function (data, status, heders) {
                    console.log('test add error');
                    console.log(data);
                    $scope.loading = false;
                });
        }else {
            console.log('add');
            console.log($scope.newtestimonial);
            var url = $rootScope.base_url + '/admin/testimonial/add';

            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log('test add success');
                    $scope.testimonials.push(data);
                    loadTestimonial();
                    $scope.newtestimonial = {};
                    $scope.showform = false;
                    $scope.loading = false;
                })
                .error(function (data, status, heders) {
                    console.log('test add error');
                    console.log(data);
                    $scope.loading = false;
                });
        }
    };

    $scope.deleteTestimonial = function (item) {
        $scope.loading = true;
        var id = item['id'];
        var url = $rootScope.base_url + '/admin/testimonial/delete/' + id;
        var data = item;
        action.post(data,url)
            .success(function (data, status, headers) {
                console.log('deleted');
                var index = $scope.testimonials.indexOf(item);
                $scope.testimonials.splice(index, 1);
                alert(data);
                loadTestimonial();
                $scope.loading = false;
            })
            .error(function (data, status, headers) {
                console.log('delete error');
                console.log(data);
                $scope.loading = false;
            });
    };


});