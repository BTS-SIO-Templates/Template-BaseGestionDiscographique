<?php

namespace App\Controller\Admin\style;

use App\Form\StyleType;
use App\Entity\Style;
use App\Repository\StyleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StyleController extends AbstractController
{
 #[Route('/admin/styles', name: 'admin_styles' , methods:["GET"])]

    public function listeStyles(StyleRepository $repo , PaginatorInterface $paginator , Request $request): Response
    {
        $styles=$paginator->paginate(
            $repo->listeStylesCompletePaginee(),
            $request->query->getInt('page', 1), /* page number */
            9 /* limit per page */
        );
        return $this->render('admin/listeStyles.html.twig',[
            'lesStyles' => $styles
        ]);

    }
     #[Route('/admin/style', name: 'admin_styles_ajout' , methods:["GET","POST"])]
     #[Route('/admin/style/modif/{id}', name: 'admin_styles_modif' , methods:["GET","POST"])]

    public function AjoutModifStyle(Style $style=null , Request $request , EntityManagerInterface $manager): Response
    {
        if($style == null)
            {
                $style=new Style();
                $mode='ajouté';
            }
            else
            {
                $mode="modifié";
            }
        $form=$this->createForm(StyleType::class,$style);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($style);
            $manager->flush();
            $this->addFlash("success","le style a été $mode");
            return $this->redirectToRoute('admin_styles');
        }
        return $this->render('admin/formAjoutModifStyle.html.twig',[
            'formStyle' => $form->createView()
        ]);
    }

    #[Route('/admin/styles/suppression/{id}', name: 'admin_styles_suppression' , methods:["GET"])]
    public function suppressionStyle(Style $style, EntityManagerInterface $manager): Response
    {
        $nbAlbums=$style->getAlbums()->count();
        if($nbAlbums>0)
        {
            $this->addFlash("danger","vous ne pouvez pas supprimer ce style car il possède $nbAlbums album(s)");
        }else
        {
            $manager->remove($style);
            $manager->flush();
            $this->addFlash("success","le style a été supprimé");
        }
        return $this->redirectToRoute('admin_styles');

    }


}
