angular.module('game').directive('objective', ['$http', '$rootScope', 'playersService', 'orderService',
    function ($http, $rootScope, playersService, orderService) {
        return {
            restrict: 'E',
            scope: {
                objective: '='
            },
            templateUrl: "directives/objective/template.html",
            link: function (scope) {

                scope.canClaim = true;
                scope.beingClaimed = false;
                angular.forEach(orderService.getCurrentOrdersForPlayer($rootScope.playerId), function(order) {
                    if(order.orderType == 'claimObjective') {
                        scope.canClaim = false;
                        if(order.data.objectiveId == scope.objective.id) {
                            scope.beingClaimed = true;
                        }
                    }
                });

                scope.claim = function () {
                    var player = $rootScope.playerId;
                    var order = {
                        objectiveId: scope.objective.id
                    };

                    $http.post('/order/' + player + '/place/claimObjective', order).then(function (response) {
                    }, function (error) {
                        console.log(error);
                    });

                };

                var describeResourceObjective = function (objective) {
                    var res = '';
                    switch (objective.params.resource) {
                        case 'social':
                            res = 'Influence';
                            break;
                        case 'industry':
                            res = 'Industry';
                            break;
                        default:
                            res = 'Unknown Resource';
                            break;
                    }
                    return 'I possess ' + objective.params.amount + ' unspent ' + res;
                };

                var describePieceObjective = function (objective) {
                    var piece = '';
                    switch (objective.params.type) {
                        case 5:
                            piece = 'Spacedocks';
                            break;
                        case 1:
                            piece = 'Planets';
                            break;
                        default:
                            piece = 'Unknown pieces?';
                            break;
                    }
                    ;
                    return 'I control ' + objective.params.amount + ' ' + piece + ' on the board.';
                };

                var describeCenterObjective = function(objective) {
                    return 'I control the planet in the center.';
                }

                scope.describeObjective = function (objective) {
                    switch (objective.type) {
                        case 'has.resource':
                            return describeResourceObjective(objective);
                        case 'has.pieces':
                            return describePieceObjective(objective);
                        case 'has.center':
                            return describeCenterObjective(objective);
                        default:
                            return 'Unknown type of objective?!';
                    }
                };

                scope.getPlayerColor = function(playerId) {
                    return playersService.getPlayer(playerId).color;
                }

            }
        }
    }]);
