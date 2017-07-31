angular.module('game').directive('gameInfo', ['$http', 'gameService', function($http, gameService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-info/template.html",
        link: function(scope) {
            scope.gameData = gameService.getGame();
        }

    }
}]);
