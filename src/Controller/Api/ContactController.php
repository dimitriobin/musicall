<?php

namespace App\Controller\Api;

use App\Model\Contact\ContactModel;
use App\Service\Mail\Contact\ContactMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
    #[Route(path: '/api/contact', name: 'api_contact', options: ['expose' => true], methods: ['POST'])]
    public function send(
        Request             $request,
        SerializerInterface $serializer,
        ValidatorInterface  $validator,
        ContactMail         $contactMail
    ): JsonResponse {
        /** @var ContactModel $contact */
        $contact = $serializer->deserialize($request->getContent(), ContactModel::class, 'json');
        $errors = $validator->validate($contact);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $contactMail->send($contact->getName(), $contact->getEmail(), $contact->getMessage());

        return $this->json([]);
    }
}
