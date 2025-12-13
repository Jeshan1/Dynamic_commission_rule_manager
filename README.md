# Commission Rules Management System

A Laravel + Vue.js application for managing airline/flight commission rules based on origins, destinations, rate value, and rate type.

## Features

- Create new commission rules (supports multiple rules at once)
- Edit existing commission rules
- Delete commission rules with confirmation
- List all commission rules in a clean table with continuous row numbering
- Server-side validation with clear, user-friendly error messages
- Responsive error handling using SweetAlert2
- Secure API with Bearer token authentication

## How to use ?
- first you can hit /admin or /login then login page appeared.
- Admin Credentials created initially through seeder.
- you can use admin email and password then you can redirect to admin panel.
- After you can go on commission module and then operate CRUD efficiently.

## Tech Stack

### Backend
- **Laravel** (PHP framework)
- **MySQL** database
- Eloquent ORM
- Form Request Validation
- Repository Pattern
- Service Layer

### Frontend
- **Vue 3** (Composition API with `<script setup>`)
- **Axios** for API calls
- **SweetAlert2** for modals and alerts

## Database Schema

- **`commission_rules`** table
  - `id` (primary key)
  - `rate_value` (decimal/number)
  - `rate_type` (enum: 'percentage', 'fixed')
  - timestamps

- **`commission_rule_origins`** table (hasMany)
  - `id`
  - `commission_rule_id` (foreign key)
  - `airport_code` (string, e.g., 'KTM', 'DEL')
  - timestamps

- **`commission_rule_destinations`** table (hasMany)
  - `id`
  - `commission_rule_id` (foreign key)
  - `airport_code` (string)
  - timestamps

## API Endpoints

| Method   | Endpoint                          | Description                  | Auth Required |
|----------|-----------------------------------|------------------------------|---------------|
| GET      | `/api/commission`                 | List all rules               | Yes           |
| POST     | `/api/commission`                 | Create new rule(s)           | Yes           |
| PUT      | `/api/commission/{id}`            | Update existing rule         | Yes           |
| DELETE   | `/api/commission/{id}`            | Delete a rule                | Yes           |

> **Note**: The `show` route is intentionally excluded from the API resource.

## Request Format Example

**Create / Update (JSON body):**

```json
{
  "rules": [
    {
      "origins": [{ "code": "KTM" }, { "code": "DEL" }],
      "destinations": [{ "code": "DXB" }, { "code": "SIN" }],
      "rate_value": 10.5,
      "rate_type": "percentage"
    }
  ]
}

## Feature Testing added for create,update and delete commission rule

## There is CommissionRuleTest.php file inside tests/feature/
** You can operate commission rule feature testing by triggering that command
`php artisan test --filter=CommissionRuleTest`