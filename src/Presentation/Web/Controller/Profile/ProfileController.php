<?php

namespace App\Presentation\Web\Controller\Profile;

use App\Domain\Users\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Users\Service\UploadService;
use App\Presentation\Web\Form\Profile\AvatarType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Users\UseCase\GetReadingListUserUseCase;
use App\Application\Users\UseCase\GetUserLibraryStatsUseCase;
use App\Application\UserBooks\UseCase\GetPreferredBookUseCase;
use App\Presentation\Web\Transformer\UserBooksTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route("/profile", name: "profile_")]
final class ProfileController extends AbstractController
{
    public function __construct(private readonly Security $security){}

    #[Route("/{name}-{id}", name: "index")]
    public function index(Users $user, GetReadingListUserUseCase $getReadingListUserUseCase, GetPreferredBookUseCase $getPreferredBookUseCase, GetUserLibraryStatsUseCase $getUserLibraryStatsUseCase): Response
    {

        $preferredBooks = $getPreferredBookUseCase->getPreferredBook($user);

        $currentlyReading = $getReadingListUserUseCase->getReadingList($user);

        $userStats = $getUserLibraryStatsUseCase->getStats($user);

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
            'preferredBooks' => $preferredBooks,
            'currentlyReading' => $currentlyReading,
            ...$userStats
        ]);
    }

    #[Route('/edit/avatar', name: 'edit_avatar')]
    public function editAvatar(Request $request, UploadService $uploadService, EntityManagerInterface $em): Response
    {
        /** @var Users $user */
        $user = $this->security->getUser();
    
        $form = $this->createForm(AvatarType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $avatarFile = $form->get('avatar')->getData();
    
            if ($user->getAvatar()) {

                $uploadService->delete($user->getAvatar());
            }
                
            $fileName = $uploadService->upload($avatarFile);
            $user->setAvatar($fileName);
    
            $em->flush();
    
            $this->addFlash('success', 'Avatar mis à jour avec succès.');
    
            return $this->redirectToRoute('profile_index');
        }
    
        return $this->render('profile/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/api/reading-list', name: 'profile_reading_list', methods: ['GET'])]
    public function getReadingList(GetReadingListUserUseCase $getReadingListUserUseCase, UserBooksTransformer $transformer): JsonResponse
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        $currentlyReading = $getReadingListUserUseCase->getReadingList($user);

        $data = $transformer->transformMany($currentlyReading);

        return new JsonResponse($data, Response::HTTP_OK);
    }

}