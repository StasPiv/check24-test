<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entry;
use AppBundle\Handler\EntryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'entries' => $this->get('handler.entry')->getEntries(EntryHandler::LIMIT_PER_PAGE, $request->get('offset')),
            'show_link_to_old' => $request->get('offset') * EntryHandler::LIMIT_PER_PAGE < $this->get('handler.entry')
        ->getEntriesCount() - 1
    ,
            'offset' => $request->get('offset')
        ]);
    }

    /**
     * @Route("/entries", name="entries")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function entriesAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/entries.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'entries' => $this->get('handler.entry')->getEntries(null),
        ]);
    }

    /**
     * @Route("/rss", name="rss", defaults={"_format"="xml"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rssAction(Request $request)
    {
        return $this->render('default/rss.html.twig', [
            'entries' => $this->get('handler.entry')->getEntries(null),
        ]);
    }

    /**
     * @Route("/entries/{entry}", name="entry")
     * @param Request $request
     * @param Entry $entry
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function entryAction(Request $request, Entry $entry)
    {
        // replace this example code with whatever you need
        return $this->render('default/entry.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'entry' => $entry,
        ]);
    }

    /**
     * @Route("/entries/{entry}/add-comment", name="add-comment")
     * @param Request $request
     * @param Entry $entry
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentAction(Request $request, Entry $entry)
    {
        $this->get('handler.entry')->addComment($entry, $request->request->all());

        return $this->redirectToRoute('entry', ['entry' => $entry->getId()]);
    }
}
