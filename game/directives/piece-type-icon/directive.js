angular.module('game').directive('pieceTypeIcon', [ function() {
    return {
        restrict: 'E',
        scope: {
            pieceType: '=',
            color: '=?',
        },
        templateUrl: "directives/piece-type-icon/template.html",
        link: function(scope) {
            scope.img = scope.pieceType.name.toLowerCase();
        }

    }
}]);
