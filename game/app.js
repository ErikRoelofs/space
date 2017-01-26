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
})