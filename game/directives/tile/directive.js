angular.module('game').directive('tile', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            tile: '='
        },
        templateUrl: "directives/tile/template.html",
        link: function(scope) {
        }
    }
}]);