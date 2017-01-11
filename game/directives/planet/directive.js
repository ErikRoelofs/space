angular.module('game').directive('planet', ['$http', 'piecesService', function($http, piecesService) {
    return {
        restrict: 'E',
        scope: {
            planet: '=',
            showRight: '=',
        },
        templateUrl: "directives/planet/template.html",
        link: function(scope) {
            scope.pieces = piecesService.getPiecesForPlanet(scope.planet);

            scope.fighters = function() {
                return scope.pieces.filter(function(item) { return item.typeId == 13}).length;
            };
            scope.troops = function() {
                return 0;
            };
            scope.defenders = function() {
                return 0;
            };
        }
    }
}]);