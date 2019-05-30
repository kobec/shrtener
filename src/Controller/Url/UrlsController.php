<?php

declare(strict_types=1);

namespace App\Controller\Url;

use App\Model\User\Entity\User\User;
use App\Model\User\UseCase\Activate;
use App\Model\User\UseCase\Block;
use App\Model\User\UseCase\Create;
use App\Model\User\UseCase\Edit;
use App\Model\User\UseCase\Role;
use App\Model\User\UseCase\SignUp\Confirm;
use App\Model\User\UseCase\Url\Create\Dto;
use App\Model\User\UseCase\Url\Create\Form;
use App\Model\User\UseCase\Url\Create\Handler;
use App\ReadModel\User\Filter;
use App\ReadModel\User\UserFetcher;
use App\ReadModel\Work\Members\Member\MemberFetcher;
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

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param UserFetcher $fetcher
     * @return Response
     */
    public function index(Request $request/*, UserFetcher $fetcher*/): Response
    {
        /*$filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date'),
            $request->query->get('direction', 'desc')
        );*/

        return $this->render('user/url/index.html.twig', [
           /* 'pagination' => $pagination,
            'form' => $form->createView(),*/
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
                return $this->redirectToRoute('url');
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
