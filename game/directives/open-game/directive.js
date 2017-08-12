angular.module('game').directive('openGame', ['lobbyService', function(lobbyService) {

    return {
        restrict: 'E',
        templateUrl: 'directives/open-game/template.html',
        scope: {
            game: '='
        },
        link: function(scope) {
            scope.value = 'derp';

            scope.joinGame = function() {
                lobbyService.joinGame(scope.game, scope.value);
            }
        }
    };

}]);
