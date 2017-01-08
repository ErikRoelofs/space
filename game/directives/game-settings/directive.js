angular.module('game').directive('gameSettings', ['$http', 'pieceTypesService', function($http, pieceTypesService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-settings/template.html",
        link: function(scope) {
            $http.get('/game/' + scope.game + '/settings').then(function(response) {
                scope.game = response.data;
                pieceTypesService.setAllPieceTypes(scope.game.pieceTypes);
            });
        }
    }
}]);