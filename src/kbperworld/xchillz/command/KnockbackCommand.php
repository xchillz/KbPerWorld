<?php

declare(strict_types=1);

namespace kbperworld\xchillz\command;

use customplayer\xchillz\player\CustomPlayer;
use kbperworld\xchillz\command\subcommand\impl\AddWorldSubCommand;
use kbperworld\xchillz\command\subcommand\impl\CreateKnockbackSubCommand;
use kbperworld\xchillz\command\subcommand\impl\SetValueSubCommand;
use kbperworld\xchillz\command\subcommand\SubCommand;
use languages\xchillz\LanguageAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

final class KnockbackCommand extends Command
{

    /** @var SubCommand[] */
    private $subCommands;

    public function __construct()
    {
        parent::__construct("knockback", "Modify a specific knockback", "/knockback", [ 'kb' ]);

        $this->subCommands = [
            'setvalue' => new SetValueSubCommand([ 'setval', 'setvalues', 'setvals' ]),
            'createknockback' => new CreateKnockbackSubCommand([ 'createkb', 'newknockback', 'newkb' ]),
            'addworld' => new AddWorldSubCommand([ 'includeworld' ])
        ];
    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        $language = LanguageAPI::getInstance()->getLanguageManager()->getDefaultLanguage();

        if ($sender instanceof CustomPlayer) {
            if (!$sender->hasPermission('knockback.command')) {
                $sender->sendMessage($language->getMessage('KNOCKBACK_NO_PERMISSION', [
                    '<command>' => $commandLabel
                ]));
                return;
            }

            $language = $sender->getLanguage();
        }

        if (!isset($args[0])) {
            $sender->sendMessage($language->getMessage('KNOCKBACK_USAGE', [
                '<command>' => $commandLabel,
                '<subcommands>' => join('|', array_keys($this->subCommands))
            ]));
            return;
        }

        $subCommand = $this->getSubCommand($args[0]);

        if ($subCommand === null) {
            $sender->sendMessage($language->getMessage('KNOCKBACK_USAGE', [
                '<command>' => $commandLabel,
                '<subcommands>' => join('|', array_keys($this->subCommands))
            ]));
            return;
        }

        $subCommand->execute($sender, $commandLabel, $args[0], array_slice($args, 1), $language);
    }

    /**
     * @return SubCommand|null
     */
    private function getSubCommand(string $identifier)
    {
        $identifier = strtolower($identifier);

        if (isset($this->subCommands[$identifier])) return $this->subCommands[$identifier];

        foreach ($this->subCommands as $subCommand) {
            if ($subCommand->containsAlias($identifier)) return $subCommand;
        }

        return null;
    }

}