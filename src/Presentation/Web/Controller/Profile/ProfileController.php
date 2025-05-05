<?php

namespace App\Presentation\Web\Controller\Profile;

use App\Domain\Users\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Users\Service\UploadService;
use App\Application\Users\UseCase\GetReadingListUserUseCase;
use App\Presentation\Web\Form\Profile\AvatarType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route("/profile", name: "profile_")]
final class ProfileController extends AbstractController
{
    public function __construct(private readonly Security $security){}

    #[Route("/", name: "index")]
    public function index(GetReadingListUserUseCase $getReadingListUserUseCase): Response
    {
        $user = $this->security->getUser();

        $books = $user->getUserBooks();

        $currentlyReading = $getReadingListUserUseCase->getReadingList($user);

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
            'books' => $books,
            'currentlyReading' => $currentlyReading,
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
}