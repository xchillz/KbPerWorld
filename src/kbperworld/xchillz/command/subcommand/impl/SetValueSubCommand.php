<?php

declare(strict_types=1);

namespace kbperworld\xchillz\command\subcommand\impl;

use kbperworld\xchillz\command\subcommand\SubCommand;
use kbperworld\xchillz\KbPerWorld;
use languages\xchillz\langs\Language;
use pocketmine\command\CommandSender;

final class SetValueSubCommand extends SubCommand
{

    public function execute(CommandSender $sender, string $commandLabel, string $subCommandLabel, array $args, Language $language)
    {
        if (!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage($language->getMessage('KNOCKBACK_SET_VALUE_USAGE', [
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

        foreach ($args as $arg) {
            $splitSet = explode('=', strtolower($arg));

            $key = $splitSet[0];

            if (!in_array($key, [
                'horizontal_knockback',
                'vertical_knockback',
                'attack_cooldown',
                'can_revert',
                'maximum_height'
            ])) {
                $sender->sendMessage($language->getMessage('UNKNOWN_KNOCKBACK_ATTRIBUTE', [
                    '<attribute>' => $key
                ]));
                continue;
            }

            if (!isset($splitSet[1])) {
                $sender->sendMessage($language->getMessage('KNOCKBACK_MUST_ASSIGN_A_VALUE', [
                    '<key>' => $splitSet[0]
                ]));
                continue;
            }

            $value = $splitSet[1];

            if (!is_numeric($value)) {
                $sender->sendMessage($language->getMessage('KNOCKBACK_VALUE_MUST_BE_NUMERIC', [
                    '<value>' => $value
                ]));
                continue;
            }

            $strKey = 'set' . str_replace('_', '', ucwords($key, '_'));

            $knockback->{$strKey}($value);

            $config->setNested("$knockbackId.$key", strpos($value, '.') !== false ? (float) $value : (int) $value);
            $sender->sendMessage($language->getMessage('KNOCKBACK_VALUE_MODIFIED', [
                '<key>' => $key,
                '<value>' => $value
            ]));
        }

        $config->save();
        $sender->sendMessage($language->getMessage('KNOCKBACK_VALUES_MODIFIED', [
            '<knockback_id>' => $knockbackId
        ]));
    }

}