<?php

// We assume that everything has been properly imported

class AlertTypeEnum
{
    public const SALE = 'sale';
    public const SALE_PRICE = 'salePrice';
    public const STOCK = 'stock';
}

class Sample {
    public function persistUserData(array $usersData): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);

        // Only create user if not exists
        foreach ($usersData as $referenceKey => $userData) {
            $user = $userRepository->findOneBy(['email' => $userData['email']]);

            $user->setEmail($userData['email'])
                ->setUsername($userData['username'])
                ->setPassword($userData['password'])
                ->setFirstname('Test')
                ->setLastname('Test')
                ->setGuid('1');

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
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
