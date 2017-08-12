angular.module('game', []).config(['$locationProvider', function($locationProvider) {
    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
    });
}]).run(['$location', '$http', 'loginService', '$rootScope', function($location, $http, loginService, $rootScope) {
	if(window.location.pathname != '/game/login.html') {
        var token = loginService.getToken();
        if (token) {
            $http.defaults.headers.common['X-Access-Token'] = 'Bearer ' + token;
            $rootScope.gameId = $location.search().id;
        }
        else {
            window.location = '/game/login.html';
        }
    }


}]).service('piecesService', function () {
    var pieces = [];
    return {
        setAllPieces: function (thePieces) {
            pieces = thePieces;
        },
        getPiecesForTileAndTurn: function (tile, turn) {
            return pieces.filter(function (item) {
                return item.tileId == tile.id && item.typeId != 1 && item.turnId == turn;
            });
        },
        getPiecesForPlanetAndTurn: function (planet, turn) {
            return pieces.filter(function (item) {
                return item.tileId == planet.tileId && item.turnId == turn;
            });
        },
        getPlanetForTileAndTurn: function (tile, turn) {
            var planets = pieces.filter(function (item) {
                return item.tileId == tile.id && item.typeId == 1 && item.turnId == turn;
            });
            if(planets.length) {
                return planets[0];
            }
            return null;
        },
		getPieceById: function(id) {
			return pieces.filter(function(item) {
				return item.id == id;
			})[0];
		}
    };
}).service('pieceTypesService', function () {
    var pieceTypes = [];
    return {
        setAllPieceTypes: function (thePieceTypes) {
            pieceTypes = thePieceTypes;
        },
        getPieceTypeForPiece: function (piece) {
            return pieceTypes.filter(function (item) {
                return item.id == piece.typeId
            })[0];
        },
		getPieceTypeById: function(id) {
        	return pieceTypes.filter(function (item) {
        		return item.id == id;
			})[0];
		},
        getAll: function() {
            return pieceTypes;
        }
    }
}).service('boardService', function () {
    var board = {};
    return {
        setBoard: function (theBoard) {
            board = theBoard;
        },
        getTileByCoordinatesAndTurn: function (coords, turn) {
            var tile = null;
            angular.forEach(board[turn - 1].tiles, function (item) {
                if (item.coordinates[0] == coords[0] && item.coordinates[1] == coords[1]) {
                    tile = item;
                }
            })
            return tile;
        },
		getTileByIdAndTurn: function(id, turn) {
			var tile = null;
			angular.forEach(board[turn - 1].tiles, function (item) {
				if (item.id == id) {
					tile = item;
				}
			})
			return tile;
		}
    }
}).service('playersService', function () {
    var players = {};
    return {
        setPlayers: function (thePlayers) {
            players = thePlayers;
        },
        getPlayer: function (id) {
            var player = null;
            angular.forEach(players, function (item) {
                if (item.id == id) {
                    player = item;
                }
            })
            return player;
        },
		getPlayers: function() {
        	return players;
		}
    }
}).service('historyService', function () {
	var history = [];
	return {
		setHistory: function(theHistory) {
			history = theHistory;
		},
		getHistory: function(turn) {
			return history[turn];
		}

	}
}).service('orderService', ['turnService', function (turnService) {
	var orders = [];
	return {
		setOrders: function(theOrders) {
			orders = theOrders;
		},
		getCurrentOrderForTileAndPlayer: function(tile, player) {
            var currentTileOrder = orders[turnService.getCurrentTurn() - 1]
				.filter(function(item) { return item.ownerId == player})
				.filter(function(item) { return item.data.tile === tile.id });
            if(currentTileOrder.length) {
            	return currentTileOrder[0];
			}
			return false;
		},
		getPreviousActivityForTile: function(tile) {
			if(!orders[turnService.getCurrentTurn() - 2]) {
				return [];
			}
            return orders[turnService.getCurrentTurn() - 2]
                .filter(function(item) { return item.data.tile === tile.id });
		},
		getOrders: function(turn) {
			return orders[turn];
		},
		getOrdersForPlayer: function(turn, player) {
			return orders[turn].filter(function(item) { return item.ownerId == player});
		},
		getCurrentOrdersForPlayer: function(player) {
            return orders[turnService.getCurrentTurn() - 1].filter(function(item) { return item.ownerId == player});
		},
		getPreviousTurnOrders: function() {
			if(turnService.getCurrentTurn() < 2) {
				return [];
			}
            return orders[turnService.getCurrentTurn() - 2];
		}

	}
}]).service('activePlayerService', function () {
	var data = {};
	return {
		setData: function (theData) {
			data = theData;
		},
		activePlayerId: function() {
			return 1;
		},
		color: function() {
			return '#ff0000';
		},
		getResources: function () {
			return data.resources;
		},
		tileHasOrder: function(tile) {
			return false;
		}
	}
}).service('detailsCommand', ['$rootScope', function($rootScope) {
	return {
		entityClicked: function(type, entity) {
			$rootScope.$broadcast('details.show', type, entity);
		},
		unload: function() {
			$rootScope.$broadcast('details.close');
		},
		load: function(data) {
		}
	}
}]).service('tacticalCommand',['$rootScope', function($rootScope) {
	return {
		entityClicked: function(type, entity) {
			if(type == 'piece') {
				$rootScope.$broadcast('tactical.add', entity.piece);
			}
		},
		unload: function() {
		},
		load: function(tile) {
			$rootScope.$broadcast('tactical.show', tile);
		}
	}
}]).service('turnService', [function() {
    var turn = 1;
    var lastTurn = 1;
    return {
		setLatestTurn: function(turnNum) {
			turn = turnNum;
			lastTurn = turnNum;
		},
		getCurrentTurn: function() {
			return turn;
		},
		getLatestTurn: function() {
			return lastTurn;
		},
		showNextTurn: function() {
            turn = Math.min( turn + 1, lastTurn );
		},
		showPreviousTurn: function() {
			turn = Math.max( turn - 1, 0 );
		},
		hasNextTurn: function() {
			return turn < lastTurn;
		},
		hasPreviousTurn: function() {
			return turn > 1;
		},
		showingLastTurn: function() {
			return turn === lastTurn;
		}
	}
	return self;
}]).service('objectiveService', [function() {
	var objectives = [];
	var claims = [];

	var attachToObjective = function(claim) {
		angular.forEach(objectives, function(objective) {
            if(!objective.claimed) {
            	objective.claimed = [];
			}
			if(objective.id == claim.objectiveId) {
				objective.claimed.push(claim);
			}
		});
	};
	return {
		setObjectives: function(theObjectives, theClaims) {
			objectives = theObjectives;
			claims = theClaims;
			angular.forEach(claims, function(item) {
				attachToObjective(item);
			});
		},
		getObjectives: function() {
			return objectives;
		}
	}
}]).service('gameService', [function() {
	var game;
	return {
		setGame: function(theGame) {
			game = theGame;
		},
		getGame: function() {
			return game;
		},
	}
}]).service('loginService', ['$http','$window', function($http, $window) {
	return {
		authenticate: function(username, password) {
			var vars = {
				_username: username,
				_password: password
			}
			return $http.post('/api/login', vars).then(function(response) {
				$window.localStorage.setItem('token', response.data.token);
			});
		},
		getToken: function() {
            return $window.localStorage.getItem('token');
		}
	}
}]).service('lobbyService', ['$http', function($http) {
	return {
		getMyGames: function() {
			return $http.get('/lobby/myGames');
		},
		getOpenGames: function() {
			return $http.get('/lobby/openGames');
		},
		joinGame: function(game, password) {
			password = password || false;
			return $http.post('/lobby/joinGame/' + game.id, {password: password});
		}
	}
}]).service('userService', ['$http', function($http) {
	return {
		getMyUserInfo: function() {
			return $http.get('/user/myInfo');
		}
	}
}]);
