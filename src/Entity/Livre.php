<?php

namespace App\Entity;

use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190)]
    private ?string $titre = null;

    #[ORM\Column(nullable: true)]
    private ?int $anee_edition = null;

    #[ORM\Column]
    private ?int $nombre_pages = null;

    #[ORM\Column(length: 190, nullable: true)]
    private ?string $code_isbn = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auteur $auteur = null;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Emprunt::class)]
    private Collection $emprunts;

    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'livres')]
    private Collection $genres;

    public function __construct()
    {
        $this->emprunts = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAneeEdition(): ?int
    {
        return $this->anee_edition;
    }

    public function setAneeEdition(?int $anee_edition): static
    {
        $this->anee_edition = $anee_edition;

        return $this;
    }

    public function getNombrePages(): ?int
    {
        return $this->nombre_pages;
    }

    public function setNombrePages(int $nombre_pages): static
    {
        $this->nombre_pages = $nombre_pages;

        return $this;
    }

    public function getCodeIsbn(): ?string
    {
        return $this->code_isbn;
    }

    public function setCodeIsbn(?string $code_isbn): static
    {
        $this->code_isbn = $code_isbn;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    /**
     * @return Emprunt
     */
    public function getEmprunt(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivre($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLivre() === $this) {
                $emprunt->setLivre(null);
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

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
            $genre->addLivre($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeLivre($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getId();
    }

    /**
     * @return Bollean
     */
    public function isDisponible(Livre $livre): bool
    {
        $emp = $livre->getEmprunt();

        if ($emp->$this->getDateRetour() == null) {
            return true;
        } else {
            return false;
        }
    }
}
