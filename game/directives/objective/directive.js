angular.module('game').directive('objective', [
    function() {
    return {
        restrict: 'E',
        scope: {
            objective: '='
        },
        templateUrl: "directives/objective/template.html",
        link: function(scope) {

            scope.message = 'the message';
        }
    }
}]);
