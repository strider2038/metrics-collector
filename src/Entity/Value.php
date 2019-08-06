<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={
 *          "groups"={"valueRead"}
 *     },
 *     denormalizationContext={
 *          "groups"={"valueWrite"}
 *     },
 *     attributes={
 *          "order"={"createdAt": "DESC"}
 *     },
 *     itemOperations={"get", "delete"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ValueRepository")
 */
class Value
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"valueRead"})
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Metric", inversedBy="values")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="name")
     * @Assert\NotBlank()
     * @Groups({"valueRead", "valueWrite"})
     *
     * @var Metric
     */
    private $metric;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Groups({"valueRead", "valueWrite"})
     *
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float", nullable=false)
     * @Assert\NotBlank()
     * @Groups({"valueRead", "valueWrite"})
     *
     * @var float
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max="100")
     * @Groups({"valueRead", "valueWrite"})
     *
     * @var string
     */
    private $tag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetric(): ?Metric
    {
        return $this->metric;
    }

    public function setMetric(?Metric $metric): self
    {
        $this->metric = $metric;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
