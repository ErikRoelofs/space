angular.module('game').directive('planet', ['$http', 'piecesService', 'pieceTypesService', 'playersService', 'turnService',
    function($http, piecesService, pieceTypesService, playersService, turnService) {
    return {
        restrict: 'E',
        scope: {
            planet: '=',
            showRight: '=',
        },
        templateUrl: "directives/planet/template.html",
        link: function(scope) {

            scope.type = pieceTypesService.getPieceTypeForPiece(scope.planet);

            scope.$watch(function() {
                return turnService.getCurrentTurn();
            }, function() {
                scope.pieces = piecesService.getPiecesForPlanetAndTurn(scope.planet, turnService.getCurrentTurn());
                scope.fighters = function() {
                    return scope.pieces.filter(function(item) { return item.typeId == 4}).length;
                };
                scope.troops = function() {
                    return 0;
                };
                scope.defenders = function() {
                    return 0;
                };
            });

            scope.getBorderColor = function () {
                if(!scope.planet.ownerId) {
                    return '#ffffff';
                }
                return playersService.getPlayer(scope.planet.ownerId).color;
            }
        }
    }
}]);
