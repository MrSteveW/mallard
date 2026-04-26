# Mallard Staff Management v1.0

## Description:

Staff management system for admin/authorisers to assign each user to one task per day.  
Admin edit a repeating shift pattern and populate a live diary of Duties.  
Admin manage and assign tasks for staff on duty each day.

## Current version features

- [x] Tech stack - Laravel 12 | Inertia | React | Livewire | PostgreSQL
- [x] Laravel Fortify for authentication with role-based user access (in Model Policies)
- [x] Calendar managed by [FullCalendar](https://fullcalendar.io/docs/react)
- [x] Drag N Drop by [dnd kit](https://dndkit.com/)
- [x] Bank Holidays listed by [Gov.uk API](https://www.api.gov.uk/gds/bank-holidays/#govuk-notification-banner-title)
- [x] Includes Laravel tools: Auth starter kit, Fortify, Wayfinder, Boost, Filament AdminPanel
- [x] Unit and Feature Pest testing
- [x] Includes: Service to import and cleanse Bank Holiday API. Actions to auto generate Duties and populate Bank Holidays into Calendar Notes.
- [x] Hosted by [Laravel Cloud](cloud.laravel.com)

## First Deployment

1. Set all environment variables from `.env.example` in Laravel Cloud
2. Run `php artisan migrate --force`
3. Run `php artisan app:bootstrap`

## Local development instructions

1. Fork the repository and clone your fork to your local machine
2. Run `npm install`
3. Run `php artisan app:migrate:fresh` to run SQLite migrations and seed data
4. Run `composer run dev` to start the PHP server and development server
5. Open http://localhost:8000 with your browser to see the app

## Development commands

Run `composer phpstan` to run PHPStan
Run `php artisan app:cleanse` to run Pint, all Pest tests and PHPStan.
Run `php artisan queue:work` to release queued jobs from database queue

[Mallard by freepick](https://www.freepik.com/free-vector/flat-mother-duck-ducklings-outside_5422457.htm#fromView=search&page=3&position=27&uuid=183c85bf-d0c6-42a3-a92e-1b01bf1eb975&query=mallard)
