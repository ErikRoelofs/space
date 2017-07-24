angular.module('game').directive('tacticalOrder', ['$http', 'piecesService', 'pieceTypesService', 'orderService', function($http, piecesService, pieceTypesService, orderService) {
    return {
        restrict: 'E',
        scope: {
            order: '='
        },
        templateUrl: "directives/orders/tactical/template.html",
        link: function(scope) {
			scope.getPiece = function(id) {
				return piecesService.getPieceById(id);
			};
            scope.getPieceType = function(id) {
                return pieceTypesService.getPieceTypeById(id);
            };
		}

    }
}]);
