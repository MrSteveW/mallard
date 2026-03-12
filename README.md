# Mallard booking system v3

## Description:

Booking system for managers to book each user to one task per day.

## Current version features

User table id | name | grade | date_started | date_ended | (soft)deleted_at  
Task table id | name | (soft)deleted_at  
Duty table id | user_id | task_id | dutydate | shift_type | hours

- [x] Tech stack - Laravel | Inertia | React | Tailwind
- [x] Auth with Laravel built-in auth
- [x] Model, resource controller and migration for User, Task and Duty.
- [x] Softdelete for User and Task

## Setup instructions

1. Fork the repository and clone your fork to your local machine
2. Run `npm install`
3. Run `php artisan migrate` to run all SQLite migrations on your local machine
4. Run `composer run dev` to start the PHP server and development server
5. Open http://localhost:8000 with your browser to see the app
