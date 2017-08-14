angular.module('game').directive('playerList', ['playersService', function(playersService) {
    return {
        restrict: 'E',
        scope: {
        },
        templateUrl: "directives/player-list/template.html",
        link: function(scope) {
            scope.players = playersService.getPlayers();
        }
    }
}]);
