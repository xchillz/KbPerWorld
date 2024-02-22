<?php

declare(strict_types=1);

namespace kbperworld\xchillz;

use kbperworld\xchillz\command\KnockbackCommand;
use kbperworld\xchillz\knockback\KnockbackManager;
use kbperworld\xchillz\listener\PlayerCreationListener;
use languages\xchillz\exception\LanguageNotFoundException;
use languages\xchillz\LanguageAPI;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

final class KbPerWorld extends PluginBase
{

    /** @var Config */
    private static $knockbackConfig;
    /** @var KnockbackManager */
    private static $knockbackManager;

    public function onEnable()
    {
        $this->saveResource('knockbacks.json');
        $this->saveResource('messages.json');

        try {
            LanguageAPI::getInstance()->getLanguageManager()->loadMessages(
                json_decode(file_get_contents($this->getDataFolder() . 'messages.json'), true)
            );
        } catch (LanguageNotFoundException $exception) {
            $this->getLogger()->logException($exception);
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        self::$knockbackConfig = new Config($this->getDataFolder() . 'knockbacks.json', Config::JSON);
        self::$knockbackManager = new KnockbackManager(self::$knockbackConfig);

        $this->getServer()->getCommandMap()->register('kbperworld', new KnockbackCommand());

        $this->getServer()->getPluginManager()->registerEvents(new PlayerCreationListener(), $this);
    }

    public static function getKnockbackConfig(): Config
    {
        return self::$knockbackConfig;
    }

    public static function getKnockbackManager(): KnockbackManager
    {
        return self::$knockbackManager;
    }

}