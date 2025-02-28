<?php

namespace App\Service\Finder\Musician;

use App\Entity\User;
use App\Exception\Musician\InvalidResultException;
use App\Exception\Musician\NoResultException;
use App\Model\Search\MusicianText;
use App\Repository\Musician\MusicianAnnounceRepository;
use App\Service\Client\OpenAI\OpenAIClient;
use App\Service\Factory\JsonTextExtractorFactory;
use App\Service\Finder\Musician\Builder\SearchModelBuilder;
use App\Service\Finder\Musician\Formatter\PromptFormatter;

class MusicianAIFinder
{
    public function __construct(
        private readonly OpenAIClient               $openAIClient,
        private readonly PromptFormatter            $promptFormatter,
        private readonly MusicianAnnounceRepository $musicianAnnounceRepository,
        private readonly JsonTextExtractorFactory   $jsonTextExtractorFactory,
        private readonly SearchModelBuilder         $searchModelBuilder
    ) {
    }

    public function find(MusicianText $musicianText, ?User $user)
    {
        $response = $this->openAIClient->getChatCompletions([
            $this->promptFormatter->formatSystemMessage(),
            $this->promptFormatter->formatUserMessage($musicianText->getSearch()),
        ]);
        if (!$content = ($response->toArray()['choices'][0]['message']['content'] ?? null)) {
            throw new NoResultException('One the key on the response is missing');
        }
        $extractor = $this->jsonTextExtractorFactory->create();
        if (!$json = $extractor->getJsonStrings($content)) {
            throw new NoResultException('There is no JSON in the response');
        }
        if (count($json) !== 1) {
            throw new InvalidResultException('Too much JSON in the response');
        }
        $searchModel = $this->searchModelBuilder->build($json[0]);

        return $this->musicianAnnounceRepository->findByCriteria($searchModel, $user);
    }
}