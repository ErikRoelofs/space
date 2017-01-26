angular.module('game').directive('gameHistory', ['$http', 'pieceTypesService', function($http, pieceTypesService) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        templateUrl: "directives/game-history/template.html",
        link: function(scope) {
            /*
            $http.get('/game/' + scope.game + '/history').then(function(response) {
                scope.game = response.data;
            })
            */
        }

    }
}]);