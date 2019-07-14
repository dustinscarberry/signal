<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Setting;

class AppConfig
{
  private $logo;
  private $locale;
  private $language;
  private $siteName;
  private $siteUrl;
  private $siteAbout;
  private $allowSubscriptions;
  private $cssOverrides;
  private $siteTimezone;
  private $analyticsGoogleID;
  private $dashboardTheme;
  private $appTheme;
  private $mailFromAddress;
  private $mailFromName;
  private $enableExchangeCalendarSync;
  private $enableGoogleCalendarSync;
  private $enableSaml2Login;
  private $saml2AppIdentifier;
  private $saml2IdpLoginUrl;
  private $saml2IdpSigningCertificate;
  private $saml2SubjectIdentifier;

  private $em;
  private $loadedSettings;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->load();
  }

  public function getLogo()
  {
    return $this->logo;
  }

  public function setLogo($logo): self
  {
    $this->logo = $logo;
    return $this;
  }

  public function getLanguage(): string
  {
    return $this->language;
  }

  public function setLanguage(string $language): self
  {
    $this->language = $language;
    return $this;
  }

  public function getLocale(): string
  {
    return $this->locale;
  }

  public function setLocale(string $locale): self
  {
    $this->locale = $locale;
    return $this;
  }

  public function getSiteName(): ?string
  {
    return $this->siteName;
  }

  public function setSiteName(?string $siteName): self
  {
    $this->siteName = $siteName;
    return $this;
  }

  public function getSiteUrl(): string
  {
    return $this->siteUrl;
  }

  public function setSiteUrl(string $siteUrl): self
  {
    $this->siteUrl = rtrim($siteUrl, '/');
    return $this;
  }

  public function getSiteAbout(): ?string
  {
    return $this->siteAbout;
  }

  public function setSiteAbout(?string $siteAbout): self
  {
    $this->siteAbout = $siteAbout;
    return $this;
  }

  public function getAllowSubscriptions(): bool
  {
    return $this->allowSubscriptions ? true : false;
  }

  public function setAllowSubscriptions(bool $allowSubscriptions): self
  {
    $this->allowSubscriptions = $allowSubscriptions;
    return $this;
  }

  public function getCSSOverrides(): ?string
  {
    return $this->cssOverrides;
  }

  public function setCSSOverrides(?string $cssOverrides): self
  {
    $this->cssOverrides = $cssOverrides;
    return $this;
  }

  public function getSiteTimezone(): string
  {
    return $this->siteTimezone;
  }

  public function setSiteTimezone(string $siteTimezone): self
  {
    $this->siteTimezone = $siteTimezone;
    return $this;
  }

  public function getAnalyticsGoogleID(): ?string
  {
    return $this->analyticsGoogleID;
  }

  public function setAnalyticsGoogleID(?string $analyticsGoogleID): self
  {
    $this->analyticsGoogleID = $analyticsGoogleID;
    return $this;
  }

  public function getDashboardTheme(): string
  {
    return $this->dashboardTheme;
  }

  public function setDashboardTheme(string $dashboardTheme): self
  {
    $this->dashboardTheme = $dashboardTheme;
    return $this;
  }

  public function getAppTheme(): string
  {
    return $this->appTheme;
  }

  public function setAppTheme(string $appTheme): self
  {
    $this->appTheme = $appTheme;
    return $this;
  }

  public function getMailFromAddress(): ?string
  {
    return $this->mailFromAddress;
  }

  public function setMailFromAddress(?string $mailFromAddress): self
  {
    $this->mailFromAddress = $mailFromAddress;
    return $this;
  }

  public function getMailFromName(): ?string
  {
    return $this->mailFromName;
  }

  public function setMailFromName(?string $mailFromName): self
  {
    $this->mailFromName = $mailFromName;
    return $this;
  }

  public function getEnableExchangeCalendarSync(): bool
  {
    return $this->enableExchangeCalendarSync;
  }

  public function setEnableExchangeCalendarSync(bool $enableExchangeCalendarSync): self
  {
    $this->enableExchangeCalendarSync = $enableExchangeCalendarSync;
    return $this;
  }

  public function getEnableGoogleCalendarSync(): bool
  {
    return $this->enableGoogleCalendarSync;
  }

  public function setEnableGoogleCalendarSync(bool $enableGoogleCalendarSync): self
  {
    $this->enableGoogleCalendarSync = $enableGoogleCalendarSync;
    return $this;
  }

  public function getEnableSaml2Login(): bool
  {
    return $this->enableSaml2Login;
  }

  public function setEnableSaml2Login(bool $enableSaml2Login): self
  {
    $this->enableSaml2Login = $enableSaml2Login;
    return $this;
  }

  public function getSaml2AppIdentifier(): ?string
  {
    return $this->saml2AppIdentifier;
  }

  public function setSaml2AppIdentifier(?string $saml2AppIdentifier): self
  {
    $this->saml2AppIdentifier = $saml2AppIdentifier;
    return $this;
  }

  public function getSaml2IdpLoginUrl(): ?string
  {
    return $this->saml2IdpLoginUrl;
  }

  public function setSaml2IdpLoginUrl(?string $saml2IdpLoginUrl): self
  {
    $this->saml2IdpLoginUrl = $saml2IdpLoginUrl;
    return $this;
  }

  public function getSaml2IdpSigningCertificate(): ?string
  {
    return $this->saml2IdpSigningCertificate;
  }

  public function setSaml2IdpSigningCertificate(?string $saml2IdpSigningCertificate): self
  {
    $this->saml2IdpSigningCertificate = $saml2IdpSigningCertificate;
    return $this;
  }

  public function getSaml2SubjectIdentifier(): ?string
  {
    return $this->saml2SubjectIdentifier;
  }

  public function setSaml2SubjectIdentifier(?string $saml2SubjectIdentifier): self
  {
    $this->saml2SubjectIdentifier = $saml2SubjectIdentifier;
    return $this;
  }

  //load settings from database
  private function load(): self
  {
    $allSettings = $this->em
      ->getRepository(Setting::class)
      ->findAll();
    $this->loadedSettings = [];

    foreach ($allSettings as $setting)
      $this->loadedSettings[$setting->getName()] = $setting->getValue();

    $this->assign();

    return $this;
  }

  //save settings to database
  public function save(): self
  {
    $repository = $this->em->getRepository(Setting::class);

    foreach ($this->loadedSettings as $key => $value)
    {
      $setting = $repository->findOneByName($key);

      if ($setting)
        $setting->setValue($this->$key);
    }

    $this->em->flush();

    return $this;
  }

  private function assign()
  {
    foreach ($this->loadedSettings as $key => $value)
      $this->$key = $value;
  }
}
