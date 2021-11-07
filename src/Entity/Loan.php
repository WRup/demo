<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoanRepository")
 * @ORM\Table(name="loan")
 *
 * Defines the properties of the Loan entity to represent the user loans of lab accessories.
 *
 */
class Loan
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Accessory
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Accessory", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $accessory;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Accessory
     */
    public function getAccessory(): Accessory
    {
        return $this->accessory;
    }

    /**
     * @param Accessory $accessory
     */
    public function setAccessory(Accessory $accessory): void
    {
        $this->accessory = $accessory;
    }




}