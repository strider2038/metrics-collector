<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Format
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    private $decimalsCount = 2;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     *
     * @var string
     */
    private $decimalPoint = '.';

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     *
     * @var string
     */
    private $thousandsSeparator = '';

    /**
     * @ORM\Column(type="string", length=25, nullable=false)
     *
     * @var string
     */
    private $unit = '';

    public function getDecimalsCount(): int
    {
        return $this->decimalsCount;
    }

    public function setDecimalsCount(int $decimalsCount): void
    {
        $this->decimalsCount = $decimalsCount;
    }

    public function getDecimalPoint(): string
    {
        return $this->decimalPoint;
    }

    public function setDecimalPoint(string $decimalPoint): void
    {
        $this->decimalPoint = $decimalPoint;
    }

    public function getThousandsSeparator(): string
    {
        return $this->thousandsSeparator;
    }

    public function setThousandsSeparator(string $thousandsSeparator): void
    {
        $this->thousandsSeparator = $thousandsSeparator;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }
}
