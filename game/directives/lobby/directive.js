angular.module('game').directive('lobby', ['lobbyService', function(lobbyService) {

    return {
        restrict: 'E',
        templateUrl: 'directives/lobby/template.html',
        link: function(scope) {
            lobbyService.getMyGames().then(function(response) {
                scope.activeGames = response.data.active;
                scope.archivedGames = response.data.archived;
            });

            lobbyService.getOpenGames().then(function(response) {
                scope.openGames = response.data;
            });

            scope.waitingGames = [];

            scope.play = function(game) {
                window.location = '/game/play.html?id=' + game.id;
            }
        }
    };

}]);
