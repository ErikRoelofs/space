angular.module('game').directive('tileSpace', ['$http', 'piecesService', function($http, piecesService) {
    return {
        restrict: 'E',
        scope: {
            tile: '=',
        },
        templateUrl: "directives/tile-space/template.html",
        link: function(scope) {
            scope.myPieces = piecesService.getPiecesForTile(scope.tile);
        }
    }
}]);