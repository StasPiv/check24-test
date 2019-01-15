<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 14.01.19
 * Time: 21:13
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entry
 * @package CoreBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="`banned_ip`")
 */
class BannedIp
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $ip;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return (string)$this->ip;
    }

    /**
     * @param string $ip
     * @return BannedIp
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }
}