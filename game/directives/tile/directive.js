angular.module('game').directive('tile', ['$http', 'boardService', '$rootScope', function($http, boardService, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            coords: '=',
        },
        templateUrl: "directives/tile/template.html",
        link: function(scope) {
            scope.tile = boardService.getTileByCoordinates(scope.coords);

            scope.showDetails = function() {
                $rootScope.$broadcast('detailsPane.show', 'tile', scope.tile);
            }
        }
    }
}]);