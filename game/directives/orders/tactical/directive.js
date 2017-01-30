angular.module('game').directive('tacticalOrder', ['$http', 'piecesService', 'orderService', function($http, piecesService, orderService) {
    return {
        restrict: 'E',
        scope: {
            order: '='
        },
        templateUrl: "directives/orders/tactical/template.html",
        link: function(scope) {
			scope.getPiece = function(id) {
				return piecesService.getPieceById(id);
			}
		}

    }
}]);