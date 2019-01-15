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
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * EntryHandler constructor.
     * @param EntityManager $manager
     * @param \Swift_Mailer $mailer
     */
    public function __construct(EntityManager $manager, \Swift_Mailer $mailer)
    {
        $this->manager = $manager;
        $this->mailer = $mailer;
    }

    public function getEntries($limit = self::LIMIT_PER_PAGE, $offset = 1): Collection
    {
        return new ArrayCollection(
            $this->manager->getRepository('AppBundle:Entry')
                ->findBy([], ['id' => Criteria::DESC], $limit, $offset)
        );
    }

    public function getEntriesCount(): int
    {
        return $this->manager->getRepository('AppBundle:Entry')->count([]);
    }

    public function checkIp(string $ip): bool
    {
        $collection = new ArrayCollection(
            $this->manager->getRepository('AppBundle:BannedIp')->findBy(['ip' => $ip])
        );

        return $collection->isEmpty();
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

        // send email
        $this->sendEmail($entry, $comment);
    }

    /**
     * @param Entry $entry
     * @param Comment $comment
     */
    private function sendEmail(Entry $entry, Comment $comment): void
    {
        $message = (new \Swift_Message(sprintf('comment by %s on: %s', $comment->getName(), $entry->getTitle())))
            ->setFrom('send@check24.test')
            ->setTo($comment->getEmail())
            ->setBody(
                sprintf('comment by %s on: %s', $comment->getName(), $entry->getTitle()),
                'text/html'
            )/*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $this->mailer->send($message);
    }
}