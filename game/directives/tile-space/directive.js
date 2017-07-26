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
                tryRedo();
            });

            scope.$watch(function() {
                return scope.tile && scope.tile.id;
            }, function() {
                tryRedo();
            });

            function tryRedo() {
                if(scope.tile && scope.tile.id) {
                    scope.myPieces = piecesService.getPiecesForTileAndTurn(scope.tile, turnService.getCurrentTurn());
                }
            }
        }
    }
}]);
