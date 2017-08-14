angular.module('game').directive('playerInfo', ['$http', 'activePlayerService', 'orderService', 'turnService', 'playersService', '$rootScope', function($http, activePlayerService, orderService, turnService, playersService, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            player: '@'
        },
        templateUrl: "directives/player-info/template.html",
        link: function(scope) {
            scope.$watch(function() {
                return turnService.getCurrentTurn();
            }, function() {
                scope.orders = orderService.getCurrentOrdersForPlayer($rootScope.playerId);
            });

			scope.resources = activePlayerService.getResources();

            scope.cancel = function(order) {
                $http.delete('/order/' + $rootScope.playerId + '/' + order.id).then(function(response) {
                    angular.forEach(scope.orders, function(item, key) {
                        if(item.id == order.id) {
                            scope.orders.splice(key, 1);
                        }
                    })
                    scope.orders = [];
                });
            }

            scope.endTurn = function() {
                $http.get('/admin/game/' + $rootScope.gameId + '/next').then(function(response) {
                    console.log('next turn. refresh!');
                })
            }

            scope.playerInfo = playersService.getPlayer($rootScope.playerId);

            scope.ready = function() {
              $http.post('/order/' + $rootScope.playerId + '/ready').then(function(response) {
                  scope.playerInfo.ready = 1;
              });
            };
            scope.notReady = function() {
                $http.post('/order/' + $rootScope.playerId + '/notReady').then(function(response) {
                    scope.playerInfo.ready = 0;
                });
            };

            scope.allowHistory = true;
            scope.$on('game.mode', function(event, mode) {
                scope.allowHistory = ( mode != 'tactical' );
            })

            scope.currentTurn = turnService.getLatestTurn;
            scope.viewingTurn = turnService.getCurrentTurn;

            scope.hasNextTurn = turnService.hasNextTurn;
            scope.hasPrevTurn = turnService.hasPreviousTurn;

            scope.nextTurn = turnService.showNextTurn;
            scope.prevTurn = turnService.showPreviousTurn;

        }
    }
}]);
