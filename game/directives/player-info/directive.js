angular.module('game').directive('playerInfo', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            player: '@'
        },
        templateUrl: "directives/player-info/template.html",
        link: function(scope) {
            /*
            $http.get('/player/' + scope.player + '/info').then(function(response) {
                scope.player = response.data;
            });

            $http.get('/player/' + scope.player + '/currentOrders').then(function(response) {
                scope.orders = response.data;
            });
            */
        }
    }
}]);