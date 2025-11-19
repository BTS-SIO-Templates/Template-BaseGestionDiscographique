<?php

namespace App\Controller\Admin\artiste;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtisteController extends AbstractController
{
 #[Route('/admin/artistes', name: 'admin_artistes' , methods:["GET"])]

    public function listeArtistes(ArtisteRepository $repo , PaginatorInterface $paginator , Request $request): Response
    {
        $artistes=$paginator->paginate(
            $repo->listeArtisteCompletePaginee(),
            $request->query->getInt('page', 1), /* page number */
            9 /* limit per page */
        );
        return $this->render('admin/listeArtistes.html.twig',[
            'lesArtistes' => $artistes
        ]);

    }

     #[Route('/admin/artiste', name: 'admin_artistes_ajout' , methods:["GET","POST"])]
     #[Route('/admin/artiste/modif/{id}', name: 'admin_artistes_modif' , methods:["GET","POST"])]


    public function AjoutModifArtiste(Artiste $artiste=null , Request $request , EntityManagerInterface $manager): Response
    {
        if($artiste == null)
            {
                $artiste=new Artiste();
                $mode='ajouté';
            }
        else
            {
                $mode="modifié";
            }
        $form=$this->createForm(ArtisteType::class,$artiste);
        $form->handleRequest($request);
        if($form->IsSubmitted() && $form->IsValid())
        {
            $manager->persist($artiste);
            $manager->flush();
            $this->addFlash("success","l'artiste a été $mode");
            return $this->redirectToRoute('admin_artistes');
        }
        return $this->render('admin/formAjoutModifArtiste.html.twig',[
            'formArtiste' => $form->createView()
        ]);
    }


    #[Route('/admin/artistes/suppression/{id}', name: 'admin_artistes_suppression' , methods:["GET"])]


    public function suppressionArtiste(Artiste $artiste, EntityManagerInterface $manager): Response
    {
        $nbAlbums=$artiste->getAlbums()->count();
        if($nbAlbums>0)
        {
            $this->addFlash("danger","vous ne pouvez pas supprimer cet artiste car il possède $nbAlbums album(s)");
        }else
        {
            $manager->remove($artiste);
            $manager->flush();
            $this->addFlash("success","l'artiste a été supprimé");
        }
        return $this->redirectToRoute('admin_artistes');  
        
    }
}
