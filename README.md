# Loyalty and Rewards Administration Platform

## Overview
A Laravel application for managing user points, balances, invoice records, point deductions, and administrative account workflows.

## Problem / Context
The source models a points-based loyalty workflow with supporting invoice and account administration.

## Core Capabilities
- User and point-balance records.
- Invoice creation/edit history and point deductions.
- Administrative point/user management and account activation/recovery flows.

## Architecture
Laravel 12/PHP with Eloquent models, migrations, controllers, Blade views, and JavaScript. Environment files, logs, seed data, and database exports are excluded.

## Tech Stack
Laravel, PHP, Eloquent, Blade, Composer, JavaScript, npm.

## Main Workflows
Administrators manage accounts and point records; invoice and deduction histories support loyalty activity review.

## Domain Model
Users, points, balances, invoices, invoice edit history, deductions, activation, and recovery records appear in the source.

## Integrations
No external loyalty, payment, or messaging integration was verified.

## Engineering Highlights
The source separates user-facing and administrative point/invoice workflows and records edit/deduction history.

## Setup
Install PHP and Composer dependencies, copy `.env.example` to a local `.env` and generate an application key, generate an application key, and use a disposable local database.

## Configuration
Configure database, mail, and app settings locally. No credentials or user records are included.

## Testing
PHP syntax and Composer manifest validation are performed for this sanitized snapshot.

## Security & Privacy
Database exports, logs, environment files, seeders, and user/contact data are excluded or normalized.

## Limitations
Business rules, production use, reward redemption, integrations, and current operational status were not verified.

## Project Status
Legacy application source published for engineering review.

## Ownership / Provenance
Source supplied by Mohamed Gamal, who confirms authority to publish his work. Original Bitbucket history was not copied; this repo starts with a clean Git history.

## License / Usage
The supplied Composer manifest declares MIT; its exact project scope was not independently established. Dependency and retained third-party notices remain applicable.
