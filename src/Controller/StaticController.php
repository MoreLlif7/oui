<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Form\LivreGoldenType;
use App\Form\InscriptionType;
use App\Form\AjoutFichierType;
use App\Form\AjoutThemeType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Entity\LivreGolden;
use App\Entity\Utilisateur;
use App\Entity\Fichier;
use App\Entity\Theme;


class StaticController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function accueil(): Response
    {
    
        return $this->render('static/accueil.html.twig', [
            
        ]);
    }

    #[Route('/ajout-fichier', name: 'ajout-fichier')]
    public function ajoutfichier(Request $request): Response
    {
        
        $fichier = new Fichier;
        $form = $this->createForm(AjoutFichierType::class, $fichier);
        $doctrine = $this->getDoctrine();
        $fichiers = $doctrine->getRepository(Fichier::class)->findBy(array(), array('dateCrea'=>'DESC'));

        if($request->get('id') != null){
            $f = $doctrine->getRepository(Fichier::class)->find($request->get('id'));
            try{
                $filesystem = new Filesystem();
                if ($filesystem->exists($this->getParameter('file_directory').'/'.$fichier->getNom())){
                    $filesystem->remove
                }
            }
        }


        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){

                $idTheme = $form->get('theme')->getData();
                $theme = $this->getDoctrine()->getRepository(Theme::class)->find($idTheme);
                

                //dump($theme);
                $fichierPhysique = $fichier->getNom();
                $ext = '';
                if($fichierPhysique->guessExtension()!=null){
                    $ext = $fichierPhysique->guessExtension();
                }
                $fichier->setExtension($ext);
                $fichier->setDateCrea(new \DateTime());
                $fichier->setOriginal($fichierPhysique->getClientOriginalName());
                $fichier->setTaille($fichierPhysique->getSize());
                $fichier->setNom(md5(uniqid()));
                $fichier->addTheme($theme);
                try{
                    $fichierPhysique->move($this->getParameter('file_directory'),$fichier->getNom());
                    $this->addFlash('notice', 'Fichier envoyé');
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($fichier);
                    $em->flush();
                }
                catch(FileException $e){
                    $this->addFlash('notice', 'Erreur d\'envoi');
                }

                return $this->redirectToRoute('ajout-fichier');
            }
        }
        return $this->render('static/ajout-fichier.html.twig', ["form"=>$form->createView(),'fichiers' => $fichiers]);
    }

    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request): Response
    {
        $utilisateur = new Utilisateur;
        $form = $this->createForm(InscriptionType::class, $utilisateur);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $this->addFlash('notice','C\'est good '.$utilisateur->getNom().' le bg ');
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();

                return $this->redirectToRoute('inscription');
            }
        }
        return $this->render('static/inscription.html.twig', [ "form"=>$form->createView()]);
    }

    #[Route('/e-penis', name: 'Epenis')]
    public function Epenis(): Response
    {
        $repoGolden = $this->getDoctrine()->getRepository(LivreGolden::class);
        $ePenisss = $repoGolden->findBy(array(), array('id'=>'ASC'));
        return $this->render('static/epenis.html.twig', ['ePenis'=>$ePenisss]);
    }

    #[Route('/liste-contacts', name: 'listeContacts')]
    public function listeContacts(): Response
    {
        $repoContact = $this->getDoctrine()->getRepository(Contact::class);
        $contacts = $repoContact->findBy(array(), array('nom'=>'ASC'));

        return $this->render('static/listeContacts.html.twig', ['contacts'=>$contacts]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $contact = new Contact();   
        
        $form = $this->createForm(ContactType::class, $contact);



        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                
                $this->addFlash('notice','C\'est good '.$contact->getNom().' le bg ');
                $message = (new \Swift_Message($form->get('sujet')->getData()))
                ->setFrom($contact->getEmail())
                ->setTo('mehdi.raymond1@outlook.fr')
                //->setBody($form->get('message')->getData());
                ->setBody($this->renderView('emails/contact-email.html.twig', array('nom'=>$contact->getNom(),'sujet'=>$contact->getSujet(),'message'=>$contact->getMessage())), 'text/html');
                $mailer->send($message);

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                return $this->redirectToRoute('contact');
            }
        }
        
        return $this->render('static/contact.html.twig', ['form'=>$form->createView()]);
    }

    #[Route('/mention', name: 'mention')]
    public function mention(Request $request): Response
    {
        $oui = new LivreGolden;
        $form = $this->createForm(LivreGoldenType::class, $oui);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($oui);
                $em->flush();
                return $this->redirectToRoute('mention');
            }
        }

        return $this->render('static/mention.html.twig', ['form'=>$form->createView()]);
    }

    #[Route('/a_propos', name: 'a_propos')]
    public function a_propos(): Response
    {
        return $this->render('static/a_propos.html.twig', [
            
        ]);
    }
}
