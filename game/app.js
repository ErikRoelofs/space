angular.module('game', []).directive('test', function() {
    return {
        restrict: 'E',
        scope: {
            cookies: '='
        },
        template: "<div>Jum jum {{ cookies }}</div>",
        link: function(scope) {
            console.log(scope.cookies);
        }
    }
}).directive('playerInfo', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            player: '@'
        },
        template: "<div>test {{ player }}. Industry: {{ data.industry }}, Social: {{ data.social }}</div>",
        link: function(scope) {
            $http.get('/player/' + scope.player + '/info').then(function(response) {
                scope.data = response.data;
            })
        }
    }
}]).directive('currentOrders', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            player: '@'
        },
        template: "<div>Current orders.<div ng-repeat='order in data'>{{ order.id }}</div></div>",
        link: function(scope) {
            $http.get('/player/' + scope.player + '/currentOrders').then(function(response) {
                scope.data = response.data;
            })
        }
    }
}]).directive('gameHistory', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        template: "<div>Game history.<div ng-repeat='turn in game.turns'>{{ turn.id }}</div></div>",
        link: function(scope) {
            $http.get('/game/' + scope.game + '/history').then(function(response) {
                scope.game = response.data;
            })
        }
    }
}]).directive('gameSettings', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            game: '@'
        },
        template: "<div>Game settings.<div ng-repeat='pieceType in game.pieceTypes'>{{ pieceType.id }}</div></div>",
        link: function(scope) {
            $http.get('/game/' + scope.game + '/settings').then(function(response) {
                scope.game = response.data;
            })
        }
    }
}]);