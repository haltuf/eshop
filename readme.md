Eshop Lectures
==============

V tomto repozitáři najdete tvorbu eshopu v Nette, PHP 8.1 a Doctrine ORM, krok za krokem.

Průběh vývoje zaznamenávám na video a bude součástí chystaného kurzu pro mírně pokročilé programátory.

Zájemci nechť dají sledovat tento repozitář (Watch nebo Favourite), o zveřejnění budu informovat.

## Instalace

1. Stáhněte nebo naklonujte repozitář:

`git clone git@github.com:haltuf/eshop.git`

2. Nainstalujte [Docker Desktop](https://www.docker.com/products/docker-desktop/) (pokud nemáte).

3. Spusťte skript `.docker/up.bat`, vyčkejte do jeho ukončení.
4. Do složky `www/frontend` je třeba nahrát soubory HTML šablony [Cartzilla](1.envato.market/7m9E4r) - všechny adresáře z adresáře `dist`. HTML šablona je bohužel placená, a tak její soubory nemohu dát jako součást tohoto repozitáře. 
5. Otevřete prohlížeč. E-shop najdete na těchto URL:
   - Homepage zatím není
   - Seznam produktů: `localhost/produkty`
   - Administrace: `localhost/admin`, login: `MichalHaltuf`, heslo: `test`
   - Databáze phpMyAdmin: `localhost:8080`

## Spouštění testů

Testy je možné spustit pomocí příkazu na příkazové řádce:

`.docker/test`

Pokud chcete pro urychlení spustit jen jeden test nebo test z jednoho adresáře, je možné uvést druhý volitelný parametr:

`.docker/test /Unit/Model/Entity/Stock.phpt`

`.docker/test /Unit/Model/Entity`

## Spouštění příkazů v dockeru

`docker exec eshop PRIKAZ`

## Vypnutí kontejnerů

`.docker/down`
