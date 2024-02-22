<?php

declare(strict_types=1);

namespace kbperworld\xchillz\knockback;

final class Knockback
{

    /** @var float */
    private $horizontalKnockback;
    /** @var float */
    private $verticalKnockback;
    /** @var float */
    private $maximumHeight;
    /** @var int */
    private $attackCooldown;
    /** @var bool */
    private $canRevert;
    /** @var string[] */
    private $worlds;

    public function __construct(float $horizontalKnockback, float $verticalKnockback, float $maxHeight, int $attackCooldown, bool $canRevert, array $worlds)
    {
        $this->horizontalKnockback = $horizontalKnockback;
        $this->verticalKnockback = $verticalKnockback;
        $this->maximumHeight = $maxHeight;
        $this->attackCooldown = $attackCooldown;
        $this->canRevert = $canRevert;
        $this->worlds = $worlds;
    }

    public function containsWorld(string $worldName): bool
    {
        return in_array($worldName, $this->worlds);
    }

    public function setHorizontalKnockback(float $horizontalKnockback)
    {
        $this->horizontalKnockback = $horizontalKnockback;
    }

    public function setVerticalKnockback(float $verticalKnockback)
    {
        $this->verticalKnockback = $verticalKnockback;
    }

    public function setAttackCooldown(int $attackCooldown)
    {
        $this->attackCooldown = $attackCooldown;
    }

    public function setMaximumHeight(float $maximumHeight)
    {
        $this->maximumHeight = $maximumHeight;
    }

    public function setCanRevert(bool $canRevert)
    {
        $this->canRevert = $canRevert;
    }

    public function setWorlds(array $worlds)
    {
        $this->worlds = $worlds;
    }

    public function canRevert(): bool
    {
        return $this->canRevert;
    }

    public function getMaximumHeight(): float
    {
        return $this->maximumHeight;
    }

    public function getHorizontalKnockback(): float
    {
        return $this->horizontalKnockback;
    }

    public function getVerticalKnockback(): float
    {
        return $this->verticalKnockback;
    }

    public function getAttackCooldown(): int
    {
        return $this->attackCooldown;
    }

}