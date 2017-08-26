<?php

namespace Plu\Service;

use Plu\Entity\Channel;
use Plu\Entity\ChannelMessage;
use Plu\Entity\User;
use Plu\Repository\ChannelMessageRepository;
use Plu\Repository\ChannelRepository;
use Plu\Repository\ChannelUserRepository;
use Plu\Repository\UserRepository;

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
     * @var UserRepository
     */
    protected $userRepository;

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
    public function __construct(ChannelRepository $channelRepo, ChannelUserRepository $channelUserRepo, ChannelMessageRepository $channelMessageRepo, UserRepository $userRepo, User $user)
    {
        $this->channelRepo = $channelRepo;
        $this->channelUserRepo = $channelUserRepo;
        $this->channelMessageRepo = $channelMessageRepo;
        $this->userRepo = $userRepo;
        $this->user = $user;
    }

    public function buildChannel($channelId) {
        $channel = $this->channelRepo->findByIdentifier($channelId);
        $users = $this->channelUserRepo->findByChannel($channel);
        $messages = $this->channelMessageRepo->findByChannel($channel);

        $channel->users = $users;
        foreach($channel->users as $user) {
            $user->user = $this->userRepo->findByIdentifier($user->userId);
        }
        $channel->messages = $messages;

        if(!$this->checkMembership($channel)) {
            throw new \Exception("Cannot view channel; not in this channel.");
        }

        return $channel;
    }

    public function sendMessage($channelId, $content) {

        $channel = $this->buildChannel($channelId);
        if(!$this->checkMembership($channel)) {
            throw new \Exception("Cannot send message; not in this channel.");
        }

        $message = new ChannelMessage();
        $message->channelId = $channelId;
        $message->content = $content;
        $message->posterId = $this->user->id;
        $message->posted = new \DateTime();
        $this->channelMessageRepo->add($message);

        return $message;
    }

    private function checkMembership(Channel $channel) {
        foreach($channel->users as $user) {
            if($user->id == $this->user->id) {
                return true;
            }
        }
        return false;
    }
}
