# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Common Commands

### Development
```bash
# Install dependencies
composer install

# Run development server
symfony serve
# or
php -S localhost:8000 -t public/

# Clear cache
php bin/console cache:clear

# Database migrations
php bin/console doctrine:migrations:migrate
php bin/console doctrine:migrations:diff  # Generate migration from entity changes
```

### Testing
```bash
# Run all tests
php bin/phpunit

# Run specific test file
php bin/phpunit tests/SomeTest.php

# Run tests with coverage
php bin/phpunit --coverage-html coverage/
```

### CLI Commands (Module-Specific)
```bash
# Create Extranet user
php bin/console extranet:user:create

# Create Taxe user
php bin/console taxe:createuser

# Generate QR code
php bin/console qrcode:generate
```

### Database
```bash
# Create database
php bin/console doctrine:database:create

# View all entities and mappings
php bin/console doctrine:mapping:info

# Validate schema
php bin/console doctrine:schema:validate
```

---

## Project Summary

This is a **multi-module monolithic Symfony 8.1 application** serving a Belgian local government (Marche) with six distinct modules, each operating as a semi-independent bundle with its own configuration, entities, and controllers. The architecture follows a modular pattern where modules are loaded via custom Symfony bundles and have separate database tables (using table prefixes).

**Technology Stack:**
- PHP 8.4+
- Symfony 8.1
- Doctrine ORM
- PostgreSQL (implied by DBAL setup)
- Twig templating
- VichUploaderBundle for file uploads
- KnpSnappyBundle for PDF generation
- Endroid QR Code library for QR code generation
- LDAP authentication support

---

## Module Structure (src/AcMarche/)

### 1. **Extranet Module** - Core user authentication & management
- **Purpose:** Central authentication system with LDAP integration and user management for other modules
- **Key Files:**
  - `Entity/User.php` - Implements `UserInterface`, `PasswordAuthenticatedUserInterface` - central user entity
  - `Security/Authenticator/ExtranetLdapAuthenticator.php` - LDAP-based authentication
  - `Security/Authenticator/ExtranetAuthenticator.php` - Standard authentication
  - `Security/Ldap/LdapExtranet.php` - LDAP adapter
  - `Security/SecurityRoles.php` - Role constants
  - `Controller/SecurityController.php` - Login/logout handling
  - `Controller/UserController.php`, `PasswordController.php` - User management
  - `Command/ExtranetUserCommand.php` - CLI user creation
- **Database:** Single `User` entity
- **Key Services:** LDAP connection, user repository, authentication
- **Security:** Handles all authentication for the entire application via Security bundle configuration

### 2. **Taxe Module** - Tax file management system
- **Purpose:** Manage tax nomenclature, exercises, and associated documents
- **Key Entities:**
  - `Taxe.php` - Tax definition (nom, position, nomenclature reference, exercices collection)
  - `Nomenclature.php` - Tax category/grouping
  - `Exercice.php` - Year-specific tax exercise with file upload
- **Controllers:**
  - `TaxeController.php` - CRUD operations for taxes (requires `ROLE_TAXE_ADMIN`)
  - `ExerciceController.php` - Manage exercices per tax
  - `NomenclatureController.php` - Manage nomenclatures
  - `SortController.php` - Sorting operations
  - `ApiController.php` - JSON endpoint (`/api2`) returning hierarchical tax data
- **Key Features:**
  - Form-based search with `SearchTaxeType`
  - File upload for exercises
  - Repository search patterns
  - Custom command for user creation
- **Route Prefix:** `/taxes`
- **Database Tables:** `taxe_*` (prefixed)
- **Notable Pattern:** `OrmCrudTrait` in `Doctrine/` for common persist/flush operations

### 3. **Patrimoine Module** - Heritage/asset management
- **Purpose:** Manage cultural heritage items, locations, classifications, and associated images
- **Key Entities:**
  - `Patrimoine.php` - Main heritage item with image collection, localisation, statut, type
  - `Image.php` - Image files with VichUploaderBundle integration
  - `Localite.php` - Location/city reference
  - `Statut.php` - Heritage status (e.g., registered, protected)
  - `TypePatrimoine.php` - Heritage type classification
- **Controllers:**
  - `PatrimoineController.php` - Main CRUD operations
  - `ImageController.php` - Image management
  - `LocalisationController.php` - Location management
  - `LocaliteController.php` - City/locality management
  - `StatutController.php`, `TypePatrimoineController.php` - Classification management
- **Key Features:**
  - Image upload & manipulation (VichUploaderBundle + LiipImagineBundle)
  - Geolocation support
  - Custom Doctrine event listeners for table prefix
- **Route Prefix:** `/patrimoine`
- **Database Tables:** `patrimoine_*` (prefixed)
- **Notable Bundles:** VichUploaderBundle, LiipImagineBundle

### 4. **Leasing Module** - Bicycle leasing calculator
- **Purpose:** Complex leasing calculation for subsidized bicycle schemes (government employees)
- **Key Components:**
  - `Entity/LeasingData.php` - DTO holding calculation parameters (statut, values, kilometers)
  - `Handler/LeasingCalculatorInterface.php` - Pluggable calculation strategy
  - `Handler/LeasingCalculatorStatutaire.php` - Calculation for permanent employees
  - `Handler/LeasingCalculatorContractuel.php` - Calculation for contractual employees
  - `Handler/LeasingFactory.php` - Factory creating appropriate calculator based on status
  - `Handler/CalculatorCommonTrait.php` - Shared calculation logic
  - `Handler/KilometerHandler.php` - Kilometer tracking
- **Forms:**
  - `LeasingType.php` - Main data input form (statut, values, etc.)
  - `KilometerForm.php` - Kilometer tracking form
  - `LeasingSendType.php` - Submit/send form
- **Controllers:**
  - `DefaultController.php` - Main form display & processing
  - `KilometerController.php` - Kilometer tracking
- **Mailer:** `MailerLeasing.php` - Sends calculation results via templated email
- **Key Features:**
  - Strategy pattern for different calculation methods
  - PDF generation/download
  - Email notifications
  - Complex financial calculations with multiple formula steps
- **No Route Prefix** (loads at root)
- **Database Tables:** None (pure calculation logic)
- **Notable Pattern:** Strategy pattern for calculators, trait-based shared logic

### 5. **EnquetePublique Module** - Public inquiry/consultation management
- **Purpose:** Manage public consultations, inquiries, documents, and categories
- **Key Entities:**
  - `Enquete.php` - Main inquiry/consultation record
  - `Document.php` - Associated documents with file uploads
  - `Categorie.php` - Document category
  - `CategorieWp.php` - WordPress category integration
- **Entity Traits:**
  - `DatesDiffusionTrait.php` - Start/end dates for publication
  - `LocationTrait.php` - Geolocation support
  - `AvisFileTrait.php` - Opinion/feedback file handling
- **Controllers:**
  - `DefaultController.php` - Main listing
  - `EnqueteController.php` - CRUD operations
  - `DocumentController.php` - Document management
  - `CategorieController.php`, `CategorieWpController.php` - Category management
  - `LocalisationController.php` - Location management
  - `ApiController.php` - JSON endpoint
- **Messaging:** Async message handling with `Enquete/Message/` and `Enquete/MessageHandler/`
- **Route Prefix:** `/enquete`
- **Database Tables:** `enquete_*` (prefixed)
- **Notable Features:** Doctrine event listeners, file upload support, WordPress integration

### 6. **QrCode Module** - QR code generation
- **Purpose:** Generate customizable QR codes (URL, WiFi, Email, Phone, EPC/Payment)
- **Key Components:**
  - `Entity/QrCode.php` - QR code definition (type, color, size, format, style) with UUID tracking
  - `QrCodeGenerator.php` - Main generation logic using Endroid library
  - `QrBuilder.php` - Builder pattern for complex QR code configurations
  - `Controller/DefaultController.php` - Display & generation
  - `Controller/ApiController.php` - API endpoint
  - `Command/QrCodeCommand.php` - CLI generation
- **Form Types:**
  - `QrGeoType.php`, `QrWifiType.php`, `QrEmailType.php`
  - `QrPhoneNumberType.php`, `QrEpcType.php`
  - Each for specific QR code data types
- **Key Features:**
  - Enum-like DataTypes for different QR formats
  - Logo embedding (Marche logo)
  - Support for SVG, PNG, and other formats
  - Customizable colors, size, margins
- **No Route Prefix** (loads at root)
- **Database Tables:** `qrcode_*` (prefixed)
- **Newest Module:** Recently added (Oct 2024)

---

## Architectural Patterns & Design

### Bundle Structure
Each module follows the **AbstractBundle pattern** with:
```
ModuleName/
├── config/
│   ├── services.php          # Service container configuration with auto-discovery
│   ├── packages/             # Optional package-specific configs
│   │   ├── doctrine.php
│   │   ├── twig.php
│   │   ├── vich_uploader.php
│   │   ├── liip_imagine.php
│   │   └── security.php
│   └── (routing auto-discovered via attributes)
├── src/
│   ├── *Bundle.php           # Bundle class extending AbstractBundle
│   ├── Controller/           # Route-attributed controllers
│   ├── Entity/               # Doctrine ORM entities
│   ├── Repository/           # Custom repository classes
│   ├── Form/                 # Symfony Form types
│   ├── Doctrine/             # Custom Doctrine extensions (traits, listeners)
│   ├── Security/             # Authentication/authorization (if module-specific)
│   ├── Command/              # Symfony CLI commands
│   └── [Domain-specific folders] # Service classes, handlers, etc.
├── templates/                # Module Twig templates (auto-namespaced as @ModuleName)
├── public/                   # Static assets
└── composer.json             # (for standalone git repos)
```

### Service Configuration Pattern
All modules use **PHP-based service configuration** with consistent defaults:
```php
$services->defaults()
    ->autowire()        # Auto-wire dependencies
    ->autoconfigure()   # Auto-tag services
    ->private();        # Services private by default

$services->load('AcMarche\\ModuleName\\', __DIR__.'/../src/*')
    ->exclude([__DIR__.'/../src/{Entity,Tests}']);  // Exclude entities from auto-discovery
```

### Routing Configuration
Centralized in `/config/routes.yaml`:
- **App namespace:** `/src/Controller/` → `/` (attribute routing)
- **Extranet:** `/src/AcMarche/Extranet/src/Controller/` → `/` prefix
- **Taxe:** `/src/AcMarche/Taxe/src/Controller/` → `/taxes` prefix
- **Patrimoine:** `/src/AcMarche/Patrimoine/src/Controller/` → `/patrimoine` prefix
- **EnquetePublique:** `/src/AcMarche/EnquetePublique/src/Controller/` → `/enquete` prefix
- **Leasing:** `/src/AcMarche/Leasing/src/Controller/` → no prefix
- **QrCode:** `/src/AcMarche/QrCode/src/Controller/` → no prefix

Routes use **PHP Attributes** (`#[Route(...)]`) for definition.

### Template Namespacing
Modules can be referenced in templates via their namespace:
- `@Taxe/taxe/index.html.twig`
- `@AcMarcheLeasing/leasing/mail.html.twig`
- `@Patrimoine/...`
- etc.

### Shared Traits & Patterns

**OrmCrudTrait** (in each module's `Doctrine/`):
```php
// Used in Repository classes for common operations
public function insert(object): void
public function persist(object): void
public function flush(): void
public function remove(object): void
public function getOriginalEntityData(object): array
```

**NameEntityTrait** (Taxe module, reused in Extranet):
```php
// Provides standard nom/prenom fields
private ?string $nom = null;
private ?string $prenom = null;
// With getters/setters
```

**UuidTrait** (Leasing, QrCode modules):
```php
// Provides UUID identifier support for entities
```

**Entity Traits for Domain Logic:**
- `DatesDiffusionTrait` - Dates for content publication
- `LocationTrait` - Geolocation support
- `AvisFileTrait` - File attachment handling

---

## Database Architecture

### Table Naming & Prefixing
- Tables per module are prefixed: `taxe_*`, `patrimoine_*`, `enquete_*`, `qrcode_*`
- Implemented via Doctrine event listeners (see `Patrimoine/Doctrine/TablePrefix.php` pattern)
- Each module has own `composer.json` with git repo (submodule-style structure)

### Key Relationships
- **Users** (Extranet) → referenced by role-based controllers across modules
- **Nomenclature** → **Taxe** → **Exercice** (1:N hierarchies)
- **Patrimoine** → **Image** (1:N with VichUploader integration)
- **Enquete** → **Document** → **Categorie** (hierarchical)

---

## Security & Authentication

### Authentication Strategy
1. **LDAP Primary:** `ExtranetLdapAuthenticator` for LDAP directory integration
2. **Fallback:** `ExtranetAuthenticator` for database users
3. **User Entity:** Single `AcMarche\Extranet\Entity\User` implements Symfony security interfaces

### Authorization
- **Role-based:** Defined in module-specific `SecurityRoles.php` classes
  - `ROLE_TAXE_ADMIN` - Tax management
  - `ROLE_PATRIMOINE_*` - Heritage management
  - `ROLE_ENQUETE_*` - Public inquiry management
- **Attribute-based:** Routes protected via `#[IsGranted('ROLE_*')]` attributes
- **Centralized Security Config:** In Extranet module `/config/packages/security.php`

---

## Key Cross-Cutting Concerns

### File Uploads
- **VichUploaderBundle:** Patrimoine (images), EnquetePublique (documents)
- **Configuration:** Module-specific `/config/packages/vich_uploader.php`
- **Image Processing:** LiipImagineBundle for Patrimoine and EnquetePublique

### Email/Notifications
- **MailerLeasing:** Sends leasing calculation results
- **Symfony Mailer:** Via `MailerInterface` injection
- **Templated Emails:** Using Twig templates in module directories

### PDF Generation
- **KnpSnappyBundle:** PDF output (used by Leasing for calculations)
- **wkhtmltopdf-amd64:** System dependency

### API Endpoints
- **Taxe:** `/api2` - Returns hierarchical JSON of nomenclatures, taxes, exercices
- **EnquetePublique:** API endpoint for inquiry data
- **QrCode:** API endpoint for QR generation
- **Pattern:** Controllers return `JsonResponse` with serialized data

### Commands
- **ExtranetUserCommand:** Create users via CLI
- **TaxeCreateuserCommand:** Create Taxe-specific users
- **QrCodeCommand:** Generate QR codes from CLI

---

## Important Configuration Files

### Application Level
- `/config/bundles.php` - Bundle registration (including all 6 modules)
- `/config/routes.yaml` - Route import with prefixes for all modules
- `/config/services.yaml` - App-level service defaults
- `/config/packages/security.php` - Authentication & authorization
- `/config/packages/doctrine.php` - Database connection (DBAL, ORM)

### Module Level (Example: Taxe)
- `src/AcMarche/Taxe/config/services.php` - Service auto-discovery
- `src/AcMarche/Taxe/config/packages/twig.php` - Template config
- `src/AcMarche/Taxe/config/packages/vich_uploader.php` - File upload config
- `src/AcMarche/Taxe/config/packages/doctrine.php` - Table prefix setup

---

## Common Development Patterns

### Controller Pattern
```php
class TaxeController extends AbstractController {
    public function __construct(
        private readonly TaxeRepository $taxeRepository
    ) {}
    
    #[Route(...)]
    #[IsGranted('ROLE_TAXE_ADMIN')]
    public function action(Request $request): Response {
        // Form handling, repository query, render
    }
}
```

### Service Injection Pattern
- Constructor-based dependency injection with `readonly` properties
- Autowiring via service.php configuration
- No factory classes (direct instantiation via container)

### Repository Pattern
- Custom repositories extend `ServiceEntityRepository`
- Use `OrmCrudTrait` for common operations
- Custom `search()` methods for specific queries

### Form Type Pattern
```php
class TaxeType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        // Add fields
    }
    public function configureOptions(OptionsResolver $resolver): void {
        // Set data_class
    }
}
```

---

## Development Workflow

### Adding a New Feature
1. Create entity in module's `Entity/`
2. Create repository extending `ServiceEntityRepository`
3. Create form type in `Form/`
4. Create controller(s) in `Controller/` with route attributes
5. Create Twig templates in module's `templates/`
6. Configure any bundles needed in `config/packages/`
7. Add service/handler classes as needed in module root or subdirectories
8. Commands auto-discovered, routes auto-discovered via attributes

### Database Migrations
- Use `bin/console doctrine:migrations:*` commands
- Migrations generated with `make:migration`
- Each module can have entity changes, but migrations are app-level

### Testing
- No test directories currently visible
- Could use standard Symfony testing patterns with `KernelTestCase`, `WebTestCase`

---

## Key Dependencies & Technologies

| Technology | Purpose |
|-----------|---------|
| **Symfony 8.1** | Core framework |
| **Doctrine ORM 3.x** | Database abstraction |
| **Twig 3.x** | Template engine |
| **VichUploader** | File upload handling |
| **LiipImagine** | Image processing |
| **KnpSnappy** | PDF generation |
| **Endroid QrCode** | QR code generation |
| **LaraZeus QrCode** | QR code library wrapper |
| **LDAP** | Authentication backend |
| **KnpDoctrineBehaviors** | Timestamps, slugs, etc. |
| **PhpSpreadsheet** | Excel file handling |
| **Symfony UX** | Frontend interactivity (Stimulus, Turbo, Dropzone) |

---

## Notes & Conventions

1. **Namespacing:** All modules under `AcMarche\` namespace (Belgian local government brand)
2. **Module Independence:** Each module is semi-independent (git submodules) but share:
   - Single User entity (from Extranet)
   - Single database (with table prefixes)
   - Security framework
3. **PHP 8.4+ Features:** Uses attributes, readonly properties, modern type hints
4. **No Traditional Services Layer:** Logic kept in handlers, commands, or directly in repositories
5. **Template Namespace:** Auto-mapped as `@ModuleName` via bundle configuration
6. **Trait Usage:** Common functionality via traits (CRUD, entities, domain logic)
7. **Constructor Injection:** Preferred over setters, with `readonly` keyword

---

## Quick Reference: Module Responsibilities

| Module | Responsibility | Key Entities | Route Prefix |
|--------|---------------|--------------|-------------|
| **Extranet** | Auth, user mgmt | User | `/` |
| **Taxe** | Tax files, docs | Taxe, Nomenclature, Exercice | `/taxes` |
| **Patrimoine** | Heritage assets, images | Patrimoine, Image, Localite | `/patrimoine` |
| **Leasing** | Bike subsidy calculations | LeasingData (DTO) | none |
| **EnquetePublique** | Public consultations | Enquete, Document, Categorie | `/enquete` |
| **QrCode** | QR code generation | QrCode | none |

---

## For Future Developers

- **Module Pattern:** Always extend `AbstractBundle`, use PHP service config
- **New Entity?** Add trait if logic is reusable; use OrmCrudTrait in repository
- **File Upload?** VichUploader is configured per module; reference existing configs
- **Auth Check?** Use `#[IsGranted(...)]` attribute on controller methods
- **Complex Logic?** Create handler classes or services, not in controllers
- **API Output?** Return `JsonResponse` from controller; use `SerializerInterface` if complex
- **Template Names:** Follow `@ModuleName/feature/action.html.twig` convention
- **Tests:** Create in module-level `Tests/` directory when adding

---

**Last Updated:** 2025-11-17
**Architecture Version:** Symfony 8.1 Multi-Module (2024-10)
