Heureka Bidding API v PHP
=========================

Jednoduše použitelná knihovna pro komunikaci s [Heureka API](https://sluzby.heureka.cz/napoveda/bidding-api/) pro strojové získávání dat z Heureky.

V rámci tohoto API můžete stahovat informace o produktech, kategoriích, cenách, dostupnosti a podobně. Data získáváte ve strojově čitelné podobě jako nativní PHP entitu splňující rozhraní `Baraja\HeurekaBiddingApi\Response\Response`.

Instalace
---------

Jednoduše nainstalujeme [Composerem](https://php.baraja.cz/composer):

```shell
composer require baraja-core/heureka-bidding-api
```

A následně stačí knihovnu použít.

Hlavní přednost této knihovny je jednoduché nasazení, přičemž nemá závislost na žádném frameworku a funguje plně automaticky.

Použití v základním PHP
-----------------------

Pro vytvoření libovolného požadavku stačí vytvořit instanci služby `HeurekaApi`, předat `accessKey` a pokládat jednotlivé dotazy. Jako odpověď bude vždy typová entita.

Například chceme zjistit, kolik Heureka obsahuje kategorií:

```php
$api = new \Baraja\HeurekaBiddingApi\HeurekaApi('vas-access-klic');

$response = $api->run(\Baraja\HeurekaBiddingApi\HeurekaApi::METHOD_CATEGORY_INDEX);

echo $response->getCount();
```

Konkrétní metody pro volání vám bude napovídat přímo váš editor.

> **POZOR:**
>
> Pro volání metod vždy používejte vyhrazené konstanty, které garantují kompatibilitu vaší aplikace s aktuální verzí API.
>
> Pokud Heureka API změní, v rámci tohoto balíku vyjde aktualizace (kterou instalujete příkazem `composer update`) a díky použití konstant bude balík stále kompatibilní.

Použití v Nette
---------------

Knihovna je dobře kompatibilní s Nette. Pro funkčnost stačí do vašeho projektového `common.neon` vložit tuto konfiguraci (nezapomeňte uvést váš `access klíč`):

```yaml
services:
   heurekaApi: Baraja\HeurekaBiddingApi\HeurekaApi('vas-access-klic')
```

Pokročilé volání
----------------

Pro pokročilé sestavení požadavku vždy využijte [oficiální dokumentaci](https://api.heureka.cz/bidding_api/v1/apidoc), přičemž se předávají 3 parametry:

Request vždy sestavujte přes metodu `run()`, která obsahuje pokročilé validace a mapování výstupu do entit.

| Parametr | Povinný? | Vysvětlení |
|----------|----------|------------|
| `method` | ano      | Název API metody, kterou chceme volat. Například `category.index`. Hodnoty získáte v konstantách `METHOD_*`. |
| `params` | ne       | Volitelné parametry jako array. Parametry se předávají do API a souvisí s konkrétní metodou. Před zavoláním API se vždy podívejte do dokumentace. Knihovna provádí základní validaci vstupních parametrů. |
| `locale` | ne       | Pro jaké prostředí (jazyk) sestavit request? Výchozí hodnota je `cs` (čeština), akceptuje také `cz` (čeština). Pro slovenštinu `sk`. Jiné jazyky nejsou podporovány. |

Pro testování volání API lze poaždavek namířit také na jinou URL. Tyto URL je potřeba nejprve registrovat metodou `setCustomEndpoint(string $locale, string $endpoint)`, které předáme jazyk (například `cs`) a URL (`endpoint`). Vlastní přepisy URL adres mají při routingu vyšší prioritu, než výchozí konstanty. Přepis se provádí v metodě `resolveEndpoint()`, kterou můžete volně zavolat.

Ošetření chyb
-------------

Pokud při zpracování požadavku nastane jakákoli aplikační chyba, vyhazujeme výjimku `\Baraja\HeurekaBiddingApi\HeurekaException`.

V případě fatálního selhání (například parse error v odpovědi ze serveru) se vyhazuje interní `\RuntimeException`, kterou bychom neměli zachytávat, ale nechat probublat až na jádro aplikace a tam zalogovat. Runtime chyby se při správné konfiguraci knihovny nebudou nikdy vyhazovat a slouží pouze pro debug.

Access klíč
-----------

Pro komunikaci s API potřebujete od Heureky získat API klíč. Použití je omezeno podle počtu požadavků a poté se platí. Základní klíč se mi podařilo získat od podpory zdarma e-mailem (pro účely testování), ale raději na to nespoléhejte.

Informace z oficiální dokumentace:

> **Jak probíhá aktivace API?**
>
> Nejprve je potřeba potvrdit smlouvu na objednávku této služby, poté vám bude poskytnut přístup/token, dokumentace a další potřebné informace. Následně stačí implementovat API.

Potíže s knihovnou a hlášení chyb
---------------------------------

Jedná se o neoficiální knihovnu, u které není garantována funkčnost na vašem prostředí. Knihovna se i tak snaží splnit co nejvíce obecné rozhraní, aby měla širokou podporu na většině prostředí.

Pro korektní funkčnost potřebujete mít `PHP 7.1` nebo novější ([jak zjistit verzi PHP?](https://php.baraja.cz/info)).

Pokud se domníváte, že všechno děláte správně a v knihovně je chyba, [nahlašte to založením nové issue](https://github.com/baraja-core/heureka-bidding-api/issues).

Knihovnu vyvíjí [Jan Barášek](https://baraja.cz).
