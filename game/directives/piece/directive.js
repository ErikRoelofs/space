angular.module('game').directive('piece', ['$http', 'pieceTypesService', 'playersService', '$rootScope', function($http, pieceTypesService, playersService, $rootScope) {
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
            };
            scope.color = function() {
                return playersService.getPlayer(scope.piece.ownerId).color;
            };
            scope.showDetails = function() {
                $rootScope.$broadcast('detailsPane.show', 'piece', { piece: scope.piece, pieceType: scope.pieceType });
            }
        }

    }
}]);