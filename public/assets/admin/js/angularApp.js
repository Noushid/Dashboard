/**
 * Created by noushid on 16/9/16.
 */

var app = angular.module('myApp', ['ngRoute', 'ui.bootstrap','angularUtils.directives.dirPagination']);
app.config(function ($routeProvider) {
    $routeProvider
        .when('/', {
        })
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
        .when('/profile',{
            templateUrl: 'change'
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


/**
 * dirPagination - AngularJS module for paginating (almost) anything.
 *
 *
 * Credits
 * =======
 *
 * Daniel Tabuenca: https://groups.google.com/d/msg/angular/an9QpzqIYiM/r8v-3W1X5vcJ
 * for the idea on how to dynamically invoke the ng-repeat directive.
 *
 * I borrowed a couple of lines and a few attribute names from the AngularUI Bootstrap project:
 * https://github.com/angular-ui/bootstrap/blob/master/src/pagination/pagination.js
 *
 * Copyright 2014 Michael Bromley <michael@michaelbromley.co.uk>
 */

(function() {

    /**
     * Config
     */
    var moduleName = 'angularUtils.directives.dirPagination';
    var DEFAULT_ID = '__default';

    /**
     * Module
     */
    angular.module(moduleName, [])
        .directive('dirPaginate', ['$compile', '$parse', 'paginationService', dirPaginateDirective])
        .directive('dirPaginateNoCompile', noCompileDirective)
        .directive('dirPaginationControls', ['paginationService', 'paginationTemplate', dirPaginationControlsDirective])
        .filter('itemsPerPage', ['paginationService', itemsPerPageFilter])
        .service('paginationService', paginationService)
        .provider('paginationTemplate', paginationTemplateProvider)
        .run(['$templateCache',dirPaginationControlsTemplateInstaller]);

    function dirPaginateDirective($compile, $parse, paginationService) {

        return  {
            terminal: true,
            multiElement: true,
            priority: 100,
            compile: dirPaginationCompileFn
        };

        function dirPaginationCompileFn(tElement, tAttrs){

            var expression = tAttrs.dirPaginate;
            // regex taken directly from https://github.com/angular/angular.js/blob/v1.4.x/src/ng/directive/ngRepeat.js#L339
            var match = expression.match(/^\s*([\s\S]+?)\s+in\s+([\s\S]+?)(?:\s+as\s+([\s\S]+?))?(?:\s+track\s+by\s+([\s\S]+?))?\s*$/);

            var filterPattern = /\|\s*itemsPerPage\s*:\s*(.*\(\s*\w*\)|([^\)]*?(?=\s+as\s+))|[^\)]*)/;
            if (match[2].match(filterPattern) === null) {
                throw 'pagination directive: the \'itemsPerPage\' filter must be set.';
            }
            var itemsPerPageFilterRemoved = match[2].replace(filterPattern, '');
            var collectionGetter = $parse(itemsPerPageFilterRemoved);

            addNoCompileAttributes(tElement);

            // If any value is specified for paginationId, we register the un-evaluated expression at this stage for the benefit of any
            // dir-pagination-controls directives that may be looking for this ID.
            var rawId = tAttrs.paginationId || DEFAULT_ID;
            paginationService.registerInstance(rawId);

            return function dirPaginationLinkFn(scope, element, attrs){

                // Now that we have access to the `scope` we can interpolate any expression given in the paginationId attribute and
                // potentially register a new ID if it evaluates to a different value than the rawId.
                var paginationId = $parse(attrs.paginationId)(scope) || attrs.paginationId || DEFAULT_ID;
                
                // (TODO: this seems sound, but I'm reverting as many bug reports followed it's introduction in 0.11.0.
                // Needs more investigation.)
                // In case rawId != paginationId we deregister using rawId for the sake of general cleanliness
                // before registering using paginationId
                // paginationService.deregisterInstance(rawId);
                paginationService.registerInstance(paginationId);

                var repeatExpression = getRepeatExpression(expression, paginationId);
                addNgRepeatToElement(element, attrs, repeatExpression);

                removeTemporaryAttributes(element);
                var compiled =  $compile(element);

                var currentPageGetter = makeCurrentPageGetterFn(scope, attrs, paginationId);
                paginationService.setCurrentPageParser(paginationId, currentPageGetter, scope);

                if (typeof attrs.totalItems !== 'undefined') {
                    paginationService.setAsyncModeTrue(paginationId);
                    scope.$watch(function() {
                        return $parse(attrs.totalItems)(scope);
                    }, function (result) {
                        if (0 <= result) {
                            paginationService.setCollectionLength(paginationId, result);
                        }
                    });
                } else {
                    paginationService.setAsyncModeFalse(paginationId);
                    scope.$watchCollection(function() {
                        return collectionGetter(scope);
                    }, function(collection) {
                        if (collection) {
                            var collectionLength = (collection instanceof Array) ? collection.length : Object.keys(collection).length;
                            paginationService.setCollectionLength(paginationId, collectionLength);
                        }
                    });
                }

                // Delegate to the link function returned by the new compilation of the ng-repeat
                compiled(scope);
                 
                // (TODO: Reverting this due to many bug reports in v 0.11.0. Needs investigation as the
                // principle is sound)
                // When the scope is destroyed, we make sure to remove the reference to it in paginationService
                // so that it can be properly garbage collected
                // scope.$on('$destroy', function destroyDirPagination() {
                //     paginationService.deregisterInstance(paginationId);
                // });
            };
        }

        /**
         * If a pagination id has been specified, we need to check that it is present as the second argument passed to
         * the itemsPerPage filter. If it is not there, we add it and return the modified expression.
         *
         * @param expression
         * @param paginationId
         * @returns {*}
         */
        function getRepeatExpression(expression, paginationId) {
            var repeatExpression,
                idDefinedInFilter = !!expression.match(/(\|\s*itemsPerPage\s*:[^|]*:[^|]*)/);

            if (paginationId !== DEFAULT_ID && !idDefinedInFilter) {
                repeatExpression = expression.replace(/(\|\s*itemsPerPage\s*:\s*[^|\s]*)/, "$1 : '" + paginationId + "'");
            } else {
                repeatExpression = expression;
            }

            return repeatExpression;
        }

        /**
         * Adds the ng-repeat directive to the element. In the case of multi-element (-start, -end) it adds the
         * appropriate multi-element ng-repeat to the first and last element in the range.
         * @param element
         * @param attrs
         * @param repeatExpression
         */
        function addNgRepeatToElement(element, attrs, repeatExpression) {
            if (element[0].hasAttribute('dir-paginate-start') || element[0].hasAttribute('data-dir-paginate-start')) {
                // using multiElement mode (dir-paginate-start, dir-paginate-end)
                attrs.$set('ngRepeatStart', repeatExpression);
                element.eq(element.length - 1).attr('ng-repeat-end', true);
            } else {
                attrs.$set('ngRepeat', repeatExpression);
            }
        }

        /**
         * Adds the dir-paginate-no-compile directive to each element in the tElement range.
         * @param tElement
         */
        function addNoCompileAttributes(tElement) {
            angular.forEach(tElement, function(el) {
                if (el.nodeType === 1) {
                    angular.element(el).attr('dir-paginate-no-compile', true);
                }
            });
        }

        /**
         * Removes the variations on dir-paginate (data-, -start, -end) and the dir-paginate-no-compile directives.
         * @param element
         */
        function removeTemporaryAttributes(element) {
            angular.forEach(element, function(el) {
                if (el.nodeType === 1) {
                    angular.element(el).removeAttr('dir-paginate-no-compile');
                }
            });
            element.eq(0).removeAttr('dir-paginate-start').removeAttr('dir-paginate').removeAttr('data-dir-paginate-start').removeAttr('data-dir-paginate');
            element.eq(element.length - 1).removeAttr('dir-paginate-end').removeAttr('data-dir-paginate-end');
        }

        /**
         * Creates a getter function for the current-page attribute, using the expression provided or a default value if
         * no current-page expression was specified.
         *
         * @param scope
         * @param attrs
         * @param paginationId
         * @returns {*}
         */
        function makeCurrentPageGetterFn(scope, attrs, paginationId) {
            var currentPageGetter;
            if (attrs.currentPage) {
                currentPageGetter = $parse(attrs.currentPage);
            } else {
                // If the current-page attribute was not set, we'll make our own.
                // Replace any non-alphanumeric characters which might confuse
                // the $parse service and give unexpected results.
                // See https://github.com/michaelbromley/angularUtils/issues/233
                var defaultCurrentPage = (paginationId + '__currentPage').replace(/\W/g, '_');
                scope[defaultCurrentPage] = 1;
                currentPageGetter = $parse(defaultCurrentPage);
            }
            return currentPageGetter;
        }
    }

    /**
     * This is a helper directive that allows correct compilation when in multi-element mode (ie dir-paginate-start, dir-paginate-end).
     * It is dynamically added to all elements in the dir-paginate compile function, and it prevents further compilation of
     * any inner directives. It is then removed in the link function, and all inner directives are then manually compiled.
     */
    function noCompileDirective() {
        return {
            priority: 5000,
            terminal: true
        };
    }

    function dirPaginationControlsTemplateInstaller($templateCache) {
        $templateCache.put('angularUtils.directives.dirPagination.template', '<ul class="pagination" ng-if="1 < pages.length || !autoHide"><li ng-if="boundaryLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(1)">&laquo;</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == 1 }"><a href="" ng-click="setCurrent(pagination.current - 1)">&lsaquo;</a></li><li ng-repeat="pageNumber in pages track by tracker(pageNumber, $index)" ng-class="{ active : pagination.current == pageNumber, disabled : pageNumber == \'...\' || ( ! autoHide && pages.length === 1 ) }"><a href="" ng-click="setCurrent(pageNumber)">{{ pageNumber }}</a></li><li ng-if="directionLinks" ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.current + 1)">&rsaquo;</a></li><li ng-if="boundaryLinks"  ng-class="{ disabled : pagination.current == pagination.last }"><a href="" ng-click="setCurrent(pagination.last)">&raquo;</a></li></ul>');
    }

    function dirPaginationControlsDirective(paginationService, paginationTemplate) {

        var numberRegex = /^\d+$/;

        var DDO = {
            restrict: 'AE',
            scope: {
                maxSize: '=?',
                onPageChange: '&?',
                paginationId: '=?',
                autoHide: '=?'
            },
            link: dirPaginationControlsLinkFn
        };

        // We need to check the paginationTemplate service to see whether a template path or
        // string has been specified, and add the `template` or `templateUrl` property to
        // the DDO as appropriate. The order of priority to decide which template to use is
        // (highest priority first):
        // 1. paginationTemplate.getString()
        // 2. attrs.templateUrl
        // 3. paginationTemplate.getPath()
        var templateString = paginationTemplate.getString();
        if (templateString !== undefined) {
            DDO.template = templateString;
        } else {
            DDO.templateUrl = function(elem, attrs) {
                return attrs.templateUrl || paginationTemplate.getPath();
            };
        }
        return DDO;

        function dirPaginationControlsLinkFn(scope, element, attrs) {

            // rawId is the un-interpolated value of the pagination-id attribute. This is only important when the corresponding dir-paginate directive has
            // not yet been linked (e.g. if it is inside an ng-if block), and in that case it prevents this controls directive from assuming that there is
            // no corresponding dir-paginate directive and wrongly throwing an exception.
            var rawId = attrs.paginationId ||  DEFAULT_ID;
            var paginationId = scope.paginationId || attrs.paginationId ||  DEFAULT_ID;

            if (!paginationService.isRegistered(paginationId) && !paginationService.isRegistered(rawId)) {
                var idMessage = (paginationId !== DEFAULT_ID) ? ' (id: ' + paginationId + ') ' : ' ';
                if (window.console) {
                    console.warn('Pagination directive: the pagination controls' + idMessage + 'cannot be used without the corresponding pagination directive, which was not found at link time.');
                }
            }

            if (!scope.maxSize) { scope.maxSize = 9; }
            scope.autoHide = scope.autoHide === undefined ? true : scope.autoHide;
            scope.directionLinks = angular.isDefined(attrs.directionLinks) ? scope.$parent.$eval(attrs.directionLinks) : true;
            scope.boundaryLinks = angular.isDefined(attrs.boundaryLinks) ? scope.$parent.$eval(attrs.boundaryLinks) : false;

            var paginationRange = Math.max(scope.maxSize, 5);
            scope.pages = [];
            scope.pagination = {
                last: 1,
                current: 1
            };
            scope.range = {
                lower: 1,
                upper: 1,
                total: 1
            };

            scope.$watch('maxSize', function(val) {
                if (val) {
                    paginationRange = Math.max(scope.maxSize, 5);
                    generatePagination();
                }
            });

            scope.$watch(function() {
                if (paginationService.isRegistered(paginationId)) {
                    return (paginationService.getCollectionLength(paginationId) + 1) * paginationService.getItemsPerPage(paginationId);
                }
            }, function(length) {
                if (0 < length) {
                    generatePagination();
                }
            });

            scope.$watch(function() {
                if (paginationService.isRegistered(paginationId)) {
                    return (paginationService.getItemsPerPage(paginationId));
                }
            }, function(current, previous) {
                if (current != previous && typeof previous !== 'undefined') {
                    goToPage(scope.pagination.current);
                }
            });

            scope.$watch(function() {
                if (paginationService.isRegistered(paginationId)) {
                    return paginationService.getCurrentPage(paginationId);
                }
            }, function(currentPage, previousPage) {
                if (currentPage != previousPage) {
                    goToPage(currentPage);
                }
            });

            scope.setCurrent = function(num) {
                if (paginationService.isRegistered(paginationId) && isValidPageNumber(num)) {
                    num = parseInt(num, 10);
                    paginationService.setCurrentPage(paginationId, num);
                }
            };

            /**
             * Custom "track by" function which allows for duplicate "..." entries on long lists,
             * yet fixes the problem of wrongly-highlighted links which happens when using
             * "track by $index" - see https://github.com/michaelbromley/angularUtils/issues/153
             * @param id
             * @param index
             * @returns {string}
             */
            scope.tracker = function(id, index) {
                return id + '_' + index;
            };

            function goToPage(num) {
                if (paginationService.isRegistered(paginationId) && isValidPageNumber(num)) {
                    var oldPageNumber = scope.pagination.current;

                    scope.pages = generatePagesArray(num, paginationService.getCollectionLength(paginationId), paginationService.getItemsPerPage(paginationId), paginationRange);
                    scope.pagination.current = num;
                    updateRangeValues();

                    // if a callback has been set, then call it with the page number as the first argument
                    // and the previous page number as a second argument
                    if (scope.onPageChange) {
                        scope.onPageChange({
                            newPageNumber : num,
                            oldPageNumber : oldPageNumber
                        });
                    }
                }
            }

            function generatePagination() {
                if (paginationService.isRegistered(paginationId)) {
                    var page = parseInt(paginationService.getCurrentPage(paginationId)) || 1;
                    scope.pages = generatePagesArray(page, paginationService.getCollectionLength(paginationId), paginationService.getItemsPerPage(paginationId), paginationRange);
                    scope.pagination.current = page;
                    scope.pagination.last = scope.pages[scope.pages.length - 1];
                    if (scope.pagination.last < scope.pagination.current) {
                        scope.setCurrent(scope.pagination.last);
                    } else {
                        updateRangeValues();
                    }
                }
            }

            /**
             * This function updates the values (lower, upper, total) of the `scope.range` object, which can be used in the pagination
             * template to display the current page range, e.g. "showing 21 - 40 of 144 results";
             */
            function updateRangeValues() {
                if (paginationService.isRegistered(paginationId)) {
                    var currentPage = paginationService.getCurrentPage(paginationId),
                        itemsPerPage = paginationService.getItemsPerPage(paginationId),
                        totalItems = paginationService.getCollectionLength(paginationId);

                    scope.range.lower = (currentPage - 1) * itemsPerPage + 1;
                    scope.range.upper = Math.min(currentPage * itemsPerPage, totalItems);
                    scope.range.total = totalItems;
                }
            }
            function isValidPageNumber(num) {
                return (numberRegex.test(num) && (0 < num && num <= scope.pagination.last));
            }
        }

        /**
         * Generate an array of page numbers (or the '...' string) which is used in an ng-repeat to generate the
         * links used in pagination
         *
         * @param currentPage
         * @param rowsPerPage
         * @param paginationRange
         * @param collectionLength
         * @returns {Array}
         */
        function generatePagesArray(currentPage, collectionLength, rowsPerPage, paginationRange) {
            var pages = [];
            var totalPages = Math.ceil(collectionLength / rowsPerPage);
            var halfWay = Math.ceil(paginationRange / 2);
            var position;

            if (currentPage <= halfWay) {
                position = 'start';
            } else if (totalPages - halfWay < currentPage) {
                position = 'end';
            } else {
                position = 'middle';
            }

            var ellipsesNeeded = paginationRange < totalPages;
            var i = 1;
            while (i <= totalPages && i <= paginationRange) {
                var pageNumber = calculatePageNumber(i, currentPage, paginationRange, totalPages);

                var openingEllipsesNeeded = (i === 2 && (position === 'middle' || position === 'end'));
                var closingEllipsesNeeded = (i === paginationRange - 1 && (position === 'middle' || position === 'start'));
                if (ellipsesNeeded && (openingEllipsesNeeded || closingEllipsesNeeded)) {
                    pages.push('...');
                } else {
                    pages.push(pageNumber);
                }
                i ++;
            }
            return pages;
        }

        /**
         * Given the position in the sequence of pagination links [i], figure out what page number corresponds to that position.
         *
         * @param i
         * @param currentPage
         * @param paginationRange
         * @param totalPages
         * @returns {*}
         */
        function calculatePageNumber(i, currentPage, paginationRange, totalPages) {
            var halfWay = Math.ceil(paginationRange/2);
            if (i === paginationRange) {
                return totalPages;
            } else if (i === 1) {
                return i;
            } else if (paginationRange < totalPages) {
                if (totalPages - halfWay < currentPage) {
                    return totalPages - paginationRange + i;
                } else if (halfWay < currentPage) {
                    return currentPage - halfWay + i;
                } else {
                    return i;
                }
            } else {
                return i;
            }
        }
    }

    /**
     * This filter slices the collection into pages based on the current page number and number of items per page.
     * @param paginationService
     * @returns {Function}
     */
    function itemsPerPageFilter(paginationService) {

        return function(collection, itemsPerPage, paginationId) {
            if (typeof (paginationId) === 'undefined') {
                paginationId = DEFAULT_ID;
            }
            if (!paginationService.isRegistered(paginationId)) {
                throw 'pagination directive: the itemsPerPage id argument (id: ' + paginationId + ') does not match a registered pagination-id.';
            }
            var end;
            var start;
            if (angular.isObject(collection)) {
                itemsPerPage = parseInt(itemsPerPage) || 9999999999;
                if (paginationService.isAsyncMode(paginationId)) {
                    start = 0;
                } else {
                    start = (paginationService.getCurrentPage(paginationId) - 1) * itemsPerPage;
                }
                end = start + itemsPerPage;
                paginationService.setItemsPerPage(paginationId, itemsPerPage);

                if (collection instanceof Array) {
                    // the array just needs to be sliced
                    return collection.slice(start, end);
                } else {
                    // in the case of an object, we need to get an array of keys, slice that, then map back to
                    // the original object.
                    var slicedObject = {};
                    angular.forEach(keys(collection).slice(start, end), function(key) {
                        slicedObject[key] = collection[key];
                    });
                    return slicedObject;
                }
            } else {
                return collection;
            }
        };
    }

    /**
     * Shim for the Object.keys() method which does not exist in IE < 9
     * @param obj
     * @returns {Array}
     */
    function keys(obj) {
        if (!Object.keys) {
            var objKeys = [];
            for (var i in obj) {
                if (obj.hasOwnProperty(i)) {
                    objKeys.push(i);
                }
            }
            return objKeys;
        } else {
            return Object.keys(obj);
        }
    }

    /**
     * This service allows the various parts of the module to communicate and stay in sync.
     */
    function paginationService() {

        var instances = {};
        var lastRegisteredInstance;

        this.registerInstance = function(instanceId) {
            if (typeof instances[instanceId] === 'undefined') {
                instances[instanceId] = {
                    asyncMode: false
                };
                lastRegisteredInstance = instanceId;
            }
        };

        this.deregisterInstance = function(instanceId) {
            delete instances[instanceId];
        };
        
        this.isRegistered = function(instanceId) {
            return (typeof instances[instanceId] !== 'undefined');
        };

        this.getLastInstanceId = function() {
            return lastRegisteredInstance;
        };

        this.setCurrentPageParser = function(instanceId, val, scope) {
            instances[instanceId].currentPageParser = val;
            instances[instanceId].context = scope;
        };
        this.setCurrentPage = function(instanceId, val) {
            instances[instanceId].currentPageParser.assign(instances[instanceId].context, val);
        };
        this.getCurrentPage = function(instanceId) {
            var parser = instances[instanceId].currentPageParser;
            return parser ? parser(instances[instanceId].context) : 1;
        };

        this.setItemsPerPage = function(instanceId, val) {
            instances[instanceId].itemsPerPage = val;
        };
        this.getItemsPerPage = function(instanceId) {
            return instances[instanceId].itemsPerPage;
        };

        this.setCollectionLength = function(instanceId, val) {
            instances[instanceId].collectionLength = val;
        };
        this.getCollectionLength = function(instanceId) {
            return instances[instanceId].collectionLength;
        };

        this.setAsyncModeTrue = function(instanceId) {
            instances[instanceId].asyncMode = true;
        };

        this.setAsyncModeFalse = function(instanceId) {
            instances[instanceId].asyncMode = false;
        };

        this.isAsyncMode = function(instanceId) {
            return instances[instanceId].asyncMode;
        };
    }

    /**
     * This provider allows global configuration of the template path used by the dir-pagination-controls directive.
     */
    function paginationTemplateProvider() {

        var templatePath = 'angularUtils.directives.dirPagination.template';
        var templateString;

        /**
         * Set a templateUrl to be used by all instances of <dir-pagination-controls>
         * @param {String} path
         */
        this.setPath = function(path) {
            templatePath = path;
        };

        /**
         * Set a string of HTML to be used as a template by all instances
         * of <dir-pagination-controls>. If both a path *and* a string have been set,
         * the string takes precedence.
         * @param {String} str
         */
        this.setString = function(str) {
            templateString = str;
        };

        this.$get = function() {
            return {
                getPath: function() {
                    return templatePath;
                },
                getString: function() {
                    return templateString;
                }
            };
        };
    }
})();

/**
 * Created by psybo-03 on 29/11/16.
 */

//AdminController

app.controller('adminController', function ($scope, $location, $http, $rootScope, $filter, $window) {
    $scope.employees = [];
    $scope.error = {};
    var base_url = $scope.baseUrl = $location.protocol() + "://" + location.host;
    $rootScope.base_url = base_url;
    $rootScope.public_url = $scope.baseUrl = $location.protocol() + "://" + location.host;

    $scope.newuser = {};
    $scope.formdisable = false;
    $scope.paginations = [5, 10, 20, 25];
    $scope.numPerPage = 5;

    $scope.format = 'yyyy/MM/dd';
    //$scope.date = new Date();
    $scope.user = {};
    $scope.paginations = [5, 10, 20, 25];
    $scope.numPerPage = 5;

    load_user();

    function load_user() {
        var url = $rootScope.base_url + '/admin/user';
        $http.get(url).then(function (response) {
            if (response.data) {
                $scope.user = response.data.username;
                $scope.newuser.username = $scope.user;
                console.log(response.data.username);
            }
        });
    }

    $scope.login = function () {
        console.log('login');
        var fd = new FormData();
        angular.forEach($scope.user, function (item, key) {
            fd.append(key, item);
        });

        var url = $rootScope.base_url + '/login/verify';
        $http.post(url, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false}
        })
            .success(function (data, status, headers) {
                $window.location.href = '/admin/#';
            })
            .error(function (data, status, header) {
                console.log('login error');
                console.log(data);
                $scope.error = data;
                $scope.showerror = true;
            });
    };

    $scope.changeProfile = function () {
        $rootScope.loading = true;
        var fd = new FormData();
        angular.forEach($scope.newuser, function (item, key) {
            fd.append(key, item);
        });
        var url = $rootScope.base_url + '/admin/change/submit';
        $http.post(url, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false}
        })
            .success(function (data, status, headers) {
                $rootScope.loading = false;
                console.log('profile changed');
                $scope.showmsg = true;
                $scope.formdisable = true;
            })
            .error(function (data, status, headers) {
                $rootScope.loading = false;
                $scope.showerror = true;
            });
    };

    $scope.reset = function () {
        $scope.newuser = {};
        load_user();
        $scope.newuser.username = $scope.user;
        $scope.showerror = false;
        $scope.showmsg = false;
        $scope.formdisable = false;
    };

    $scope.cancel = function () {
        $window.location.href = '/admin/#';
    };

});


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
    $scope.loading = false;

    $scope.numPerPage = 8;


    loadGallery();
    function loadGallery() {
        $scope.loading = true;
        $http.get($rootScope.base_url + '/admin/gallery/get-all').then(function (response) {
            if (response.data) {
                $scope.galleries = response.data;
                console.log($scope.galleries);
                $scope.loading = false;
            } else {
                console.log('No data found');
                $scope.message = 'No data found';
                $scope.loading = false;
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
        $scope.loading = true;
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
                    $scope.loading = false;
                })
                .error(function (data, status, hedears) {
                    console.log('edit error');
                    console.log(data);
                    $scope.loading = false;
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
                    $scope.loading = false;
                })
                .error(function (data, status, hedears) {
                    console.log('add error');
                    console.log(data);
                    $scope.loading = false;
                });
        }
    };

    $scope.showGalleryFiles= function (item) {
        console.log(item);
        $scope.galleryfiles = item;
    };

    $scope.deleteImage= function (item) {
        $scope.loading = true;
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
                $scope.loading = false;
            })
            .error(function (data, status, headers) {
                console.log('delete image error');
                console.log(data);
                $scope.loading = false;
            });
    };

    $scope.deleteGallery= function (item) {
        $scope.loading = true;
        var url = $rootScope.base_url + '/admin/gallery/delete';
        action.post(item, url)
            .success(function (data, status, headers) {
                console.log('gallery deleted');
                var index = $scope.galleries.indexOf(item);
                $scope.galleries.splice(index, 1);
                $scope.loading = false;
            })
            .error(function (data,status,headers) {
                console.log('delete error');
                console.log(data);
                $scope.loading = false;
            });
    };

});
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
            $scope.loading = true;
            if (response.data) {
                $scope.showtable = true;
                $scope.portfolios = response.data;
                console.log($scope.portfolios);
                $scope.loading = false;
            }else{
                console.log('No data found');
                $scope.showtable = false;
                $scope.message = 'No data Found';
                $scope.loading = false;
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
                    $scope.loading = false;
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
                    $scope.loading = false;

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
                    $scope.loading = false;

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
                    $scope.loading = false;

                });
        }
    };

    $scope.deletePortfolio = function (item) {
        $scope.loading = true;
        var id = item['id'];
        $http.delete($rootScope.base_url + '/admin/portfolio/delete/' + id)
            .success(function (data, status, headers) {
                console.log(data);
                alert(data);
                loadPortfolio();
                $scope.loading = false;
            });
    };

    $scope.deleteImage = function(item) {
        $scope.loading = true;
            var url = $rootScope.base_url + '/admin/portfolio/delete-image';
            var data = item;
            action.post(data, url)
                .success(function (data, headers, status) {
                    console.log('portfolio file deleted');
                    var index = $scope.item_files.indexOf(item);
                    $scope.item_files.splice(index, 1);
                    $scope.loading = false;
                })
                .error(function (data,headers,status) {
                    console.log('portfolio file delete error');
                    $scope.loading = false;
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
    $scope.filespre = [];


    loademployee();

    function loademployee() {
        $scope.loading = true;
        $http.get($rootScope.base_url + '/admin/employee').then(function (response) {
            if (response.data) {
                $scope.employees = response.data;
                $scope.showtable = true;
                $scope.loading = false;
            }else {
                console.log('No data found!');
                $scope.showtable = false;
                $scope.message = 'No Data Found';
                $scope.loading = false;
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
        $scope.loading = true;
        if ($scope.newemployee.linkedin != undefined) {
            var string = $scope.newemployee.linkedin;
            if (!~string.indexOf("http")) {
                $scope.newemployee.linkedin = "http://" + string;
            }
        }
        if ($scope.newemployee.facebook != undefined) {
            var string = $scope.newemployee.facebook;
            if (!~string.indexOf("http")) {
                $scope.newemployee.facebook = "http://" + string;
            }
        }
        if ($scope.newemployee.twitter != undefined) {
            var string = $scope.newemployee.twitter;
            if (!~string.indexOf("http")) {
                $scope.newemployee.twitter = "http://" + string;
            }
        }
        if ($scope.newemployee.googleplus != undefined) {
            var string = $scope.newemployee.googleplus;
            if (!~string.indexOf("http")) {
                $scope.newemployee.googleplus = "http://" + string;
            }
        }
        if ($scope.newemployee.github != undefined) {
            var string = $scope.newemployee.github;
            if (!~string.indexOf("http")) {
                $scope.newemployee.github = "http://" + string;
            }
        }

        //Add data to form data
        var fd = new FormData();
        var i = 0;
        angular.forEach($scope.newemployee, function (item, key) {
            fd.append(key, item);
        });
        angular.forEach($scope.files.photo, function (item, key) {
            fd.append('photo', item);
            i++;
        });

        if ($scope.newemployee.id) {
            console.log('edit');
            var url = $rootScope.base_url + '/admin/employee/edit/' + $scope.newemployee.id;

            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    $scope.newemployee = {};
                    $scope.showform = false;
                    $scope.employees.push(data);
                    loademployee();
                    $scope.newemployee = {};
                    $scope.filespre = [];
                    $scope.loading = false;
                })
                .error(function (data, status, heders) {
                    console.log(data);
                    $scope.loading = false;
                });
        }else {
            var url = $rootScope.base_url + '/admin/employee/add';
            $http.post(url, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            })
                .success(function (data, status, headers) {
                    console.log(data);
                    $scope.employees.push(data);
                    loademployee();
                    $scope.newemployee = {};
                    $scope.showform = false;
                    $scope.filespre = [];
                    $scope.loading = false;
                })
                .error(function (data, status, heders) {
                    console.log(data);
                    $scope.loading = false;
                });
        }

    };

    $scope.deleteEmployee = function (item) {
        $scope.loading = true;
        console.log(item);
        var id = item['id'];
        var url = $rootScope.base_url + '/admin/employee/delete/' + id;
        var data = item;
        action.post(data,url)
            .success(function (data, status, headers) {
                console.log('deleted');
                var index = $scope.employees.indexOf(item);
                $scope.employees.splice(index, 1);
                alert(data);
                loademployee();
                $scope.loading = false;
            })
            .error(function (data, status, headers) {
                console.log('delete error');
                console.log(data);
                $scope.loading = false;
            });
    };
});
/**
 * Created by psybo-03 on 21/12/16.
 */
app.controller('teamController',function($scope,$rootScope,$http,$location) {

    $scope.teams = [];

    $rootScope.base_url = $scope.baseUrl = $location.protocol() + "://" + location.host;

    loadTeam();

    function loadTeam() {
        $http.get($rootScope.base_url + '/load_team').then(function(response) {
            if (response.data) {
                $scope.teams = response.data;
                console.log($scope.teams);
            }else {
                $scope.message = 'No data Found';
                console.log('No data Found');
            }
        })
    }


})
