# LSW Cars · Projekt-Notizen

Stand: 12. Mai 2026

Diese Datei ist die zentrale Orientierung beim Wechsel auf einen anderen Rechner
oder beim Wiedereinstieg nach einer Pause. Hier steht, **wo wir gerade stehen**,
**was noch zu tun ist** und **wo Stolperfallen liegen**.

---

## 1) Schnellstart auf einem neuen Rechner

Voraussetzungen: PHP ≥ 8.2, Composer, MySQL (XAMPP), Node (optional, aktuell
nicht zwingend, da kein Vite-Build genutzt wird), Git.

```bash
git clone https://github.com/smp4000/lsw.cars.filament.git lsw.cars
cd lsw.cars

composer install
cp .env.example .env
php artisan key:generate

# MySQL-DB anlegen (z. B. via phpMyAdmin)
#   Name: lsw.cars   Charset: utf8mb4_unicode_ci

php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear
```

Aufrufen:
- Frontend → http://localhost/lsw.cars/public/
- Filament-Admin → http://localhost/lsw.cars/public/admin
- Login: `admin@admin.com` / `password`

Falls XAMPP-Apache direkt auf `lsw.cars` zeigen soll (ohne `/public`), eine
`.htaccess` im Wurzelverzeichnis kann das rewriten – ist aktuell nicht
vorhanden, da lokal `php artisan serve` reicht:

```bash
php artisan serve   # http://127.0.0.1:8000
```

---

## 2) Aktueller Stand (was fertig ist)

### Datenbank & Domain-Logik
- Migrations: `vehicles`, `vehicle_images`, `contact_messages`
- Eloquent-Modelle mit Relationen, Casts und Accessor-Attributen
  (`erstzulassung_formatiert`, `preis_formatiert`, `km_formatiert`)
- `VehicleImage::url` unterstützt sowohl Storage-Pfade als auch externe URLs
- `VehicleSeeder`: 6 Beispielfahrzeuge mit je 3 Unsplash-Bildern
- `DatabaseSeeder`: legt zusätzlich einen Filament-Admin an

### Filament-Admin (`/admin`)
- **VehicleResource** – CRUD mit Sections (Grunddaten, Technik, Ausstattung,
  Status), Filter (Marke, Kraftstoff, Status), Tabelle mit Bilderzähler
- **ImagesRelationManager** – Multi-Upload (Disk `public/vehicles`), Drag&Drop-
  Sortierung, Bild-Editor
- **ContactMessageResource** – Posteingang, Lesestatus-Toggle, Bulk-Aktion
  „als gelesen markieren", Navigation-Badge mit ungelesener Anzahl
- **StatsOverview-Widget** – KPIs auf dem Dashboard
- Branding: „LSW Cars", Primärfarbe Amber

### Frontend (Blade)
- Routen + Controller + Views: `/`, `/fahrzeuge`, `/fahrzeug/{id}`,
  `/leistungen`, `/ueber-uns`, `/kontakt`, `/impressum`, `/datenschutz`
- Layouts/Partials: `app.blade.php`, `header`, `footer`, `cookie-banner`,
  `whatsapp`
- Wiederverwendbare Komponente: `components/vehicle-card.blade.php`
- WhatsApp-FAB auf allen Seiten (Nummer im `AppServiceProvider`)
- Cookie-Banner mit Body-Klassen-Toggle
- Black-&-Gold-Theme (`public/assets/css/style.css` + `theme.css`) + JS
  (`public/assets/js/main.js`) – 1:1 aus dem pure-PHP-Vorprojekt übernommen
- Bilder/Logo unter `public/assets/images/` (`logo.png`, `amg-gts.jpg`)

### Behobener Bug
- Filament 5: `Section` ist im Namespace `Filament\Schemas\Components\Section`,
  **nicht** `Filament\Forms\Components\Section`. Falls noch andere
  Layout-Komponenten („Grid", „Tabs", „Group", „Wizard") gebraucht werden:
  ebenfalls aus `Filament\Schemas\Components\…` importieren.

---

## 3) Was noch offen ist (TODO)

### 🔥 Hohe Priorität – SEO-Paket
Detaillierter Plan steht in der Konversation. Ziel: Fahrzeuge bei Google gut auffindbar machen.

- [ ] **Slug-Spalte** in `vehicles` (Migration + Backfill) und Route
      `/fahrzeug/{slug}` statt `/fahrzeug/{id}` (alte IDs per Redirect)
- [ ] **Pro-Seite Title + Meta-Description** dynamisch aus Fahrzeugdaten
      (`@section('title')`, `@section('description')`)
- [ ] **JSON-LD `Vehicle`/`Car`-Schema** auf Detailseite (Preis, Marke, KM,
      Bilder, Verfügbarkeit – Google-Rich-Cards)
- [ ] **JSON-LD `AutoDealer` (LocalBusiness)** auf Start- + Kontaktseite
      (Adresse, Öffnungszeiten, Telefon, Geo)
- [ ] **Open Graph + Twitter Cards** im Layout
- [ ] **Dynamische `sitemap.xml`-Route**, `robots.txt`
- [ ] **Breadcrumbs** als Blade-Component + Schema
- [ ] **Image-Optimierung** – `srcset`, ALT-Texte aus Titel + Marke generieren
- [ ] Performance: Response-Cache, gzip, optional `intervention/image` für WebP

**Außerhalb des Codes:**
- [ ] Google Business Profile (Petersberg) anlegen
- [ ] Google Search Console verbinden, Sitemap einreichen

### 🟡 Mittlere Priorität – Mobile.de-Anbindung
- [ ] Klären: ist LSW Cars als **Mobile.de-Händler** registriert?
- [ ] Variante A (mit API-Zugang): XML-Feed-URL holen, Sync-Command
      `php artisan vehicles:sync-mobilede` bauen, täglich per Cronjob
- [ ] Variante B (ohne API): Filament-Importer-Action „Mobile.de CSV
      importieren" mit Spalten-Mapping

### 🟡 Mittlere Priorität – Instagram
- [ ] Instagram-Profilname festlegen (z. B. `@lsw_cars`)
- [ ] Stufe 1: Instagram-Icon-Link in Header/Footer + „Folgen Sie uns"-Card
      auf der Startseite
- [ ] Stufe 2 (optional): Live-Feed via **Snapwidget**/**Elfsight** als
      Blade-Component einbinden (Instagram Basic Display API ist seit
      04.12.2024 abgeschaltet → API selbst bauen lohnt sich nur, wenn schon
      Business-Account + Meta-Entwickler-Setup vorhanden)
- [ ] Stufe 3: Filament-Action „Auf Instagram teilen" am Fahrzeug
      (Caption-Vorlage + Bilder-ZIP)

### 🟢 Niedrige Priorität / Schliff
- [ ] **Produktiv-Deployment** auf All-Inkl klären
      (Subdomain-DocumentRoot auf `…/lsw.cars/public` zeigen lassen,
      `.env` per SSH/SFTP, `composer install --no-dev`, `php artisan
      migrate --force`, `php artisan storage:link`,
      `php artisan optimize`)
- [ ] **Admin-Passwort** vor dem Go-Live ändern
      (`User::find(1)->update(['password' => bcrypt('…')])`)
- [ ] **Firmendaten** in `AppServiceProvider::boot()` final prüfen
      (Impressum/Datenschutz spiegeln sie)
- [ ] **Mail-Versand** statt nur DB-Speicherung der Kontaktanfragen
      (`Notification` an `COMPANY_EMAIL`, SMTP-Daten in `.env`)
- [ ] **Tests** (Pest/PHPUnit) – aktuell ungenutzte Skelette

---

## 4) Stolperfallen / Hinweise

- **Filament 5 Namespaces**: Layout-Komponenten (`Section`, `Grid`, `Tabs`)
  unter `Filament\Schemas\Components\…`, Form-Inputs (`TextInput`,
  `Textarea`, `Toggle`) unter `Filament\Forms\Components\…`. Beim Hinzufügen
  neuer Felder darauf achten – `make:filament-resource` setzt das nicht immer
  konsistent.
- **`php artisan optimize:clear`** ist nach Änderungen an Resources/Schemas
  Pflicht, sonst zeigt Filament noch die alte Definition.
- **Storage-Link**: `php artisan storage:link` muss auf jedem neuen Rechner
  einmalig ausgeführt werden, sonst werden hochgeladene Bilder nicht
  ausgeliefert (404 in der Übersicht).
- **Seeder-Bilder kommen von Unsplash** (externe URLs in `vehicle_images.
  dateiname`). Funktioniert nur mit Internetzugang. Bei Offline-Setup besser
  echte Bilder hochladen oder den Seeder anpassen.
- **`make:filament-relation-manager` läuft interaktiv** und blieb beim
  letzten Versuch hängen – die Datei `ImagesRelationManager.php` wurde
  deshalb manuell angelegt. Falls weitere RelationManager nötig sind, am
  besten ebenfalls per Hand schreiben.

---

## 5) Verwandte Repos

- **Aktuelles Laravel/Filament-Projekt** → https://github.com/smp4000/lsw.cars.filament
- **Vorgänger (pure PHP/MySQL)** → https://github.com/smp4000/lsw-cars
  (lokaler Pfad: `C:\xampp\htdocs\lsw-cars\`)
  Server-Deployment lief auf All-Inkl unter
  https://lsw-cars.aral-welle.com/ – nicht mit dem Laravel-Projekt
  verwechseln.
