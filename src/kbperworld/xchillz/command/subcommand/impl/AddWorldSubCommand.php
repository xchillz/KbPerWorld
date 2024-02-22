<?php

declare(strict_types=1);

namespace kbperworld\xchillz\command\subcommand\impl;

use kbperworld\xchillz\command\subcommand\SubCommand;
use kbperworld\xchillz\KbPerWorld;
use languages\xchillz\langs\Language;
use pocketmine\command\CommandSender;
use pocketmine\Server;

final class AddWorldSubCommand extends SubCommand
{

    public function execute(CommandSender $sender, string $commandLabel, string $subCommandLabel, array $args, Language $language)
    {
        if (!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage($language->getMessage('KNOCKBACK_ADD_WORLD_USAGE', [
                '<command>' => $commandLabel,
                '<subcommand>' => $subCommandLabel
            ]));
            return;
        }

        $knockbackId = array_shift($args);
        $knockback = KbPerWorld::getKnockbackManager()->getKnockback($knockbackId);

        if ($knockback === null) {
            $sender->sendMessage($language->getMessage('UNKNOWN_KNOCKBACK', [
                '<provided_id>' => $knockbackId
            ]));
            return;
        }

        $config = KbPerWorld::getKnockbackConfig();
        $worlds = $config->getNested("$knockbackId.worlds");

        foreach ($args as $worldName) {
            if (!Server::getInstance()->isLevelLoaded($worldName)) {
                $sender->sendMessage($language->getMessage('KNOCKBACK_WORLD_DOES_NOT_EXIST', [
                    '<world>' => $worldName
                ]));
                continue;
            }

            if (in_array($worldName, $worlds)) {
                $sender->sendMessage($language->getMessage('KNOCKBACK_WORLD_REGISTERED_ALREADY', [
                    '<world>' => $worldName
                ]));
                continue;
            }

            $worlds[] = $worldName;
            $sender->sendMessage($language->getMessage('KNOCKBACK_WORLD_ADDED', [
                '<world>' => $worldName
            ]));
        }

        $config->setNested("$knockbackId.worlds", $worlds);
        $config->save();

        $knockback->setWorlds($worlds);

        $sender->sendMessage($language->getMessage('KNOCKBACK_WORLDS_ADDED'));
    }

}