angular.module('game').directive('chatChannel', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            channelId: '@'
        },
        templateUrl: "directives/chat/template.html",
        link: function(scope) {

            scope.new = { message: ''};
            scope.postersById = {};

            $http.get('/chat/channel/' + scope.channelId ).then(function(response) {
                scope.channel = response.data;
                angular.forEach(scope.channel.users, function(user) {
                    scope.postersById[user.id] = user;
                });
            });

            scope.send = function() {
                $http.post('/chat/send/' + scope.channelId, scope.new).then(function(response) {
                   scope.new.message = '';
                   scope.channel.messages.push(response.data);
                });
            };

            scope.getPoster = function(message) {
                return scope.postersById[message.posterId].user;
            };

        }
    }
}]);
