angular.module('game', []).service('piecesService', function () {
    var pieces = [];
    return {
        setAllPieces: function (thePieces) {
            pieces = thePieces;
        },
        getPiecesForTile: function (tile) {
            return pieces.filter(function (item) {
                return item.tileId == tile.id && item.typeId != 4 && item.typeId != 1;
            });
        },
        getPiecesForPlanet: function (planet) {
            return pieces.filter(function (item) {
                return item.tileId == planet.tileId && item.typeId == 4;
            });
        },
        getPlanetForTile: function (tile) {
            var planets = pieces.filter(function (item) {
                return item.tileId == tile.id && item.typeId == 1;
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
        getTileByCoordinates: function (coords) {
            var tile = null;
            angular.forEach(board.tiles, function (item) {
                if (item.coordinates[0] == coords[0] && item.coordinates[1] == coords[1]) {
                    tile = item;
                }
            })
            return tile;
        },
		getTileById: function(id) {
			var tile = null;
			angular.forEach(board.tiles, function (item) {
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
}).service('orderService', function () {
	var orders = [];
	return {
		setOrders: function(theOrders) {
			orders = theOrders;
		},
		getOrders: function(turn) {
			return orders[turn];
		},
		getOrdersForPlayer: function(turn, player) {
			return orders[turn].filter(function(item) { return item.ownerId == player});
		}

	}
}).service('activePlayerService', function () {
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
}]);
