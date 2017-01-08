angular.module('game').directive('planet', ['$http', 'piecesService', function($http, piecesService) {
    return {
        restrict: 'E',
        scope: {
            planet: '='
        },
        templateUrl: "directives/planet/template.html",
        link: function(scope) {
            scope.pieces = piecesService.getPiecesForPlanet(scope.planet);
        }
    }
}]);