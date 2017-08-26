<?php

namespace Plu\Service;

use Plu\Entity\ChannelMessage;
use Plu\Entity\User;
use Plu\Repository\ChannelMessageRepository;
use Plu\Repository\ChannelRepository;
use Plu\Repository\ChannelUserRepository;

class ChatService
{

    /**
     * @var ChannelRepository
     */
    protected $channelRepo;

    /**
     * @var ChannelUserRepository
     */
    protected $channelUserRepo;

    /**
     * @var ChannelMessageRepository
     */
    protected $channelMessageRepo;

    /**
     * @var User;
     */
    protected $user;

    /**
     * ChatService constructor.
     * @param ChannelRepository $channelRepo
     * @param ChannelUserRepository $channelUserRepo
     * @param ChannelMessageRepository $channelMessageRepo
     * @param User $user
     */
    public function __construct(ChannelRepository $channelRepo, ChannelUserRepository $channelUserRepo, ChannelMessageRepository $channelMessageRepo, User $user)
    {
        $this->channelRepo = $channelRepo;
        $this->channelUserRepo = $channelUserRepo;
        $this->channelMessageRepo = $channelMessageRepo;
        $this->user = $user;
    }

    public function buildChannel($channelId) {
        $channel = $this->channelRepo->findByIdentifier($channelId);
        $users = $this->channelUserRepo->findByChannel($channel);
        $messages = $this->channelMessageRepo->findByChannel($channel);

        $channel->users = $users;
        $channel->messages = $messages;

        return $channel;
    }

    public function sendMessage($channelId, $content) {
        $message = new ChannelMessage();
        $message->channelId = $channelId;
        $message->content = $content;
        $message->posterId = $this->user->id;
        $message->posted = new \DateTime();
        $this->channelMessageRepo->add($message);

        return $message;
    }

}
