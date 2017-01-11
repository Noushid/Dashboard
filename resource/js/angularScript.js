/**
 * Created by noushid on 16/9/16.
 */

var app = angular.module('myApp', ['ngRoute', 'ui.bootstrap','angularUtils.directives.dirPagination']);
app.config(function ($routeProvider) {
    $routeProvider
        //.when('/', {
        //    templateUrl: ''
        //})
        .when('/employees',{
            templateUrl: 'employees',
            controller: 'employeeController'
        })
        .when('/portfolio',{
            templateUrl: 'portfolio',
            controller: 'portfolioController'
        })
        .when('/testimonial',{
            templateUrl: 'testimonial',
            controller: 'testimonialController'
        })
        .when('/gallery',{
            templateUrl: 'gallery',
            controller: 'GalleryController'
        })

});

//Pagination filter
app.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});

//File reader directive new
app.directive('ngFileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            var model = $parse(attrs.ngFileModel);
            var isMultiple = attrs.multiple;
            var modelSetter = model.assign;
            element.bind('change', function () {
                var values = [];
                console.log(element[0].files);
                angular.forEach(element[0].files, function (item) {
                    var value = {
                        // File Name
                        name: item.name,
                        //File Size
                        size: item.size,
                        //File URL to view
                        url: URL.createObjectURL(item),
                        // File Input Value
                        _file: item
                    };
                    values.push(value);
                });
                scope.$apply(function () {
                    if (isMultiple) {
                        modelSetter(scope, values);
                    } else {
                        modelSetter(scope, values[0]);
                    }
                });
            });
        }
    };
}]);

app.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;
            scope.filespre = [];

            element.bind('change', function(){
                var values = [];
                angular.forEach(element[0].files, function (item) {
                    //url
                    item.url = URL.createObjectURL(item);
                    item.model = attrs.fileModel;
                    scope.filespre.push(item);
                });
                scope.$apply(function(){
                    modelSetter(scope, element[0].files);
                    //old
                    //modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);


// We can write our own fileUpload service to reuse it in the controller
app.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl, name){
        var fd = new FormData();
        var i = 0;
        angular.forEach(file, function (item) {
            fd.append(i, item);
            i++;
        });
/*        fd.append('file', file);*/
        fd.append('name', name);

        return $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined,'Process-Data': false}
        })
            //.success(function(data,status,headers){
            //    console.log(data);
            //})
            //.error(function(data,status,headers){
            //    console.log("error");
            //});
    }
}]);

//we can write our own insert service to reuse it in the controller
app.service('action', ['$http', function ($http) {
    this.post = function (data, url) {
        return $http({
            method: 'post',
            url: url,
            data: data,
            header: {'Content-type': 'application/x-www-form-urlencoded'}
        })

    };

}]);

app.directive('startslider',function() {
    return {
        restrict: 'A',
        replace: false,
        template: '<ul class="bxslider">' +
        '<li ng-repeat="picture in portfolioitem.files">' +
        '<img ng-src="{{picture.src}}" alt="" />' +
        '</li>' +
        '</ul>',
        link: function(scope, elm, attrs) {
            elm.ready(function() {
                $("." + $(elm[0]).attr('class')).bxSlider({
                    mode: 'fade',
                    auto:true,
                    autoControls: true,
                    //slideWidth: 768,
                    slideHeight:386
                });

            });
        }
    };
});


app.directive('ngConfirmClick', [
    function () {
        return {
            link: function (scope, element, attr) {
                var msg = attr.ngConfirmClick || "Are you sure?";
                var clickAction = attr.confirmedClick;
                element.bind('click', function (event) {
                    if (window.confirm(msg)) {
                        scope.$eval(clickAction)
                    }
                });
            }
        };
    }
]);


app.controller('MainCtrl', function($scope,$rootScope) {
    $scope.base = 'http://bxslider.com';

    $scope.images = [
        {src: $rootScope.public_url + '/assets/img/portfolio/preview/1.JPG' },
        {src: $rootScope.public_url + '/assets/img/portfolio/preview/2.JPG' },
        {src: $rootScope.public_url + '/assets/img/portfolio/preview/6.JPG' },
    ];
});


app.directive('carousel', [function () {
    return {
        restrict: 'A',
        //transclude: true,
        replace: false,
        controller: 'HomeBlogController',
        require: 'carousel'
    };
}]);