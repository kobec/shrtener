<?php

namespace App\Controller;

use App\Model\User\UseCase\Url\Click\Dto;
use App\Model\User\UseCase\Url\Click\Handler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShortenerController
 * @package App\Controller
 */
class ShortenerController extends AbstractController
{

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
     * @Route("/{shortUrl}", name="shortener")
     */
    public function index(Request $request, $shortUrl, Handler $handler): Response
    {
        $dto = new Dto($shortUrl);
        try {
            $userUrl = $handler->handle($dto);
            return $this->redirect($userUrl);
        } catch (\DomainException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
        }
        return $this->render('welcome/index.html.twig', [
            'controller_name' => 'WelcomeController',
        ]);
    }
}
