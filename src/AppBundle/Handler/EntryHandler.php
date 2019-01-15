<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 15.01.19
 * Time: 12:35
 */

namespace AppBundle\Handler;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Entry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

class EntryHandler
{
    const LIMIT_PER_PAGE = 3;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * EntryHandler constructor.
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function getEntries($limit = self::LIMIT_PER_PAGE): Collection
    {
        return new ArrayCollection(
            $this->manager->getRepository('AppBundle:Entry')
                ->findBy([], ['id' => Criteria::DESC], $limit)
        );
    }

    public function addComment(Entry $entry, array $data)
    {
        $comment = new Comment();

        $comment->setName($data['name'])
                ->setEmail($data['email'])
                ->setUrl($data['url'])
                ->setComment($data['comment'])
                ->setEntry($entry);

        try {
            $this->manager->persist($comment);
            $this->manager->flush();
        } catch (ORMException $exception) {
            dump($exception);
        }
    }
}