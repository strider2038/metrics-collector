<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Embeddable()
 */
class Format
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\GreaterThanOrEqual("0")
     * @Assert\LessThanOrEqual("6")
     * @Groups({"formatRead", "formatWrite"})
     *
     * @var int
     */
    private $decimalsCount = 2;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     * @Assert\Length(min="1", max="1")
     * @Groups({"formatRead", "formatWrite"})
     *
     * @var string
     */
    private $decimalPoint = '.';

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     * @Assert\Length(min="0", max="1")
     * @Groups({"formatRead", "formatWrite"})
     *
     * @var string
     */
    private $thousandsSeparator = '';

    /**
     * @ORM\Column(type="string", length=25, nullable=false)
     * @Assert\Length(max="25")
     * @Groups({"formatRead", "formatWrite"})
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

    public function formatValue(?float $value): string
    {
        if ($value === null) {
            $formatted = '';
        } else {
            $formatted = number_format(
                $value,
                $this->decimalsCount,
                $this->decimalPoint,
                $this->thousandsSeparator
            );

            if ($this->unit !== '') {
                $formatted .= ' ' . $this->unit;
            }
        }

        return $formatted;
    }
}
