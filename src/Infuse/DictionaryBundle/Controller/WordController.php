<?php

namespace Infuse\DictionaryBundle\Controller;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Infuse\DictionaryBundle\Entity\Word;
use Infuse\DictionaryBundle\Form\WordType;

/**
 * Word controller.
 *
 * @Route("/")
 */
class WordController extends Controller
{
    /**
     * Lists all Word entities.
     *
     * @Route("/", name="word")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');

        $letter = null;
        $status = !$securityContext->isGranted('ROLE_ADMIN');
        if (!$request->query->get('letter')) {
            $entities = $em->createQuery('SELECT w FROM InfuseDictionaryBundle:Word w WHERE w.status >= :s')->setParameter(':s', $status)->getResult();
        }else{
            $letter = $request->query->get('letter');
            $entities = $em->getRepository('InfuseDictionaryBundle:Word')->findAllStartWith($request->query->get('letter'), $status);
        }

        return array(
            'entities' => $entities,
            'letter' => $letter,
        );
    }

    /**
     * Creates a new Word entity.
     *
     * @Route("/", name="word_create")
     * @Method("POST")
     * @Template("InfuseDictionaryBundle:Word:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Word();
        $form = $this->createForm(new WordType($this->get('security.context')), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Your word has been saved. We will review it soon as posisble. Thanks.');

            return $this->redirect($this->generateUrl('word_show', array('slug' => $entity->getSlug())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Word entity.
     *
     * @Route("/new", name="word_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Word();
        $form   = $this->createForm(new WordType($this->get('security.context')), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Word entity.
     *
     * @Route("/{slug}", name="word_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfuseDictionaryBundle:Word')->findOneBySlug($slug);

        if (!$entity) {
            return $this->redirect($this->generateUrl('word_search', array('text' => $slug)));
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Word entity.
     *
     * @Route("/admin/{id}/edit", name="word_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $securityContext = $this->get('security.context');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfuseDictionaryBundle:Word')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Word entity.');
        }

        $editForm = $this->createForm(new WordType($securityContext), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Word entity.
     *
     * @Route("/admin/{id}", name="word_update")
     * @Method("PUT")
     * @Template("InfuseDictionaryBundle:Word:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InfuseDictionaryBundle:Word')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Word entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new WordType($this->get('security.context')), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('word_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Word entity.
     *
     * @Route("/admin/{id}", name="word_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('InfuseDictionaryBundle:Word')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Word entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('word'));
    }

    /**
     * Search a word for a given text
     *
     * @Route("/search/{text}", name="word_search")
     * @Method("GET")
     * @Template("InfuseDictionaryBundle:Word:index.html.twig")
     **/
    public function searchAction($text)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('InfuseDictionaryBundle:Word')->searchAllWithSlugText(Urlizer::urlize($text));

        if (count($entities) == 1) {
            $entity = array_shift($entities);
            return $this->redirect($this->generateUrl('word_show', array('slug' => $entity->getSlug())));
        }

        return array(
            'search' => $text,
            'entities' => $entities
        );
    }

    /**
     * Creates a form to delete a Word entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
