angular.module('game').directive('lobby', ['lobbyService', 'userService', function(lobbyService, userService) {

    return {
        restrict: 'E',
        templateUrl: 'directives/lobby/template.html',
        link: function(scope) {

            scope.newGame = {};

            lobbyService.getMyGames().then(function(response) {
                scope.activeGames = response.data.active;
                scope.archivedGames = response.data.archived;
            });

            scope.openGames = [];
            scope.waitingGames = [];
            var haveJoined = function(game, id) {
                var joined = false;
                angular.forEach(game.players, function(player) {
                    if(player.userId == id) {
                        joined = true;
                    }
                });
                return joined;
            }

            lobbyService.getOpenGames().then(function(response) {
                userService.getMyUserInfo().then(function(subResponse){
                    angular.forEach(response.data, function(game) {
                        if(haveJoined(game, subResponse.data.id)) {
                            scope.waitingGames.push(game);
                        }
                        else {
                            scope.openGames.push(game);
                        }
                    });
                });
            });

            scope.play = function(game) {
                window.location = '/game/play.html?id=' + game.id;
            }
            userService.getMyUserInfo().then(function(response) {
                scope.user = response.data;
            })

            scope.create = function() {
                lobbyService.openGame(scope.newGame.password, scope.newGame.vpLimit).then(function(response) {
                    console.log(response);
                });
            }

            scope.isHost = function(game) {
                return game.userId == scope.user.id;
            }

            scope.logout = function() {
                window.location = '/game/login.html';
            }

        }
    };

}]);
