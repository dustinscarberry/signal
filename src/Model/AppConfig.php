<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;

class AppConfig
{
  private $em;

  private $settingList = [
    'logo' => '',
    'locale' => 'en',
    'language' => 'en',
    'siteName' => 'Signal',
    'siteUrl' => 'localhost',
    'siteAbout' => '',
    'allowSubscriptions' => false,
    'cssOverrides' => '',
    'siteTimezone' => 'America/New_York',
    'analyticsGoogleID' => '',
    'dashboardTheme' => '',
    'appTheme' => '',
    'mailFromAddress' => '',
    'mailFromName' => 'Signal',
    'enableSaml2Login' => false,
    'saml2AppIdentifier' => '',
    'saml2IdpLoginUrl' => '',
    'saml2IdpSigningCertificate' => '',
    'saml2SubjectIdentifier' => ''
  ];

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->load();
  }

  public function getLogo()
  {
    return $this->settingList['logo'];
  }

  public function setLogo($logo): self
  {
    $this->settingList['logo'] = $logo;
    return $this;
  }

  public function getLanguage(): string
  {
    return $this->settingList['language'];
  }

  public function setLanguage(string $language): self
  {
    $this->settingList['language'] = $language;
    return $this;
  }

  public function getLocale(): string
  {
    return $this->settingList['locale'];
  }

  public function setLocale(string $locale): self
  {
    $this->settingList['locale'] = $locale;
    return $this;
  }

  public function getSiteName(): ?string
  {
    return $this->settingList['siteName'];
  }

  public function setSiteName(?string $siteName): self
  {
    $this->settingList['siteName'] = $siteName;
    return $this;
  }

  public function getSiteUrl(): string
  {
    return $this->settingList['siteUrl'];
  }

  public function setSiteUrl(string $siteUrl): self
  {
    $this->settingList['siteUrl'] = rtrim($siteUrl, '/');
    return $this;
  }

  public function getSiteAbout(): ?string
  {
    return $this->settingList['siteAbout'];
  }

  public function setSiteAbout(?string $siteAbout): self
  {
    $this->settingList['siteAbout'] = $siteAbout;
    return $this;
  }

  public function getAllowSubscriptions(): bool
  {
    return $this->settingList['allowSubscriptions'] ? true : false;
  }

  public function setAllowSubscriptions(bool $allowSubscriptions): self
  {
    $this->settingList['allowSubscriptions'] = $allowSubscriptions;
    return $this;
  }

  public function getCSSOverrides(): ?string
  {
    return $this->settingList['cssOverrides'];
  }

  public function setCSSOverrides(?string $cssOverrides): self
  {
    $this->settingList['cssOverrides'] = $cssOverrides;
    return $this;
  }

  public function getSiteTimezone(): string
  {
    return $this->settingList['siteTimezone'];
  }

  public function setSiteTimezone(string $siteTimezone): self
  {
    $this->settingList['siteTimezone'] = $siteTimezone;
    return $this;
  }

  public function getAnalyticsGoogleID(): ?string
  {
    return $this->settingList['analyticsGoogleID'];
  }

  public function setAnalyticsGoogleID(?string $analyticsGoogleID): self
  {
    $this->settingList['analyticsGoogleID'] = $analyticsGoogleID;
    return $this;
  }

  public function getDashboardTheme(): string
  {
    return $this->settingList['dashboardTheme'];
  }

  public function setDashboardTheme(string $dashboardTheme): self
  {
    $this->settingList['dashboardTheme'] = $dashboardTheme;
    return $this;
  }

  public function getAppTheme(): string
  {
    return $this->settingList['appTheme'];
  }

  public function setAppTheme(string $appTheme): self
  {
    $this->settingList['appTheme'] = $appTheme;
    return $this;
  }

  public function getMailFromAddress(): ?string
  {
    return $this->settingList['mailFromAddress'];
  }

  public function setMailFromAddress(?string $mailFromAddress): self
  {
    $this->settingList['mailFromAddress'] = $mailFromAddress;
    return $this;
  }

  public function getMailFromName(): ?string
  {
    return $this->settingList['mailFromName'];
  }

  public function setMailFromName(?string $mailFromName): self
  {
    $this->settingList['mailFromName'] = $mailFromName;
    return $this;
  }

  public function getEnableSaml2Login(): bool
  {
    return $this->settingList['enableSaml2Login'];
  }

  public function setEnableSaml2Login(bool $enableSaml2Login): self
  {
    $this->settingList['enableSaml2Login'] = $enableSaml2Login;
    return $this;
  }

  public function getSaml2AppIdentifier(): ?string
  {
    return $this->settingList['saml2AppIdentifier'];
  }

  public function setSaml2AppIdentifier(?string $saml2AppIdentifier): self
  {
    $this->settingList['saml2AppIdentifier'] = $saml2AppIdentifier;
    return $this;
  }

  public function getSaml2IdpLoginUrl(): ?string
  {
    return $this->settingList['saml2IdpLoginUrl'];
  }

  public function setSaml2IdpLoginUrl(?string $saml2IdpLoginUrl): self
  {
    $this->settingList['saml2IdpLoginUrl'] = $saml2IdpLoginUrl;
    return $this;
  }

  public function getSaml2IdpSigningCertificate(): ?string
  {
    return $this->settingList['saml2IdpSigningCertificate'];
  }

  public function setSaml2IdpSigningCertificate(?string $saml2IdpSigningCertificate): self
  {
    $this->settingList['saml2IdpSigningCertificate'] = $saml2IdpSigningCertificate;
    return $this;
  }

  public function getSaml2SubjectIdentifier(): ?string
  {
    return $this->settingList['saml2SubjectIdentifier'];
  }

  public function setSaml2SubjectIdentifier(?string $saml2SubjectIdentifier): self
  {
    $this->settingList['saml2SubjectIdentifier'] = $saml2SubjectIdentifier;
    return $this;
  }

  // load settings from database
  private function load(): self
  {
    $allSettings = $this->em
      ->getRepository(Setting::class)
      ->findAll();

    foreach ($allSettings as $setting)
      $this->settingList[$setting->getName()] = $setting->getValue();

    return $this;
  }

  // save settings to database
  public function save(): self
  {
    $repository = $this->em->getRepository(Setting::class);

    foreach ($this->settingList as $key => $value) {
      $setting = $repository->findOneByName($key);

      if (!$setting) {
        $setting = new Setting();
        $setting->setName($key);
        $this->em->persist($setting);
      }

      $setting->setValue($value);
    }

    $this->em->flush();
    return $this;
  }
}
