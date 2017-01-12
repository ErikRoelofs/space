angular.module('game').directive('piece', ['$http', 'pieceTypesService', function($http, pieceTypesService) {
    return {
        restrict: 'E',
        scope: {
            piece: '='
        },
        templateUrl: "directives/piece/template.html",
        link: function(scope) {
            scope.pieceType = pieceTypesService.getPieceTypeForPiece(scope.piece);
            scope.img = function() {
                return scope.pieceType.name.toLowerCase();
            }
            scope.color = function() {
                switch( parseInt(scope.piece.ownerId, 10) ) {
                    case 1: return '#ff0000';
                    case 2: return '#00ff00';
                    case 3: return '#0000ff';
                    case 4: return '#ff00ff';
                    case 5: return '#ffff00';
                    case 6: return '#00ffff';
                }
            }
        }

    }
}]);