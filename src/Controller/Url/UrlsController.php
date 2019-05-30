<?php

declare(strict_types=1);

namespace App\Controller\Url;

use App\Model\User\Entity\User\UserId;
use App\Model\User\UseCase\Url\Create\Dto;
use App\Model\User\UseCase\Url\Create\Form;
use App\Model\User\UseCase\Url\Create\Handler;
use App\ReadModel\User\Url\UrlFetcher;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/url", name="url")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class UrlsController extends AbstractController
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
     * @Route("/my", name=".my")
     * @param Request $request
     * @param UrlFetcher $fetcher
     * @return Response
     */
    public function index(Request $request, UrlFetcher $fetcher, UserInterface $user): Response
    {
        //prepare all needed data from front end
        $userId = new UserId($user->getId());
        //use UrlFetcher service to get all data
        $pagination = $fetcher->all(
            $userId,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'id'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('user/url/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/create", name=".create")
     * @param Request $request
     * @param UserInterface $user
     * @param Handler $handler
     * @return Response
     */
    public function create(Request $request, UserInterface $user, Handler $handler): Response
    {
        //prepare all needed data from front end
        $dto = new Dto($user->getId());
        $form = $this->createForm(Form::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //pass recently collected data to the create url service
                $handler->handle($dto);
                return $this->redirectToRoute('url.my');
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('user/url/create.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors()
        ]);
    }


}
