# Magister API
[![Latest Stable Version](https://poser.pugx.org/stanvk/magister/v/stable.svg)](https://packagist.org/packages/stanvk/magister)
[![Latest Unstable Version](https://poser.pugx.org/stanvk/magister/v/unstable.svg)](https://packagist.org/packages/stanvk/magister)
[![License](https://poser.pugx.org/stanvk/magister/license.svg)](https://packagist.org/packages/stanvk/magister)

We zijn momenteel bezig met een nieuwe en verbeterde versie.

> Onze website: http://magister-api.nl

#Inhoud
1. [Benodigdheden](#benodigdheden)
2. [Om te beginnen](#om-te-beginnen)
3. [Gebruik](#gebruik)
3. [Authenticatie](#authenticatie)
4. [Modellen](#modellen)
  1. [Introductie](#introductie)
  2. [Basis gebruik](#basis-gebruik)
  3. [Omzetten naar arrays/JSON](#omzetten-naar-arraysjson)

#Benodigdheden
Magister heeft een aantal benodigdheden:

* PHP 5.4 of hoger.
* Composer geïnstalleerd.

> [Waarom men Composer zou moeten gebruiken en hoe ermee te beginnen.](http://code.tutsplus.com/tutorials/easy-package-management-with-composer--net-25530)

#Om te beginnen
Magister maakt gebruik van Composer om de dependencies in te laden. Om gebruik te maken van Magister dien je Composer geïnstalleerd te hebben op je machine.

Als je reeds eerder met Composer hebt gewerkt dan weet je vast hoe makkelijk het is om dependencies in te laden.
Om Magister te gebruiken in je PHP project dien je de volgende regels aan je composer.json toe te voegen:

    "require": {
        "stanvk/magister": "dev-master"
    }

Na dit hoef je enkel nog het volgende commando uit te voeren om alle dependencies in te laden:

    composer update

#Gebruik
Om gebruik te maken van Magister, zou je code er als volgt uit kunnen zien:
```php
require 'vendor/autoload.php';

use Magister\Magister;

$magister = new Magister( string $school [, string $username , string $password ] );
```
Eerst laden we de gehele API in via de require functie. Daarna maken we een nieuwe instantie aan van de Magister class, die zich in de `Magister\Magister` namespace bevindt. Magister accepteert drie parameters, waarvan de eerste de school is, de tweede de gebruikersnaam en de derde het wachtwoord. De gebruikersnaam en het wachtwoord zijn optioneel.
> De 'school' parameter is het subdomein van je school: https://[school].magister.net.

#Authenticatie
Als de gebruiker de Magister instantie van een gebruikersnaam en wachtwoord heeft voorzien, wordt de gebruiker automatisch ingelogd op de Magister server met die gegevens.

Er zijn een aantal functies beschikbaar met betrekking tot het authenticatie proces. Als de gebruiker geen gebruikersnaam en wachtwoord heeft meegegeven, dient er zelfstandig ingelogd te worden met de volgende functie voordat er van de functionaliteiten van de API gebruik kunnen worden gemaakt:
```php
Auth::attempt(['Gebruikersnaam' => $username, 'Wachtwoord' => $password]);
```
De functie geeft een boolean terug die op `true` staat als de gebruiker succesvol ingelogd kon worden en op `false` als dit niet het geval is.

Om vervolgens op andere momenten te controleren of een gebruiker is ingelogd op de Magister server kunnen we de volgende regels code uitvoeren:
```php
if (Auth::check()) {
    echo 'De gebruiker is ingelogd!';
}
```
De functie `check()` geeft een boolean terug. Als de gebruiker succesvol is ingelogd, staat deze gelijk aan `true`, zo niet, dan aan `false`.

Mocht het voorkomen dat de gebruiker om welke reden dan ook niet ingelogd kon worden, dan kan men met de volgende functie de foutmelding ophalen:
```php
echo Auth::getError();
```
Er wordt een string teruggegeven met daarin de error afkomstig van de Magister server.

Voor de rest kan men de gebruiker uitloggen op de Magister server met de volgende functie:
```php
Auth::logout();
```
> Deze voorbeelden demonstreren de kracht van Magister. Magister levert een expressieve en makkelijk te gebruiken syntax, die nergens anders te vinden is.

#Modellen

## Introductie

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac mi eu erat finibus dignissim. Sed sit amet porttitor enim. Phasellus eu semper risus. Sed a fringilla libero, non dictum tortor. Cras posuere neque nulla, ut blandit arcu finibus ac. Quisque lacinia faucibus orci, at vulputate enim egestas eget. Vestibulum sed semper libero, ut faucibus dui. Nunc sed tincidunt massa, interdum mattis diam. Duis cursus sapien sed neque iaculis, quis varius tortor pellentesque.

## Basis gebruik

```php
use Magister\Models\Account;
```

```php
$profile = Account::profile();

var_dump($profile->Achternaam);
```

```php
$surname = Account::pluck('Achternaam');

var_dump($surname);
```

##Omzetten naar arrays/JSON

```php
return Account::profile()->toJson();
```

```php
return Account::profile()->toArray();
```