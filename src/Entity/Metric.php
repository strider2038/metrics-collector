<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\MetricRepository")
 */
class Metric
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="metrics")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="name")
     */
    private $section;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @ORM\Embedded(class=Format::class)
     *
     * @var Format
     */
    private $format;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $orderIndex;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Value::class, mappedBy="metric", orphanRemoval=true)
     */
    private $metricValues;

    public function __construct()
    {
        $this->format = new Format();
        $this->metricValues = new ArrayCollection();
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
    public function getMetricValues(): Collection
    {
        return $this->metricValues;
    }

    public function addMetricValue(Value $metricValue): self
    {
        if (!$this->metricValues->contains($metricValue)) {
            $this->metricValues[] = $metricValue;
            $metricValue->setMetric($this);
        }

        return $this;
    }

    public function removeMetricValue(Value $metricValue): self
    {
        if ($this->metricValues->contains($metricValue)) {
            $this->metricValues->removeElement($metricValue);
            // set the owning side to null (unless already changed)
            if ($metricValue->getMetric() === $this) {
                $metricValue->setMetric(null);
            }
        }

        return $this;
    }
}
