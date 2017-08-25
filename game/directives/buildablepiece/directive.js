angular.module('game').directive('buildablepiece', ['$http', 'playersService', '$rootScope', function($http, playersService, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            pieceType: '='
        },
        templateUrl: "directives/buildablepiece/template.html",
        link: function(scope) {
            scope.color = '#ff0000';

            scope.produce = function() {
                scope.$emit('tactical.produce', scope.pieceType);
            }
        }

    }
}]);
