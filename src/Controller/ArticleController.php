<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends AbstractController
{
    public function createArticle(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $article = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect('/view-article/' . $article->getId());

        }

        return $this->render(
            'edit.html.twig',
            array('form' => $form->createView())
        );

    }

    public function viewArticle($id)
    {
        $article = $this->getDoctrine()
            ->getRepository('App\Entity\Article')
            ->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'There are no articles with the following id: ' . $id
            );
        }

        return $this->render(
            'view.html.twig',
            array('article' => $article)
        );
    }

    public function showArticles()
    {
        $articles = $this->getDoctrine()
            ->getRepository('App\Entity\Article')
            ->findAll();

        return $this->render(
            'show.html.twig',
            array('articles' => $articles)
        );
    }

    public function deleteArticle($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('App\Entity\Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'There are no articles with the following id: ' . $id
            );
        }

        $em->remove($article);
        $em->flush();

        return $this->redirect('/show-articles');
    }

    public function updateArticle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('App\Entity\Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'There are no articles with the following id: ' . $id
            );
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $article = $form->getData();
            $em->flush();
            return $this->redirect('/view-article/' . $id);
        }

        return $this->render(
            'edit.html.twig',
            array('form' => $form->createView())
        );
    }
}
