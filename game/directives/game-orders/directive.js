angular.module('game').directive('gameOrders', ['$http', 'pieceTypesService', 'orderService', function($http, pieceTypesService, orderService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-orders/template.html",
        link: function(scope) {

			scope.orders = orderService.getPreviousTurnOrders();
        }

    }
}]);
