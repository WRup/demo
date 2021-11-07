<?php

namespace App\Controller;

use App\Repository\AccessoryRepository;
use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage lab contents in the public part of the site.
 *
 * @Route("/lab")
 *
 */
class AccessoryController extends AbstractController
{

    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, methods="GET", name="lab_index")
     * @Cache(smaxage="10")
     *
     */
    public function home(Request $request, AccessoryRepository $accessories, TagRepository $tags): Response
    {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }
        $latestAccessories = $accessories->findLatest(1, $tag);

        return $this->render('lab/index.html.twig', [
            'paginator' => $latestAccessories,
            'tagName' => $tag ? $tag->getName() : null,
        ]);
    }


    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, methods="GET", name="lab_index")
     * @Route("/rss.xml", defaults={"page": "1", "_format"="xml"}, methods="GET", name="lab_rss")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods="GET", name="lab_index_paginated")
     * @Cache(smaxage="10")
     *
     */
    public function index(Request $request, int $page, string $_format, AccessoryRepository $accessories, TagRepository $tags): Response
    {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }
        $latestAccessories = $accessories->findLatest($page, $tag);

        return $this->render('lab/index.' . $_format . '.twig', [
            'paginator' => $latestAccessories,
            'tagName' => $tag ? $tag->getName() : null,
        ]);
    }


    /**
     * @Route("/search", methods="GET", name="lab_search")
     */
    public function search(Request $request, AccessoryRepository $accessories): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 10);

        if (!$request->isXmlHttpRequest()) {
            return $this->render('lab/search.html.twig', ['query' => $query]);
        }

        $foundAccessories = $accessories->findBySearchQuery($query, $limit);

        $results = [];
        foreach ($foundAccessories as $accessory) {
            $results[] = [
                'name' => htmlspecialchars($accessory->getName(), \ENT_COMPAT | \ENT_HTML5),
                'manufacturer' => htmlspecialchars($accessory->getManufacturer(), \ENT_COMPAT | \ENT_HTML5),
                'model' => htmlspecialchars($accessory->getModel(), \ENT_COMPAT | \ENT_HTML5),
                'content' => htmlspecialchars($accessory->getContent(), \ENT_COMPAT | \ENT_HTML5),
                'url' => htmlspecialchars($accessory->getUrl(), \ENT_COMPAT | \ENT_HTML5),
            ];
        }

        return $this->json($results);
    }

}