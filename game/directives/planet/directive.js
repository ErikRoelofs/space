angular.module('game').directive('planet', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            planet: '='
        },
        templateUrl: "directives/planet/template.html",
        link: function(scope) {
            console.log(scope.planet);
        }
    }
}]);