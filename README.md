# zadanie_enis

Wymagania:
- php7.2
- php7.2-sqlite3

<code>
composer install
</code>

<br>
Uruchomienie:
<br>
<br>
<b>Import:</b><br>
<code>
cd public/
</code>
<br>
<code>
php import.php ../data/data.csv
</code>

<br>
<br>
<b>Ostatni import oraz lista:</b><br>
Uruchamiamy serwer
<code>
sudo php7.2 -S 127.0.0.1:81 -t public
</code>
<br>
Wchodzimy przez przeglądarkę na:
http://127.0.0.1:81/
