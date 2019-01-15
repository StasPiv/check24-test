<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 14.01.19
 * Time: 21:13
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entry
 * @package CoreBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="`entry`")
 */
class Entry
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var ArrayCollection|Comment[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", cascade={"persist"}, mappedBy="entry")
     * @ORM\OrderBy({"date" = "DESC"})
     */
    protected $comments;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Entry
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        if (mb_strlen($this->description) <= 1000) {
            return $this->description;
        }

        $cutDescription = substr($this->description, 0, 1000);

        $lastPeriodBefore1000 = strrpos($cutDescription, '.');

        if ($lastPeriodBefore1000 !== false) {
            return substr($this->description, 0, $lastPeriodBefore1000) . '...';
        }

        $lastSpaceBefore1000 = strrpos($cutDescription, ' ');

        return substr($this->description, 0, $lastSpaceBefore1000) . '...';
    }

    /**
     * @param string $description
     * @return Entry
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return (string)$this->author;
    }

    /**
     * @param string $author
     * @return Entry
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getDateString(): string
    {
        return (new \DateTime)->diff($this->date)->days === 0 ? 'Today' : $this->date->format('d.m.Y H:i:s');
    }

    /**
     * @param \DateTime $date
     * @return Entry
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Comment[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }
}