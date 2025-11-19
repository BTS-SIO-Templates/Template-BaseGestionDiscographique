<?php

namespace App\Controller\Admin\album;


use App\Entity\Album;
use App\Form\AlbumType;
use App\Models\FiltreAlbum;

use App\Form\FiltreAlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
 #[Route('/admin/album', name: 'admin_albums' , methods:["GET"])]

    public function listeAlbums(AlbumRepository $repo , PaginatorInterface $paginator , Request $request): Response
    {
        $filtre = new FiltreAlbum();
        $formFiltreAlbum=$this->createForm(FiltreAlbumType::class,$filtre);
        $formFiltreAlbum->handleRequest($request);
        dump($filtre);
        $albums=$paginator->paginate(
            $repo->listeAlbumsCompletePaginee($filtre),
            $request->query->getInt('page', 1), /* page number */
            9 /* limit per page */
        );
        return $this->render('admin/listeAlbums.html.twig',[
            'lesAlbums' => $albums,
            'formFiltreAlbum' => $formFiltreAlbum->createView()
        ]);

    }

     #[Route('/admin/album/ajout', name: 'admin_albums_ajout' , methods:["GET","POST"])]
     #[Route('/admin/album/modif/{id}', name: 'admin_albums_modif' , methods:["GET","POST"])]


    public function AjoutModifAlbum(Album $album=null , Request $request , EntityManagerInterface $manager): Response
    {
        if($album == null)
            {
                $album=new Album();
                $mode='ajouté';
            }
        else
            {
                $mode="modifié";
            }
        $form=$this->createForm(AlbumType::class,$album);
        $form->handleRequest($request);
        if($form->IsSubmitted() && $form->IsValid())
        {
            // Correction : lier chaque morceau à l'album courant
            foreach ($album->getMorceaux() as $morceau) {
                $morceau->setAlbum($album);
            }
            $manager->persist($album);
            $manager->flush();
            $this->addFlash("success","l'album a été $mode");
            return $this->redirectToRoute('admin_albums');
        }
        return $this->render('admin/formAjoutModifAlbum.html.twig',[
            'formAlbum' => $form->createView(),
            'albumimage'=>$album->getImage(),
        ]);
    }



    #[Route('/admin/albums/suppression/{id}', name: 'admin_albums_suppression' , methods:["GET"])]
    public function suppressionAlbum(Album $album, EntityManagerInterface $manager): Response
    {
        $nbMorceaux=$album->getMorceaux()->count();
        if($nbMorceaux>0)
        {
            $this->addFlash("danger","vous ne pouvez pas supprimer cet album car il possède $nbMorceaux morceau(x)");
        }else
        {
            $manager->remove($album);
            $manager->flush();
            $this->addFlash("success","l'album a été supprimé");
        }
        return $this->redirectToRoute('admin_albums');

    }


}
