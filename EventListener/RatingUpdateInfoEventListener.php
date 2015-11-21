<?php

namespace DCS\RatingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DCS\RatingBundle\DCSRatingEvents;
use DCS\RatingBundle\Event\RatingEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class RatingUpdateInfoEventListener implements EventSubscriberInterface
{
    
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DCSRatingEvents::RATING_PRE_PERSIST => 'updatePermalink',
        );
    }

    public function updatePermalink(RatingEvent $event)
    {
        if (null === $this->requestStack) {
            return;
        }

        $rating = $event->getRating();

        if (null === $rating->getPermalink()) {
            $rating->setPermalink($this->requestStack->getCurrentRequest()->get('permalink'));
        }

        if (null === $rating->getSecurityRole()) {
            $rating->setSecurityRole($this->requestStack->getCurrentRequest()->get('securityRole'));
        }
    }
}
