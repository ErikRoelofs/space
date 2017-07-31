angular.module('game').directive('objectiveOrder', ['orderService', function(orderService) {
    return {
        restrict: 'E',
        scope: {
            order: '='
        },
        templateUrl: "directives/orders/objective/template.html",
        link: function(scope) {
		}

    }
}]);
