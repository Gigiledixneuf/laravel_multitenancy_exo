
# Laravel Tenancy (Multi-tenancy) Setup
Ce guide explique comment configurer le package [stancl/tenancy](https://github.com/stancl/tenancy) pour ajouter la multi-tenancy à une application Laravel.
## Installation
### 1. Installation du package
Installez le package via Composer :
```bash
composer require stancl/tenancy
```
### 2. Configuration
Publiez les fichiers de configuration :
```bash
php artisan tenancy:install
```
Cela va créer les fichiers suivants :
- `config/tenancy.php`
- `routes/tenant.php`
- `database/migrations/landlord/2019_09_15_000010_create_tenants_table.php`
- `database/migrations/tenant/2019_09_15_000020_create_domains_table.php`

### 3. Configuration de la base de données
Modifiez votre fichier `.env` pour ajouter la connexion pour la base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=central
DB_USERNAME=root
DB_PASSWORD=
```
Et, ajoutez la session centralisée :
```
SESSION_DRIVER=database
# SESSION_CONNECTION=your-session-connection sert à reunir la session de toute l'application dans une seule base de données centrale
SESSION_CONNECTION=mysql
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```
### 4. Modification du modèle Tenant
Dans le modèle `App\Models\Tenant` (créé par la migration), ajoutez le trait `HasDatabases` et `HasDomains` :
```php
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
}
```
### 5. Ajouter Tenancy dans le provider
Assurez-vous que le middleware de tenancy est appliqué dans `app/bootstrap/providers.php` :
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
];
```
### 6. Configuration des modèles tenant
Pour chaque modèle qui doit être scoped au tenant, ajoutez le trait `BelongsToTenant` :
```php
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
class ExampleModel extends Model
{
    use BelongsToTenant;
}
```
## Configuration des domaines en local
Pour éviter d'avoir à modifier manuellement le fichier `hosts` à chaque création de tenant, il est recommandé d'utiliser un DNS proxy.
### Option 1 : Utilisation de Laravel Valet (macOS)
Si vous utilisez Laravel Valet, vous pouvez configurer un wildcard DNS :
```bash
valet park
```
Cela permettra d'accéder à tous les sous-domaines de votre dossier racine Valet (par exemple, `tenant1.test`, `tenant2.test`).
### Option 2 : Utilisation de dnsmasq (Linux/macOS)
Installez dnsmasq :
**Sur macOS (avec Homebrew) :**
```bash
brew install dnsmasq
```
**Sur Ubuntu/Debian :**
```bash
sudo apt install dnsmasq
```
Configurez dnsmasq pour rediriger tous les sous-domaines de `.localhost` vers `127.0.0.1` :
Créez un fichier de configuration pour dnsmasq (par exemple `/usr/local/etc/dnsmasq.conf` sur macOS, `/etc/dnsmasq.conf` sur Linux) et ajoutez :
```
address=/localhost/127.0.0.1
```
Démarrez dnsmasq :
```bash
sudo brew services start dnsmasq  # macOS avec Homebrew
sudo systemctl start dnsmasq     # Linux
```
Configurez votre système pour utiliser dnsmasq comme serveur DNS pour les domaines `.localhost`. Sur macOS, allez dans les Préférences Système > Réseau > Avancé > DNS, et ajoutez `127.0.0.1` en haut de la liste des serveurs DNS.
### Option 3 : Utilisation de un fichier hosts générique (Non recommandé)
Si vous ne pouvez pas utiliser de DNS proxy, vous pouvez ajouter une entrée wildcard dans votre fichier `hosts` (bien que cela ne fonctionne pas sur tous les systèmes) :
```
127.0.0.1 *.localhost
```
Cependant, cette méthode n'est pas supportée par tous les systèmes d'exploitation (Windows la supporte, mais macOS et Linux non).
## Utilisation
### Création d'un tenant
Vous pouvez créer un tenant dans votre code :
```php
$tenant = Tenant::create(['id' => 'tenant1']);
$tenant->domains()->create(['domain' => 'tenant1.localhost']);
```
Ou via une commande artisan :
```bash
php artisan tenancy:create-tenant tenant1 tenant1.localhost
```
### Accès au tenant
Une fois configuré, vous pouvez accéder à votre application via l'URL du tenant, par exemple : `http://tenant1.localhost`.
## Documentation officielle
Pour plus de détails, consultez la [documentation officielle de stancl/tenancy](https://tenancyforlaravel.com/).
---
