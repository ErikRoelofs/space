angular.module('game').directive('playerInfo', ['$http', 'activePlayerService', 'orderService', function($http, activePlayerService, orderService) {
    return {
        restrict: 'E',
        scope: {
            player: '@'
        },
        templateUrl: "directives/player-info/template.html",
        link: function(scope) {
			scope.orders = orderService.getOrdersForPlayer(0, 1);
			scope.resources = activePlayerService.getResources();

            scope.cancel = function(order) {
                $http.delete('/order/1/' + order.id).then(function(response) {
                    angular.forEach(scope.orders, function(item, key) {
                        if(item.id == order.id) {
                            scope.orders.splice(key, 1);
                        }
                    })
                    scope.orders = [];
                });
            }

        }
    }
}]);
