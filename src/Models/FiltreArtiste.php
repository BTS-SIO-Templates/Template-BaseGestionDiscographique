<?php 

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Artiste;

class FiltreArtiste
{

    /**
     * 
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères",
     * )
     */

    private ?string $nom = null;

    public Artiste $artiste;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
}
