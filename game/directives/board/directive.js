angular.module('game').directive('board', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            board: '@'
        },
        templateUrl: "directives/board/template.html",
        link: function(scope) {
            $http.get('/board/' + scope.board + '/tiles').then(function(response) {
                scope.board = response.data;
                console.log(scope.board);
            });
        }
    }
}]);