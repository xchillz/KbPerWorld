<?php

declare(strict_types=1);

namespace kbperworld\xchillz\player;

use customplayer\xchillz\player\CustomPlayer;
use kbperworld\xchillz\KbPerWorld;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;

final class KnockbackPlayer extends CustomPlayer
{

    public function attack($damage, EntityDamageEvent $source)
    {
        parent::attack($damage, $source);

        if($source->isCancelled()) return;

        $knockback = KbPerWorld::getKnockbackManager()->getKnockback($this->getLevel()->getFolderName());

        if($knockback === null || !($source instanceof EntityDamageByEntityEvent) || !($source->getDamager()) instanceof KnockbackPlayer) return;

        $this->attackTime = $knockback->getAttackCooldown();
    }

    public function knockBack(Entity $attacker, $damage, $x, $z, $base = 0.4)
    {
        $player = $this->getPlayer();

        $knockback = KbPerWorld::getKnockbackManager()->getKnockback($this->getLevel()->getFolderName());

        if ($knockback === null || !($attacker instanceof KnockbackPlayer)) {
            parent::knockBack($attacker, $damage, $x, $z, $base);
            return;
        }

        $horizontalKnockback = $knockback->getHorizontalKnockback();
        $verticalKnockback = $knockback->getVerticalKnockback();
        $maxHeight = $knockback->getMaximumHeight();
        $canRevert = $knockback->canRevert();

        if ($maxHeight > 0.0 && !$player->isOnGround()) {
            list($max, $min) = $this->clamp($player->getY(), $attacker->getY());

            if ($max - $min >= $maxHeight) {
                $verticalKnockback *= 0.5;

                if ($canRevert) {
                    $verticalKnockback *= -1;
                }
            }
        }

        $x = $player->getX() - $attacker->getX();
        $z = $player->getZ() - $attacker->getZ();

        $f = sqrt($x * $x + $z * $z);
        if($f <= 0) {
            return;
        }

        $f = 1 / $f;

        $motion = new Vector3($this->motionX, $this->motionY, $this->motionZ);

        $motion->x /= 2;
        $motion->y /= 2;
        $motion->z /= 2;
        $motion->x += $x * $f * $horizontalKnockback;
        $motion->y += $verticalKnockback;
        $motion->z += $z * $f * $horizontalKnockback;

        $motion->y = min($motion->y, $verticalKnockback);

        $this->setMotion($motion);
    }

    private function clamp(float $first, float $second): array
    {
        return ($first > $second) ? [$first, $second] : [$second, $first];
    }

    public function getPlayer()
    {
        return $this;
    }

}