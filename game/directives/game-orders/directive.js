angular.module('game').directive('gameOrders', ['$http', 'pieceTypesService', 'orderService', 'turnService', function($http, pieceTypesService, orderService, turnService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-orders/template.html",
        link: function(scope) {

            scope.$watch(function() {
                return turnService.getCurrentTurn();
            }, function() {
                scope.orders = orderService.getPreviousTurnOrders();
            });

        }

    }
}]);
