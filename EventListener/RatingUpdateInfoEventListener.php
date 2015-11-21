<?php

namespace DCS\RatingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DCS\RatingBundle\DCSRatingEvents;
use DCS\RatingBundle\Event\RatingEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class RatingUpdateInfoEventListener implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public static function getSubscribedEvents()
    {
        return array(
            DCSRatingEvents::RATING_PRE_PERSIST => 'updatePermalink',
        );
    }

    public function updatePermalink(RatingEvent $event)
    {
        if (null === $this->request) {
            return;
        }

        $rating = $event->getRating();

        if (null === $rating->getPermalink()) {
            $rating->setPermalink($this->request->get('permalink'));
        }

        if (null === $rating->getSecurityRole()) {
            $rating->setSecurityRole($this->request->get('securityRole'));
        }
    }
}
