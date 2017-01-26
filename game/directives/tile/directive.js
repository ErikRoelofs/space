angular.module('game').directive('tile', ['$http', 'boardService', 'piecesService', '$rootScope', function($http, boardService, piecesService, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            coords: '=',
        },
        templateUrl: "directives/tile/template.html",
        link: function(scope) {
            scope.tile = boardService.getTileByCoordinates(scope.coords);
            scope.planet = piecesService.getPlanetForTile(scope.tile);
            scope.showDetails = function() {
                $rootScope.$broadcast('detailsPane.show', 'tile', scope.tile);
            }
        }
    }
}]);