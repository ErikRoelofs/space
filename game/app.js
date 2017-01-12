angular.module('game', []).service('piecesService', function () {
    var pieces = [];
    return {
        setAllPieces: function (thePieces) {
            pieces = thePieces;
        },
        getPiecesForTile: function (tile) {
            return pieces.filter(function (item) {
                return item.location.type == 'space' && item.location.coordinates[0] == tile.coordinates[0] && item.location.coordinates[1] == tile.coordinates[1];
            });
        },
        getPiecesForPlanet: function (planet) {
            return pieces.filter(function (item) {
                return item.location.type == 'planet' && item.location.id == planet.id;
            });
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