angular.module('game').directive('piece', ['$http', 'pieceTypesService', 'playersService', function($http, pieceTypesService, playersService) {
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
                return playersService.getPlayer(scope.piece.ownerId).color;
            }
        }

    }
}]);