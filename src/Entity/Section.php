<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 */
class Section
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=25, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $orderIndex;

    /**
     * @ORM\OneToMany(targetEntity=Metric::class, mappedBy="section", orphanRemoval=true)
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
}
