angular.module('game').directive('piece', ['$http', 'playersService', '$rootScope', function($http, playersService, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            piece: '='
        },
        templateUrl: "directives/piece/template.html",
        link: function(scope) {
            scope.color = playersService.getPlayer(scope.piece.ownerId).color;

            scope.$watch(function() {
                return scope.piece;
            }, function() {
                scope.color = playersService.getPlayer(scope.piece.ownerId).color;
            });

            scope.clicked = function() {
                $rootScope.$broadcast('entity.clicked', 'piece', { piece: scope.piece, pieceType: scope.piece });
            }
        }

    }
}]);
