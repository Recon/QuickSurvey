<?php

namespace Recon\AppBundle\Controller;

use Recon\ModelBundle\Entity\Project;
use Recon\ModelBundle\Entity\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectController extends Controller
{

    /**
     * @Route("/v/{slug}")
     * @Template()
     */
    public function viewAction($slug, Request $request)
    {
        $project = $this->getProject($slug);


        if ($project->getIsCompleted()) {
            return $this->render('ReconAppBundle:Messages:info.html.twig', [
                        'message' => 'This survey has already been filled. Thank you for your time!'
            ]);
        }

        $form = $this->getSurveyForm($project)->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->handleFormData($form, $project);
            $this->submitNotificationEmail($project);

            // @todo later on
            // $this->submitChatNotification($project);

            return $this->render('ReconAppBundle:Messages:success.html.twig', [
                        'message' => 'Thank you for your time! The answers were recorded and forwarded to the developer.'
            ]);
        }

        return [
            'project' => $project,
            'form' => $form->createView()
        ];
    }

    /**
     * Retrieves a project by slug
     *
     * @param string $slug
     * @return Project
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    protected function getProject($slug)
    {
        $repository = $this->getDoctrine()->getRepository('ReconModelBundle:Project');
        $slug = preg_replace('/[^a-z0-9]/i', '', $slug);

        if (strlen($slug) != 13) {
            throw new HttpException(400);
        }

        if (!$project = $repository->findOneBy(['slug' => $slug])) {
            throw new NotFoundHttpException();
        }

        return $project;
    }

    /**
     * Creates the survey form for a given project
     *
     * @param Project $project
     * @return FormBuilder
     */
    protected function getSurveyForm(Project $project)
    {
        $form = $this->createFormBuilder()
                ->setAction($this->generateUrl('recon_app_project_view', [
                            'slug' => $project->getSlug()
                ]))
                ->setMethod('POST')
                ->setAttribute('novalidate', 'true')
        ;

        foreach ($project->getQuestions() as $question) {
            $radioItems = [];

            foreach ($question->getAnswers() As $answer) {

                $defaultName = "question:{$answer->getQuestion()->getId()}:{$answer->getId()}";

                switch ($answer->getType()) {
                    case 'RADIO':
                        $radioItems[$answer->getId()] = $answer->getText();
                        break;
                    case 'TEXTAREA':
                        $form->add($defaultName, 'textarea', [
                            'label' => $answer->getText()
                        ]);
                        break;
                    case 'TEXTINPUT':
                        $form->add($defaultName, 'text', [
                            'label' => $answer->getText()
                        ]);
                        break;
                    case 'CHECKBOX':
                        $form->add($defaultName, 'checkbox', [
                            'label' => $answer->getText(),
                        ]);
                        break;
                }
            }

            if (count($radioItems)) {
                $form->add("question:{$answer->getQuestion()->getId()}", 'choice', [
                    'choices' => $radioItems,
                    'expanded' => true,
                    'label' => ' ',
                    'constraints' => [
                        new NotBlank()
                    ]
                ]);
            }
        }

        $form->add('save', 'submit', array('label' => 'Submit', 'attr' => [
                'class' => 'btn-primary withripple',
                'type' => 'submit'
        ]));

        return $form;
    }

    public function handleFormData(Form $form, $project)
    {
        $data = $form->getData();

        foreach ($project->getQuestions() as $question) {
            foreach ($question->getAnswers() As $answer) {

                if (!array_key_exists("question:{$question->getId()}:{$answer->getId()}", $data) && !array_key_exists("question:{$question->getId()}", $data)) {
                    continue;
                }

                $response = new Response();
                $response->setAnswer($answer);
                $response->setProject($project);

                switch ($answer->getType()) {
                    case 'RADIO':
                        $key = "question:{$question->getId()}";
                        break;
                    case 'CHECKBOX':
                        $key = "question:{$question->getId()}:{$answer->getId()}";
                        break;
                    case 'TEXTAREA':
                    case 'TEXTINPUT':
                        $key = "question:{$question->getId()}:{$answer->getId()}";
                        $response->setValue($data[$key]);
                        break;
                }

                unset($data[$key]);
                $project->addResponse($response);
            }
        }

        $project->setIsCompleted(true);
        $this->getDoctrine()->getManager()->flush();
    }

    public function submitNotificationEmail($project)
    {
        $message = \Swift_Message::newInstance()
                ->setSubject("Quick Survey Response: {$project->getName()}")
                ->setFrom($this->container->getParameter('email_from'))
                ->setTo($this->container->getParameter('email_admin'))
                ->setBody(
                $this->renderView('ReconAppBundle:Email:projectResponse.html.twig', array(
                    'project' => $project
                )), 'text/html'
                )
        ;
        $this->get('mailer')->send($message);

        return;
    }

}
