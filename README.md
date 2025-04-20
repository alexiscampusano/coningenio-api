# ConIngenio API Documentation

## Overview

ConIngenio API is a basic RESTful API built in PHP without frameworks. It provides endpoints to manage services and company "About Us" information. It also includes a frontend to display this information.

## System Requirements

- **Docker** (version 20.10.0 or higher)
- **Docker Compose** (version 2.0.0 or higher)
- Postman (or similar) to test API endpoints
- Modern web browser to access the frontend

### Installing Docker and Docker Compose

**For Linux (Ubuntu/Debian):**
```bash
# Install Docker
sudo apt update
sudo apt install docker.io

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.18.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

**For Windows/Mac:**
Download and install Docker Desktop from [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)

## Setup and Deployment

### 1. Clone the repository

```bash
git clone https://github.com/alexiscampusano/coningenio-api
cd coningenio-api
```

### 2. Start the containers

```bash
docker-compose up -d
```

### 3. Populate the database

```bash
docker exec -it coningenio-app php /var/www/html/sync.php
```

### 4. Access the frontend

```
http://localhost:8080/web/
```

## Project Structure

```
coningenio-api-full/
├── app/                # Backend code (API)
│   ├── Commands/       # Commands to synchronize data
│   ├── Controllers/    # API controllers
│   ├── Models/         # Data models
│   ├── Repositories/   # Database access
│   ├── Services/       # Business logic
│   └── Utils/          # Utilities
├── config/             # Project configuration
├── database/           # Database migrations
│   └── migrations/
├── public/             # API entry point
│   └── .htaccess       # Redirection rules
├── routes/             # API route definitions
├── web/                # Frontend
│   ├── css/            # Styles
│   ├── js/             # JavaScript
│   └── index.html      # Main page
├── 000-default.conf    # Apache configuration
├── docker-compose.yml  # Docker configuration
└── sync.php            # Data synchronization script
```

## API Routes for Postman

### Services

#### 1. Get all services

- **Method**: GET
- **URL**: `http://localhost:8080/api/v1/services`
- **Description**: Returns a list of all available services.
- **Response example**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "external_id": "1",
        "name": "Digital Consulting",
        "description": "We identify gaps and connect the dots between your business and digital strategy.",
        "created_at": "2025-04-20 12:34:56",
        "updated_at": "2025-04-20 12:34:56"
      },
      // more services...
    ]
  }
  ```

#### 2. Get a specific service

- **Method**: GET
- **URL**: `http://localhost:8080/api/v1/services/{id}`
- **Description**: Returns a specific service by its ID.
- **Example**:
  - Request: `GET http://localhost:8080/api/v1/services/1`
  - Response:
    ```json
    {
      "data": {
        "id": 1,
        "external_id": "1",
        "name": "Digital Consulting",
        "description": "We identify gaps and connect the dots between your business and digital strategy.",
        "created_at": "2025-04-20 12:34:56",
        "updated_at": "2025-04-20 12:34:56"
      }
    }
    ```

#### 3. Create a new service

- **Method**: POST
- **URL**: `http://localhost:8080/api/v1/services`
- **Headers**: `Content-Type: application/json`
- **Body**:
  ```json
  {
    "name": "New Service",
    "description": "Description of the new service"
  }
  ```
- **Response example**:
  ```json
  {
    "data": {
      "id": 5,
      "message": "Service created successfully"
    }
  }
  ```
- **Note**: Manually created services will have `external_id: null`

#### 4. Update an existing service

- **Method**: PUT
- **URL**: `http://localhost:8080/api/v1/services/{id}`
- **Headers**: `Content-Type: application/json`
- **Body**:
  ```json
  {
    "name": "Updated Service",
    "description": "New service description"
  }
  ```
- **Example**:
  - Request: `PUT http://localhost:8080/api/v1/services/1`
  - Response:
    ```json
    {
      "data": {
        "message": "Service updated successfully"
      }
    }
    ```

#### 5. Delete a service

- **Method**: DELETE
- **URL**: `http://localhost:8080/api/v1/services/{id}`
- **Description**: Deletes a service by its ID.
- **Example**:
  - Request: `DELETE http://localhost:8080/api/v1/services/5`
  - Response:
    ```json
    {
      "data": {
        "message": "Service deleted successfully"
      }
    }
    ```

### About Us

#### 1. Get all "About Us" information

- **Method**: GET
- **URL**: `http://localhost:8080/api/v1/about-us`
- **Description**: Returns all "About Us" information.
- **Response example**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "title": "Highly customized IT support, management and design services.",
        "description": "Accelerate innovation with world-class tech teams...",
        "type": "general",
        "created_at": "2025-04-20 12:34:56",
        "updated_at": "2025-04-20 12:34:56"
      },
      {
        "id": 2,
        "title": "Mission",
        "description": "Our mission is to offer innovative digital solutions...",
        "type": "mission",
        "created_at": "2025-04-20 12:34:56",
        "updated_at": "2025-04-20 12:34:56"
      },
      // more sections...
    ]
  }
  ```

#### 2. Get information by ID

- **Method**: GET
- **URL**: `http://localhost:8080/api/v1/about-us/{id}`
- **Description**: Returns a specific section by its ID.
- **Example**:
  - Request: `GET http://localhost:8080/api/v1/about-us/2`
  - Response:
    ```json
    {
      "data": {
        "id": 2,
        "title": "Mission",
        "description": "Our mission is to offer innovative digital solutions...",
        "type": "mission",
        "created_at": "2025-04-20 12:34:56",
        "updated_at": "2025-04-20 12:34:56"
      }
    }
    ```

#### 3. Get information by type

- **Method**: GET
- **URL**: `http://localhost:8080/api/v1/about-us?type={type}`
- **Description**: Returns specific sections by type (general, mission, vision).
- **Example**:
  - Request: `GET http://localhost:8080/api/v1/about-us?type=mission`
  - Response:
    ```json
    {
      "data": [
        {
          "id": 2,
          "title": "Mission",
          "description": "Our mission is to offer innovative digital solutions...",
          "type": "mission",
          "created_at": "2025-04-20 12:34:56",
          "updated_at": "2025-04-20 12:34:56"
        }
      ]
    }
    ```

#### 4. Create new section

- **Method**: POST
- **URL**: `http://localhost:8080/api/v1/about-us`
- **Headers**: `Content-Type: application/json`
- **Body**:
  ```json
  {
    "title": "New Section",
    "description": "Description of the new section",
    "type": "values"
  }
  ```
- **Response example**:
  ```json
  {
    "data": {
      "id": 4,
      "message": "Item created successfully"
    }
  }
  ```

#### 5. Update existing section

- **Method**: PUT
- **URL**: `http://localhost:8080/api/v1/about-us/{id}`
- **Headers**: `Content-Type: application/json`
- **Body**:
  ```json
  {
    "title": "Updated Section",
    "description": "New section description",
    "type": "values"
  }
  ```
- **Example**:
  - Request: `PUT http://localhost:8080/api/v1/about-us/4`
  - Response:
    ```json
    {
      "data": {
        "message": "Item updated successfully"
      }
    }
    ```

#### 6. Delete section

- **Method**: DELETE
- **URL**: `http://localhost:8080/api/v1/about-us/{id}`
- **Description**: Deletes a section by its ID.
- **Example**:
  - Request: `DELETE http://localhost:8080/api/v1/about-us/4`
  - Response:
    ```json
    {
      "data": {
        "message": "Item deleted successfully"
      }
    }
    ```

## Postman Collection

To facilitate testing, you can import the following collection into Postman:

```
# Services
GET http://localhost:8080/api/v1/services
GET http://localhost:8080/api/v1/services/1
POST http://localhost:8080/api/v1/services
PUT http://localhost:8080/api/v1/services/1
DELETE http://localhost:8080/api/v1/services/5

# About Us
GET http://localhost:8080/api/v1/about-us
GET http://localhost:8080/api/v1/about-us/1
GET http://localhost:8080/api/v1/about-us?type=mission
POST http://localhost:8080/api/v1/about-us
PUT http://localhost:8080/api/v1/about-us/1
DELETE http://localhost:8080/api/v1/about-us/4
```

## Synchronization System

The API includes a synchronization system with external sources:

### Services

- Services are synchronized using an `external_id` field that allows:
  - Avoiding duplicates during synchronizations
  - Updating existing services instead of replacing them
  - Distinguishing between services managed by the external API and those created manually

### About Us

- "About Us" content is synchronized using the title as a unique identifier
- This allows updating existing content by detecting matching titles like "Mission" or "Vision"

To run the synchronization manually:

```bash
docker exec -it coningenio-app php /var/www/html/sync.php
```

## Frontend

The frontend is available at:
- URL: `http://localhost:8080/web/`

It contains the following sections:
1. **Home**: General company presentation
2. **Services**: List of offered services
3. **About Us**: Information about the company, mission, and vision
4. **Contact**: Contact form

## Troubleshooting

### 1. Error "could not find driver" in PDO
This error occurs when PHP PDO extensions are not installed. Solution:
```bash
docker-compose down
# Make sure docker-compose.yml contains the installation of pdo_mysql
docker-compose up -d
```

### 2. Error 400 Bad Request when accessing the API
Check the .htaccess file in the public folder:
```bash
cat public/.htaccess
```
Make sure it correctly redirects API requests to index.php.

### 3. No data displayed in the frontend
Run the synchronization script:
```bash
docker exec -it coningenio-app php /var/www/html/sync.php
```

### 4. Database inaccessible
Verify that MySQL is running:
```bash
docker exec -it coningenio-mysql mysql -u root -psecret123 -e "SHOW DATABASES;"
```

### 5. Docker permission problems
If you have issues running Docker commands:
```bash
# In Linux, add your user to the docker group
sudo usermod -aG docker $USER
# Then log out and log back in
```

## Useful Commands

```bash
# View Apache logs
docker logs coningenio-app

# Enter the container
docker exec -it coningenio-app bash

# Restart services
docker-compose restart

# Check database
docker exec -it coningenio-mysql mysql -u root -psecret123 -e "SELECT * FROM coningenio.services;"

# Stop all services when finished
docker-compose down

# Backup the database
docker exec coningenio-mysql mysqldump -u root -psecret123 coningenio > backup.sql

# Clear all data to reset
docker exec -it coningenio-mysql mysql -u root -psecret123 -e "USE coningenio; DELETE FROM services; DELETE FROM about_us;"
```

## Additional Notes

- This project is configured for development environments. For production, it is recommended to strengthen security.
- The external API used for initial data synchronization comes preconfigured and requires no additional setup.
- Data is updated via the sync.php script, which can be scheduled for periodic execution if desired.
- The synchronization service intelligently manages data to avoid duplicates.

---

## License

This project, ConIngenio, is licensed for educational purposes at Ipss under a proprietary license. All rights reserved by the developers.

Developed by [Alexis Campusano](https://github.com/alexiscampusano).