<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\SiteBundle\Event\SiteEvents;
use Ojs\SiteBundle\Event\ViewArticleEvent;

class ArticleController extends Controller
{

    public function articlePageAction($slug, $article_id, $issue_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $data['article'] = $em->getRepository('OjsJournalBundle:Article')->find($article_id);
        if (!$data['article']) {
            throw $this->createNotFoundException($this->get('translator')->trans('Article Not Found'));
        }
        //log article view event
        $data['schemaMetaTag'] = '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />';
        $data['meta'] = $this->get('ojs.article_service')->generateMetaTags($data['article']);
        $data['journal'] = $data['article']->getJournal();
        $data['page'] = 'journals';
        $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);

        /* @var $entity Article */
        $entity = $data['article'];
        $event = new ViewArticleEvent($entity);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(SiteEvents::VIEW_ARTICLE, $event);

        return $this->render('OjsSiteBundle:Article:article_page.html.twig', $data);
    }
}
