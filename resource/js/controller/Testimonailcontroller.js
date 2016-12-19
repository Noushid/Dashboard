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
        $http.get($rootScope.base_url + '/Testimonial_Controller/get_all').then(function (response) {
            if (response.data) {
                $scope.testimonials = response.data;
                $scope.showtable = true;
                console.log($scope.testimonials);
            } else {
                console.log('No data Found');
                $scope.showtable = false;
                $scope.message = 'No data found';
            }
        });
    }




});