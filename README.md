# Project Manager API

This Laravel-based API manages users, projects, timesheets, and dynamic project attributes using an Entity-Attribute-Value (EAV) model. It leverages Laravel Passport for token-based authentication and supports flexible filtering with basic operators.

## Table of Contents

- [Setup Instructions](#setup-instructions)
- [API Documentation](#api-documentation)
  - [Authentication Endpoints](#authentication-endpoints)
  - [User Endpoints](#user-endpoints)
  - [Project Endpoints](#project-endpoints)
  - [Timesheet Endpoints](#timesheet-endpoints)
  - [Attribute Endpoints](#attribute-endpoints)
- [Example Requests/Responses](#example-requestsresponses)
- [Test Credentials](#test-credentials)

## Setup Instructions

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/bilahdsid/astudio-project-management
   cd project-manager-api
   ```

2. **Install Dependencies:**
   Make sure you have Composer installed, then run:
   ```bash
   composer install
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   ```
   Update your `.env` file with the proper database credentials and other settings.

4. **Key Generation:**
   ```bash
   php artisan key:generate
   ```

5. **Database Migration & Seeding:**
   Run the migrations and seed the database with sample data:
   ```bash
   php artisan migrate --seed
   ```

6. **Install Laravel Passport:**
   ```bash
   php artisan passport:install
   ```

## API Documentation

### Authentication Endpoints

#### Register
- **URL:** `/api/register`
- **Method:** `POST`
- **Payload:**
  ```json
  {
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "password": "secret123"
  }
  ```
- **Response:**
  ```json
  {
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john.doe@example.com",
      "created_at": "2025-03-06T12:00:00.000000Z",
      "updated_at": "2025-03-06T12:00:00.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOi..."
  }
  ```

#### Login
- **URL:** `/api/login`
- **Method:** `POST`
- **Payload:**
  ```json
  {
    "email": "john.doe@example.com",
    "password": "secret123"
  }
  ```
- **Response:**
  ```json
  {
    "user": { /* user details */ },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOi..."
  }
  ```

#### Logout
- **URL:** `/api/logout`
- **Method:** `POST`
- **Headers:** `Authorization: Bearer {token}`
- **Response:**
  ```json
  {
    "message": "Logged out successfully"
  }
  ```

### User Endpoints
- **List Users:** `GET /api/users`
- **Get User:** `GET /api/users/{id}`

### Project Endpoints
- **List Projects:** `GET /api/projects`
- **Filtering Example:**
  ```http
  GET /api/projects?filters[name]=ProjectA&filters[department][operator]==&filters[department][value]=IT
  ```
- **Get Project:** `GET /api/projects/{id}`
- **Create Project:** `POST /api/projects`
  - **Payload Example:**
    ```json
    {
      "name": "Project A",
      "status": "active",
      "attributes": {
        "department": "IT",
        "start_date": "2025-03-01",
        "end_date": "2025-06-01"
      }
    }
    ```
- **Update Project:** `PUT /api/projects/{id}`
- **Delete Project:** `DELETE /api/projects/{id}`

### Timesheet Endpoints
- **List Timesheets:** `GET /api/timesheets`
- **Get Timesheet:** `GET /api/timesheets/{id}`
- **Create Timesheet:** `POST /api/timesheets`
  - **Payload Example:**
    ```json
    {
      "project_id": 1,
      "task_name": "Development Task",
      "date": "2025-03-05",
      "hours": 5
    }
    ```
- **Update Timesheet:** `PUT /api/timesheets/{id}`
- **Delete Timesheet:** `DELETE /api/timesheets/{id}`

### Attribute Endpoints
- **List Attributes:** `GET /api/attributes`
- **Get Attribute:** `GET /api/attributes/{id}`
- **Create Attribute:** `POST /api/attributes`
  - **Payload Example:**
    ```json
    {
      "name": "priority",
      "type": "select"
    }
    ```
- **Update Attribute:** `PUT /api/attributes/{id}`
- **Delete Attribute:** `DELETE /api/attributes/{id}`

## Example Requests/Responses

### Example: Filtering Projects by Static and Dynamic Attributes
- **Request:**
  ```http
  GET /api/projects?filters[name]=ProjectA&filters[department][operator]==&filters[department][value]=IT
  ```
- **Response:**
  ```json
  [
    {
      "id": 2,
      "name": "ProjectA",
      "status": "active",
      "created_at": "2025-03-06T12:30:00.000000Z",
      "updated_at": "2025-03-06T12:30:00.000000Z",
      "attributeValues": [
        {
          "id": 5,
          "attribute_id": 1,
          "entity_id": 2,
          "value": "IT",
          "created_at": "2025-03-06T12:30:00.000000Z",
          "updated_at": "2025-03-06T12:30:00.000000Z",
          "attribute": {
            "id": 1,
            "name": "department",
            "type": "text",
            "created_at": "2025-03-06T12:00:00.000000Z",
            "updated_at": "2025-03-06T12:00:00.000000Z"
          }
        }
      ]
    }
  ]
  ```

## Test Credentials

For quick testing, you can use the credentials generated by the seeders. One sample test user is:

- **Email:** `john.doe@example.com`
- **Password:** `password`

Note: If you have multiple users created via seeding, check your database or create your own using the `/api/register` endpoint.


## Developer notes

Although the task seemed simple at first glance, it served as a valuable refresher with intricate details. I focused on delivering the task effectively and aimed to keep the solution straightforward while meeting the required objectives.