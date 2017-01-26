angular.module('game').directive('gameSettings', ['$http', 'pieceTypesService', function($http, pieceTypesService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-settings/template.html",
        link: function(scope) {
            scope.pieceTypes = pieceTypesService.getAll();
        }
    }
}]);