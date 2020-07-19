<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/sign-in", name="sign_in")
     */
    public function signIn(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $lastAuthenticationError = $authenticationUtils->getLastAuthenticationError();
        $lastEmailAddress = $authenticationUtils->getLastUsername();

        if ($request->query->has('email')) {
            $lastEmailAddress = $request->query->get('email');
        }

        return $this->render('User/signIn.html.twig', [
            'lastAuthenticationError' => $lastAuthenticationError,
            'lastEmailAddress' => $lastEmailAddress
        ]);
    }

    /**
     * @Route("/sign-up", name="sign_up")
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sign_in');
        }

        return $this->render('User/signUp.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sign-out", name="sign_out")
     */
    public function signOut()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
