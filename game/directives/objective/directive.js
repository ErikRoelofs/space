angular.module('game').directive('objective', [ '$http',
    function($http) {
    return {
        restrict: 'E',
        scope: {
            objective: '='
        },
        templateUrl: "directives/objective/template.html",
        link: function(scope) {

            scope.claim = function() {
                var player = 1;
                var order = {
                    objectiveId: scope.objective.id
                };

                $http.post('/order/' + player + '/place/claimObjective', order).then(function(response) {
                    console.log(response);
                },function(error) {
                    console.log(error);
                })
            };
        }
    }
}]);
