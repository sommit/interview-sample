<?php

// We assume that everything has been properly imported

class AlertTypeEnum
{
    public const SALE = 'sale';
    public const SALE_PRICE = 'salePrice';
    public const STOCK = 'stock';
}


class Log {
    private User $user;

    // ...

    public function setUser($user)
    {
        $this->user = $user;
    }

    // ...
}

class Sample {
    public function logLogin(AuthenticationSuccessEvent $event)
    {
        $userGlobal = $event->getUser();
        $user = $this->em->getRepository('AppBundle:User\User')->findOneBy(['username' => $userGlobal->getUsername()]);

        $log = new Log();
        $log->setUser($user);

        $user->setLastLogin(new DateTime());

        $this->em->persist($log);
        $this->em->merge($user);
        $this->em->flush();
    }

    /* Some special constants */
    const INVALID_ID = 0 << 0;
    const UPDATED = 1 << 0;
    const IN_ORDER = 1 << 1;
    const HAS_STOCK = 1 << 2;



    public function getAlerts($configuration, $structure)
    {
        $alertsByType = [];

        // Sale Alerts
        $alerts = $configuration->getSaleAlerts();
        $alertsByType[AlertTypeEnum::SALE]['alerts'] = $this->formatAlerts(AlertTypeEnum::SALE, $structure->getRestaurant(), $alerts);
        $alertsByType[AlertTypeEnum::SALE]['count'] = $this->countAlerts($alertsByType[AlertTypeEnum::SALE]['alerts']);

        // Sale Price Alerts
        $alerts = $configuration->getSalePriceAlerts();
        $alertsByType[AlertTypeEnum::SALE_PRICE]['alerts'] = $this->formatAlerts(AlertTypeEnum::SALE_PRICE, $structure->getRestaurant(), $alerts);
        $alertsByType[AlertTypeEnum::SALE_PRICE]['count'] = $this->countAlerts($alertsByType[AlertTypeEnum::SALE_PRICE]['alerts']);

        // Stock Alerts
        $alerts = $configuration->getSaleAlerts();
        $alertsByType[AlertTypeEnum::STOCK]['alerts'] = $this->formatAlerts(AlertTypeEnum::STOCK, $structure->getRestaurant(), $alerts);
        $alertsByType[AlertTypeEnum::STOCK]['count'] = $this->countAlerts($alertsByType[AlertTypeEnum::STOCK]['alerts']);

        return $alertsByType;
    }
}
