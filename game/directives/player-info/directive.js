angular.module('game').directive('playerInfo', ['$http', 'orderService', function($http, orderService) {
    return {
        restrict: 'E',
        scope: {
            player: '@'
        },
        templateUrl: "directives/player-info/template.html",
        link: function(scope) {
			scope.orders = orderService.getOrdersForPlayer(0, 1);
        }
    }
}]);