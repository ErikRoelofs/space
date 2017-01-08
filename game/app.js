angular.module('game', []).service('piecesService', function() {
    var pieces = [];
    return {
        setAllPieces: function(thePieces) {
            pieces = thePieces;
        },
        getPiecesForTile: function(tile) {
            return pieces.filter(function(item) {
                return item.location.type == 'space' && item.location.coordinates[0] == tile.coordinates[0] && item.location.coordinates[1] == tile.coordinates[1];
            });
        },
        getPiecesForPlanet: function(planet) {
            return pieces.filter(function(item) {
                return item.location.type == 'planet' && item.location.id == planet.id;
            });
        }
    };
}).service('pieceTypesService', function() {
    var pieceTypes = [];
    return {
        setAllPieceTypes: function (thePieceTypes) {
            pieceTypes = thePieceTypes;
        },
        getPieceTypeForPiece: function(piece) {
            return pieceTypes.filter(function(item) { return item.id == piece.typeId})[0];
        }
    }
});