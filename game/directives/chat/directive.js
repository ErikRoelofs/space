angular.module('game').directive('chatChannel', ['$http', function($http) {
    return {
        restrict: 'E',
        scope: {
            channelId: '@'
        },
        templateUrl: "directives/chat/template.html",
        link: function(scope) {

            scope.new = { message: ''};

            $http.get('/chat/channel/' + scope.channelId ).then(function(response) {
                scope.users = response.data.users;
                scope.messages = response.data.messages;
                scope.channel = response.data.channel;
            });

            scope.send = function() {
                $http.post('/chat/send/' + scope.channelId, scope.new).then(function(response) {
                   scope.new.message = '';
                   scope.messages.push(response.data);
                });
            }

        }
    }
}]);
