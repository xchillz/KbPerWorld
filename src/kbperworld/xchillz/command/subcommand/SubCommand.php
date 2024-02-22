<?php

declare(strict_types=1);

namespace kbperworld\xchillz\command\subcommand;

use kbperworld\xchillz\player\KnockbackPlayer;
use languages\xchillz\langs\Language;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

abstract class SubCommand
{

    /** @var string[] */
    private $aliases;

    public function __construct(array $aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * @param ConsoleCommandSender|KnockbackPlayer $sender
     */
    public abstract function execute(CommandSender $sender, string $commandLabel, string $subCommandLabel, array $args, Language $language);

    public function containsAlias(string $providedAlias): bool
    {
        return in_array($providedAlias, $this->aliases);
    }

}