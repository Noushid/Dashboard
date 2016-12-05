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
});

//Pagination filter
app.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});

/*

//file upload

app.directive('ngFiles', ['$parse', function ($parse) {
    function fn_link(scope, element, attrs) {
        var onChange = $parse(attrs.ngFiles);
        element.on('change', function (event) {
            onChange(scope, {$files: event.target.files});
        });
    };
    return {
        link: fn_link
    };

}]);


//file reader directive
app.directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
                        scope.fileread = loadEvent.target.result;
                    });
                };
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    };
}]);
*/

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

            element.bind('change', function(){
                var values = [];
                angular.forEach(element[0].files, function (item) {
                    //file name
                    values.push(item);
                });
                //console.log(values);
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
app.service('insert', ['$http', function ($http) {
    this.insertDataToUrl = function (data, url) {
        return $http({
            method: 'post',
            url: url,
            data: data,
            header: {'Content-type': 'application/x-www-form-urlencoded'}
        })

    }
}]);

