<?php

declare(strict_types=1);

namespace kbperworld\xchillz\command\subcommand\impl;

use kbperworld\xchillz\command\subcommand\SubCommand;
use kbperworld\xchillz\KbPerWorld;
use kbperworld\xchillz\knockback\Knockback;
use languages\xchillz\langs\Language;
use pocketmine\command\CommandSender;

final class CreateKnockbackSubCommand extends SubCommand
{

    public function execute(CommandSender $sender, string $commandLabel, string $subCommandLabel, array $args, Language $language)
    {
        if (!isset($args[0])) {
            $sender->sendMessage($language->getMessage('CREATE_KNOCKBACK_USAGE'));
            return;
        }

        $knockbackId = $args[0];

        if (KbPerWorld::getKnockbackManager()->getKnockback($knockbackId) !== null) {
            $sender->sendMessage($language->getMessage('KNOCKBACK_EXISTS_ALREADY', [
                '<knockback_id>' => $knockbackId
            ]));
            return;
        }

        $config = KbPerWorld::getKnockbackConfig();

        $config->set($knockbackId, [
            'horizontal_knockback' => 0.4,
            'vertical_knockback' => 0.4,
            'attack_cooldown' => 10,
            'can_revert' => false,
            'maximum_height' => 0.0,
            'worlds' => []
        ]);

        $config->save();
        KbPerWorld::getKnockbackManager()->addKnockback($knockbackId, new Knockback(
            0.4,
            0.4,
            0.0,
            10,
            false,
            []
        ));

        $sender->sendMessage($language->getMessage('KNOCKBACK_CREATED_SUCCESSFULLY', [
            '<knockback_id>' => $knockbackId
        ]));
    }

}