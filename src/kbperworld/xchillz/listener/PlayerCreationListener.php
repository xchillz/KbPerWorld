<?php

declare(strict_types=1);

namespace kbperworld\xchillz\listener;

use kbperworld\xchillz\player\KnockbackPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;

final class PlayerCreationListener implements Listener
{

    /**
     * @priority HIGH
     */
    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(KnockbackPlayer::class);
    }

}