angular.module('game').directive('scores', ['playersService', 'gameService', function(playersService, gameService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/scores/template.html",
        link: function(scope) {
            scope.players = playersService.getPlayers();
            scope.scores = [];
            scope.vpLimit = gameService.getGame().vpLimit;
            for( var i = 0; i <= scope.vpLimit ; i++ ) {
                scope.scores.push({
                    score: i,
                    players: scope.players.filter(function(player) { return player.score == i || ( i == scope.vpLimit && player.score > i ) ; })
                });
            }
        }

    }
}]);

