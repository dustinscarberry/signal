<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\ServiceCategory;
use App\Entity\ServiceStatus;
use App\Entity\User;
use App\Entity\Setting;
use App\Entity\IncidentStatus;
use App\Entity\IncidentType;
use App\Entity\Incident;
use App\Entity\MaintenanceStatus;
use App\Service\Manager\UserManager;

class DemoFixtures extends Fixture
{
  private $passwordEncoder;
  private $userManager;

  public function __construct(
    UserPasswordEncoderInterface $passwordEncoder,
    UserManager $userManager
  )
  {
    $this->passwordEncoder = $passwordEncoder;
    $this->userManager = $userManager;
  }

  public function load(ObjectManager $manager)
  {
    //create default service categories
    $serviceCategory = new ServiceCategory();
    $serviceCategory->setName('Default');
    $serviceCategory->setHint('Default category');
    $serviceCategory->setDeletable(false);
    $serviceCategory->setEditable(false);
    $manager->persist($serviceCategory);

    //create default service statuses
    $serviceStatuses = [
      ['name' => 'Operational', 'type' => 'ok', 'metricValue' => 100],
      ['name' => 'Experiencing Issues', 'type' => 'issue', 'metricValue' => 75],
      ['name' => 'Partial Outage', 'type' => 'error', 'metricValue' => 50],
      ['name' => 'Major Outage', 'type' => 'error', 'metricValue' => 0],
      ['name' => 'Maintenance', 'type' => 'offline', 'metricValue' => 0]
    ];

    foreach ($serviceStatuses as $status)
    {
      $record = new ServiceStatus();
      $record->setName($status['name']);
      $record->setType($status['type']);
      $record->setMetricValue($status['metricValue']);
      $record->setDeletable(false);
      $record->setEditable(false);
      $manager->persist($record);
    }

    //create default incident statuses
    $incidentStatuses = [
      ['name' => 'Investigating', 'type' => 'error'],
      ['name' => 'Identified', 'type' => 'issue'],
      ['name' => 'Watching', 'type' => 'issue'],
      ['name' => 'Resolved', 'type' => 'ok']
    ];

    foreach ($incidentStatuses as $incidentStatus)
    {
      $record = new IncidentStatus();
      $record->setName($incidentStatus['name']);
      $record->setType($incidentStatus['type']);
      $record->setDeletable(false);
      $record->setEditable(false);
      $manager->persist($record);
    }

    //create incident types
    $incidentTypes = [
      ['name' => 'Degradation'],
      ['name' => 'Outage']
    ];

    foreach ($incidentTypes as $incidentType)
    {
      $record = new IncidentType();
      $record->setName($incidentType['name']);
      $manager->persist($record);
    }

    //create default maintenance statuses
    $maintenanceStatuses = [
      ['name' => 'Upcoming', 'type' => 'future'],
      ['name' => 'In Progress', 'type' => 'progress'],
      ['name' => 'Complete', 'type' => 'ok']
    ];

    foreach ($maintenanceStatuses as $maintenanceStatus)
    {
      $record = new MaintenanceStatus();
      $record->setName($maintenanceStatus['name']);
      $record->setType($maintenanceStatus['type']);
      $record->setDeletable(false);
      $record->setEditable(false);
      $manager->persist($record);
    }

    //create default users
    $user = new User();
    $user->setUsername('demo');
    $user->setPassword($this->passwordEncoder->encodePassword($user, 'demo'));
    $user->setEmail('demo@demo.com');
    $user->setFirstName('Demo');
    $user->setLastName('Account');
    $user->setRoles(['ROLE_ADMIN']);
    $user = $this->userManager->regenerateApiToken($user);
    $manager->persist($user);

    //create default settings
    $settings = [];
    $settings['logo'] = '';
    $settings['language'] = 'en';
    $settings['locale'] = 'en';
    $settings['siteName'] = 'Signal';
    $settings['siteUrl'] = 'http://localhost/';
    $settings['siteAbout'] = '';
    $settings['allowSubscriptions'] = true;
    $settings['cssOverrides'] = '';
    $settings['siteTimezone'] = 'America/New_York';
    $settings['analyticsGoogleID'] = '';
    $settings['dashboardTheme'] = 'purple';
    $settings['appTheme'] = 'light';
    $settings['mailFromAddress'] = 'statusbot@donotreply.com';
    $settings['mailFromName'] = 'Status Bot';
    $settings['enableExchangeCalendarSync'] = false;
    $settings['enableSaml2Login'] = false;
    $settings['saml2AppIdentifier'] = '';
    $settings['saml2IdpLoginUrl'] = '';
    $settings['saml2IdpSigningCertificate'] = '';
    $settings['saml2SubjectIdentifier'] = '';

    foreach ($settings as $key => $value)
    {
      $setting = new Setting();
      $setting->setName($key);
      $setting->setValue($value);
      $manager->persist($setting);
    }

    $manager->flush();
  }
}
