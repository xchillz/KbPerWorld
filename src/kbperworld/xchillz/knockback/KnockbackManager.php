<?php

declare(strict_types=1);

namespace kbperworld\xchillz\knockback;

use pocketmine\utils\Config;

final class KnockbackManager
{

    /** @var array<string, Knockback> */
    private $knockbacks = [];

    public function __construct(Config $config)
    {
        foreach ($config->getAll() as $knockbackId => $knockbackData) {
            $this->knockbacks[$knockbackId] = new Knockback(
                (float) $knockbackData['horizontal_knockback'],
                (float) $knockbackData['vertical_knockback'],
                (float) $knockbackData['maximum_height'],
                (int) $knockbackData['attack_cooldown'],
                (bool) $knockbackData['can_revert'],
                (array) $knockbackData['worlds']
            );
        }
    }

    public function addKnockback(string $knockbackId, Knockback $knockback)
    {
        $this->knockbacks[$knockbackId] = $knockback;
    }

    /**
     * @return Knockback|null
     */
    public function getKnockback(string $worldName)
    {
        foreach ($this->knockbacks as $knockback) {
            if ($knockback->containsWorld($worldName)) return $knockback;
        }

        return null;
    }

}