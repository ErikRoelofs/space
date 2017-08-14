angular.module('game').directive('openGame', ['lobbyService', function(lobbyService) {

    return {
        restrict: 'E',
        templateUrl: 'directives/open-game/template.html',
        scope: {
            game: '=',
            canJoin: '@',
            isHost: '='
        },
        link: function(scope) {
            scope.value = '';

            scope.joinGame = function() {
                lobbyService.joinGame(scope.game, scope.value);
            };

            scope.launchGame = function() {
                lobbyService.launchGame(scope.game);
            };
        }
    };

}]);
