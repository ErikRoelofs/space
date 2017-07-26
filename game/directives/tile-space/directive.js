angular.module('game').directive('tileSpace', ['$http', 'piecesService', 'turnService', function($http, piecesService, turnService) {
    return {
        restrict: 'E',
        scope: {
            tile: '=',
        },
        templateUrl: "directives/tile-space/template.html",
        link: function(scope) {
            scope.$watch(function() {
                return turnService.getCurrentTurn();
            }, function() {
                scope.myPieces = piecesService.getPiecesForTileAndTurn(scope.tile, turnService.getCurrentTurn());
            });
        }
    }
}]);
