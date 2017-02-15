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
        }
    }
}]);