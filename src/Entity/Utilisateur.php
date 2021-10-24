<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(
 *  fields = {"mail"},
 *  message = "L'email est déjà utilisé"
 * )
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Length(min="4", minMessage="Votre mot de passe doit faire au minimum 4 caractères")
     */
    private $password;

    /**
     * @EqualTo(propertyPath="password", message="Les mots de passe doivent identiques")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUserIdentifier()
    {
        
    }

    public function eraseCredentials() {

    }

    public function getSalt() {

    }

    public function getRoles() {
        return ['ROLE_USER'];
    }

}