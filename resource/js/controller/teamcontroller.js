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
