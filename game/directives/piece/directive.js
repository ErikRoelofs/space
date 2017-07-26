angular.module('game').directive('piece', ['$http', 'pieceTypesService', 'playersService', '$rootScope', function($http, pieceTypesService, playersService, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            piece: '='
        },
        templateUrl: "directives/piece/template.html",
        link: function(scope) {
            scope.pieceType = pieceTypesService.getPieceTypeForPiece(scope.piece);
            scope.color = playersService.getPlayer(scope.piece.ownerId).color;

            scope.$watch(function() {
                return scope.piece;
            }, function() {
                scope.pieceType = pieceTypesService.getPieceTypeForPiece(scope.piece);
                scope.color = playersService.getPlayer(scope.piece.ownerId).color;
                if(!scope.pieceType) {
                    console.log('no piece type?');
                    console.log(scope.piece);
                }
            });

            scope.clicked = function() {
                $rootScope.$broadcast('entity.clicked', 'piece', { piece: scope.piece, pieceType: scope.pieceType });
            }
        }

    }
}]);
