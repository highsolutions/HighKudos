## ...

### Instalacja

* pobrać projekt z repozytorium - `git pull origin master`
* skierować domenę na katalog `public`
* odpalić komendę `composer install`
* odpalić komendę `npm install`
* odpalić komendę `php artisan env:set AAAA`, gdzie AAAA to nazwa środowiska, które istnieje w katalogu environment
* odpowiednio skonfigurować plik `.env`, jeśli wymagane są zmiany
* odpalić komendę `php artisan key:generate` ze względów na bezpieczeństwo
* odpalić komendy `chmod 0777 boostrap/cache` i `chmod 0777 storage`
* odpalić komendę `php artisan migrate --seed` do utworzenia tabel w bazie danych
* odpalić komendę `php artisan storage:link` do utworzenia symlinków w katalogu public
* odpalić komendę `npm run dev` do kompilacji assetów (css i js)

### Aktualizacja

* pobrać wszystkie zmiany na repozytorium - `git fetch --all`
* pobrać aktualną wersję z repozytorium - `git reset --hard origin/master`
* odpalić komendę `composer install` do zaktualizowania bibliotek PHP / jeśli jakieś zmiany w pliku `/composer.json`
* odpalić komendę `npm install` do zaktualizowania paczek JS / jeśli jakieś zmiany w pliku `/package.json`
* odpalić komendę `php artisan env:set AAAA`, gdzie AAAA to nazwa środowiska, które istnieje w katalogu environment
* odpalić komendę `php artisan migrate` do zaktualizowania tabel w bazie danych / jeśli jakieś zmiany w katalogu `/database/migrations`
* odpalić komendę `npm run dev` do kompilacji assetów (css i js)
