<?php

declare(strict_types=1);

namespace App\Controller\Url;

use App\Model\User\Entity\User\Url;
use App\ReadModel\User\Url\Log\UrlLogFetcher;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/url/statistics", name="url.log")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class UrlsLogController extends AbstractController
{
    private const PER_PAGE = 10;

    private $logger;

    /**
     * UrlsController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/{id}", name=".list")
     * @param Request $request
     * @param UrlLogFetcher $fetcher
     * @param UserInterface $user
     * @return Response
     */
    public function index(Url $url, Request $request, UrlLogFetcher $fetcher, UserInterface $user): Response
    {
        //prepare all needed data from front end
        //use UrlFetcher service to get all data
        $pagination = $fetcher->all(
            $user->getId(),
            $url->getId(),
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'id'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('user/url/log/index.html.twig', [
            'pagination' => $pagination,
            'title' => $url->getShortUrl()->getValue()
        ]);
    }

}
