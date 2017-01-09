angular.module('game').directive('tile', ['$http', 'boardService', function($http, boardService) {
    return {
        restrict: 'E',
        scope: {
            coords: '=',
        },
        templateUrl: "directives/tile/template.html",
        link: function(scope) {
            scope.tile = boardService.getTileByCoordinates(scope.coords);
        }
    }
}]);