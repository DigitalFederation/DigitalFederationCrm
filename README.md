# Digital Federation CRM

Digital Federation CRM is an open-source Laravel and Vite platform for federation management. It provides operational workflows for members, entities, certifications, licenses, events, documents, payments, and public directories.

The project is released under the Apache License 2.0. It is provided as source code for self-hosting and adaptation. The maintainers do not provide guaranteed hosting, support, maintenance, or service-level commitments.

## Project Scope

This repository contains the generic application source for self-hosted federation operations.

Deployment-specific configuration, branding, logos, uploaded files, production data, business documents, and credentials must stay outside the repository.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL or a compatible database
- Redis, queues, object storage, mail, payment, Sentry, and invoicing integrations are optional and configured through environment variables

## Local Setup

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

`npm run build` also prepares frontend vendor assets such as TinyMCE under ignored public build paths.

Run the application locally:

```bash
php artisan serve
npm run dev
```

## Configuration

All secrets and deployment-specific values belong in `.env`, never in committed files.

Start from `.env.example` and configure:

- Application URL, database, cache, queue, session, and mail settings
- Public federation branding through `FEDERATION_*` and `INTERNATIONAL_FEDERATION_*`
- Public map scope through `PUBLIC_MAP_*`; leave `PUBLIC_MAP_COUNTRY_ID` empty unless the deployment intentionally publishes map locations for that country
- Optional Sentry DSNs
- Optional EasyPay payment credentials
- Optional Moloni invoicing credentials
- Optional local-only default admin seeding through `SEED_DEFAULT_ADMIN=true` and `DEFAULT_ADMIN_PASSWORD`
- Optional object storage credentials

Deployment logos should be stored in an ignored path such as `public/private-branding/` and referenced by env variables. Do not commit private branding assets.

## Testing And Quality

```bash
php artisan test
./vendor/bin/pest
./vendor/bin/phpstan analyse -c phpstan.neon
./vendor/bin/pint
npm run build
bash scripts/validate-no-generated-artifacts.sh --all
```

Use the smallest useful test set for focused changes, then run broader checks before release.

## Contributing

See `CONTRIBUTING.md` for contribution guidelines.

## Security

Please report vulnerabilities privately. See `SECURITY.md`.

## License

Licensed under the Apache License, Version 2.0. See `LICENSE`.
