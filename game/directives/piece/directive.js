angular.module('game').directive('piece', ['$http', 'pieceTypesService', function($http, pieceTypesService) {
    return {
        restrict: 'E',
        scope: {
            piece: '='
        },
        templateUrl: "directives/piece/template.html",
        link: function(scope) {
            scope.pieceType = pieceTypesService.getPieceTypeForPiece(scope.piece);
        }
    }
}]);