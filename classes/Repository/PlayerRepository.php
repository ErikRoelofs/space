<?php

namespace Plu\Repository;


use Plu\Entity\Game;
use Symfony\Component\Security\Core\User\User;

class PlayerRepository extends BaseRepository
{
    /**
     * @var User
     */
    protected $user;

    public function __construct($db, $converter, $user)
    {
        $this->user = $user;
        return parent::__construct($db, $converter, 'player');
    }

    public function findByGame(Game $game) {
        $sql = "SELECT * FROM $this->tableName WHERE gameId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $game->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

    public function findForCurrentUserByGame(Game $game) {
        $sql = "SELECT * FROM $this->tableName p INNER JOIN users u ON u.id = p.userId WHERE p.gameId = ? AND u.username = ?";
        $row = $this->db->fetchAssoc($sql, array((int) $game->id, $this->user->getUsername()));
        return $this->converter->fromDB($this->tableName, $row);

    }
}
