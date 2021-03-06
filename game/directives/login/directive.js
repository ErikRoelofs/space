angular.module('game').directive('login', ['loginService', 'userService', '$http', function(loginService, userService, $http) {

    return {
        restrict: 'E',
        templateUrl: 'directives/login/template.html',
        link: function(scope) {

            scope.new = {};

            scope.login = function() {
                loginService.authenticate(scope.username, scope.password).then(function(response) {
                    window.location = "/game/";
                }, function(error) {
                    scope.loginError = error.data;
                });
            };

            scope.register = function() {
                userService.registerAccount(scope.new.username, scope.new.password, scope.new.email).then(
                    function(response) {
                        loginService.authenticate(scope.new.username, scope.new.password).then(function(response) {
                            window.location = "/game/";
                        }, function(error) {
                            scope.registerError = 'registering-failed';
                        });
                    },
                    function(error) {
                        scope.registerError = error.data;
                    });
            }

            scope.clearLoginError = function() {
                scope.loginError = '';
            }

            scope.clearRegisterError = function() {
                scope.registerError = '';
            }

            $http.get('/home/stats').then(function(response) {
                scope.stats = response.data;
            })
        }
    };

}]);
