<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"genre_browse", "movie_read"})
     * @Groups({"movie_browse"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"genre_browse"})
     * @Groups({"movie_read"})
     * @Groups({"movie_browse"})
     * 
     * @Assert\NotBlank
     * ? https://symfony.com/doc/5.4/reference/constraints/Length.html
     * @Assert\Length(
     *      min = 5,
     *      max = 255
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"movie_read"})
     * @Groups({"movie_browse"})
     * 
     * @Assert\NotBlank
     */
    private $duration;

    /**
     * @ORM\Column(type="float")
     * @Groups({"movie_read"})
     * @Groups({"movie_browse"})
     * 
     * @Assert\NotBlank
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     * @Groups({"movie_browse"})
     * @Assert\NotBlank
     * @Assert\Length(min = 10)
     */
    private $summary;

    /**
     * @ORM\Column(type="text")
     * @Groups({"movie_read"})
     * @Assert\NotBlank
     * @Assert\Length(min = 20)
     */
    private $synopsis;

    /**
     * @ORM\Column(type="date")
     * @Groups({"movie_read"})
     * @Assert\NotBlank
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"movie_read"})
     * 
     * @Assert\NotBlank
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movie_read"})
     * @Groups({"movie_browse"})
     * 
     * @Assert\NotBlank
     */
    private $poster;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups({"movie_read"})
     * @Groups({"movie_browse"})
     * 
     * @Assert\NotBlank
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movie")
     * @Groups({"movie_read"})
     */
    private $castings;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     * @Groups({"movie_read"})
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="movie")
     * @Groups({"movie_read"})
     */
    private $seasons;

    public function __construct()
    {
        $this->castings = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->seasons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Casting>
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovie($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->removeElement($casting)) {
            // set the owning side to null (unless already changed)
            if ($casting->getMovie() === $this) {
                $casting->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setMovie($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getMovie() === $this) {
                $season->setMovie(null);
            }
        }

        return $this;
    }
}
