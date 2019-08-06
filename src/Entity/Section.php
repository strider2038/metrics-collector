<?php

namespace App\Entity;

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
 *          "groups"={"sectionRead", "metricRead", "formatRead"}
 *     },
 *     denormalizationContext={
 *          "groups"={"sectionWrite"}
 *     },
 *     attributes={
 *          "order"={"orderIndex": "ASC"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity({"name"})
 * @UniqueEntity({"orderIndex"})
 */
class Section
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=25, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="25")
     * @Assert\Regex(pattern="/[a-z0-9_]+/", message="Allowed symbols: a-z, 0-9, _.")
     * @Groups({"sectionRead", "sectionWrite"})
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="100")
     * @Groups({"sectionRead", "sectionWrite"})
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=false, unique=true)
     * @Groups({"sectionRead", "sectionWrite"})
     *
     * @var int
     */
    private $orderIndex;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"sectionRead"})
     *
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"sectionRead"})
     *
     * @var \DateTimeInterface
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Metric::class, mappedBy="section", orphanRemoval=true)
     * @Groups({"sectionRead"})
     * @ApiSubresource()
     *
     * @var Metric[]
     */
    private $metrics;

    public function __construct()
    {
        $this->metrics = new ArrayCollection();
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
     * @return Collection|Metric[]
     */
    public function getMetrics(): Collection
    {
        return $this->metrics;
    }

    public function addMetric(Metric $metric): self
    {
        if (!$this->metrics->contains($metric)) {
            $this->metrics[] = $metric;
            $metric->setSection($this);
        }

        return $this;
    }

    public function removeMetric(Metric $metric): self
    {
        if ($this->metrics->contains($metric)) {
            $this->metrics->removeElement($metric);
            // set the owning side to null (unless already changed)
            if ($metric->getSection() === $this) {
                $metric->setSection(null);
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
