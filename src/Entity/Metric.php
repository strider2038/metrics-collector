<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={
 *          "groups"={"metricRead", "metricSectionRead", "formatRead"}
 *     },
 *     denormalizationContext={
 *          "groups"={"metricWrite", "metricSectionWrite", "formatWrite"}
 *     },
 *     attributes={
 *          "order"={"orderIndex": "ASC"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\MetricRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity({"name"})
 * @UniqueEntity({"orderIndex"})
 */
class Metric
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="25")
     * @Assert\Regex(pattern="/[a-z_]+/", message="Allowed symbols: a-z, _.")
     * @Groups({"metricRead", "metricWrite"})
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="metrics")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="name")
     * @Assert\NotBlank()
     * @Groups({"metricSectionRead", "metricSectionWrite"})
     *
     * @var string
     */
    private $section;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="100")
     * @Groups({"metricRead", "metricWrite"})
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Embedded(class=Format::class)
     * @Assert\Valid()
     * @Groups({"metricRead", "metricWrite"})
     * @ApiProperty(
     *     swaggerContext={
     *         "type"="object",
     *         "properties"={
     *              "decimalsCount"={
     *                  "type"="integer",
     *                  "min"="0",
     *                  "max"="6"
     *              },
     *              "decimalPoint"={
     *                  "type"="string",
     *                  "maxLength"=1
     *              },
     *              "thousandsSeparator"={
     *                  "type"="string",
     *                  "maxLength"=1
     *              },
     *              "unit"={
     *                  "type"="string",
     *                  "maxLength"=25
     *              }
     *         }
     *     }
     * )
     *
     * @var Format
     */
    private $format;

    /**
     * @ORM\Column(type="integer", nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Groups({"metricRead", "metricWrite"})
     *
     * @var int
     */
    private $orderIndex;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"metricRead"})
     *
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"metricRead"})
     *
     * @var \DateTimeInterface
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Value::class, mappedBy="metric", orphanRemoval=true)
     * @ApiSubresource()
     *
     * @var Value[]
     */
    private $values;

    public function __construct()
    {
        $this->format = new Format();
        $this->values = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFormat(): Format
    {
        return $this->format;
    }

    public function setFormat(Format $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getOrderIndex(): int
    {
        return $this->orderIndex;
    }

    public function setOrderIndex(int $orderIndex): self
    {
        $this->orderIndex = $orderIndex;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection|Value[]
     */
    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(Value $value): self
    {
        if (!$this->values->contains($value)) {
            $this->values[] = $value;
            $value->setMetric($this);
        }

        return $this;
    }

    public function removeValue(Value $value): self
    {
        if ($this->values->contains($value)) {
            $this->values->removeElement($value);
            // set the owning side to null (unless already changed)
            if ($value->getMetric() === $this) {
                $value->setMetric(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');

        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable('now');
        }
    }
}
