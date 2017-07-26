angular.module('game').directive('pieceTypeIcon', [ function() {
    return {
        restrict: 'E',
        scope: {
            pieceType: '=',
            color: '=?',
        },
        templateUrl: "directives/piece-type-icon/template.html",
        link: function(scope) {
            scope.$watch(function() {
                return scope.pieceType && scope.pieceType.name;
            }, function() {
                scope.img = scope.pieceType.name.toLowerCase();
                scope.title = scope.pieceType.name;
            });

        }

    }
}]);
