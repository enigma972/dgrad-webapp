<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Candidat;
use App\Entity\Cv;
use App\Entity\MotivationLetter;
use App\Form\CandidatureFormType;
use App\Repository\OffreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/offres/{page}/list", name="offres", defaults={"page": "1"})
     */
    public function offres(OffreRepository $offres, $page=1, $nombreParPage=15)
    {	
    	$offres = $offres->getAll($page, $nombreParPage);
    	return $this->render('home/offres.html.twig', [
            'offres'		=>	$offres,
            'page'			=>	$page,
            'nombreParPage'	=>	ceil(count($offres)/15)
        ]);
    }

    /**
     * @Route("/offres/{id}/voir", name="offre")
     */
    public function offre(Offre $offre)
    {   
        return $this->render('home/offre.html.twig', [
            'offre'        =>  $offre
        ]);
    }

    /**
     * @Route("/offre/{id}/apply", name="offre_apply")
     */
    public function apply(Offre $offre, Request $request, FlashBagInterface $flashBag)
    {
        $candidatureIsSended = false;
        $candidat = new Candidat();
        $form = $this->createForm(CandidatureFormType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file1 = $form->get('cv')->getData();
            $file2 = $form->get('lettreDeMotivation')->getData();

            if ($file1 && $file2) {
                if ($file1->guessClientExtension() == 'pdf' && $file2->guessClientExtension() == 'pdf') {
                    $cv = new Cv();
                    $motivationLetter = new MotivationLetter();

                    $cv->preUpload($file1);
                    $motivationLetter->preUpload($file2);

                    $candidat
                            ->setOffre($offre)
                            ->setCv($cv)
                            ->setLettreDeMotivation($motivationLetter);


                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($candidat);
                    $entityManager->flush();

                    $cv->upload();
                    $motivationLetter->upload();

                    $flashBag->add('info', 'Votre candidature à bien été envoyé');
                    $candidatureIsSended = true;
                }
            }
        }

        dump($form);

        return $this->render('home/apply.html.twig', [
            'candidatureForm'     => $form->createView(),
            'offre'               => $offre,
            'candidatureIsSended' => $candidatureIsSended,
        ]);
    }

    /**
     * @Route("/nous-contact", name="contact")
     */
    public function contact()
    {
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/a-propos", name="about")
     */
    public function about()
    {
        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/en-savoir-plus", name="about_plus")
     */
    public function aboutPlus()
    {
        return $this->render('home/about_plus.html.twig');
    }
}
