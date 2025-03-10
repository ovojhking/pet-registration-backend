# Project Overview

This project serves as the backend for [Pet Registration Frontend](https://github.com/ovojhking/pet-registration-frontend)

It will be executed on **localhost:8000** after starting.

## Purpose

The main goal is to simulate a pet registration system for a pet website.

## Pet Registration Form Rules

- The form must allow users to enter the pet's **name, category, breed, age, and gender**.
- If the **category** is "dog," the system must determine whether the pet is **dangerous**.
  - A pet is considered dangerous if its **breed** is either **Pitbull** or **Mastiff**.
- If the user selects **"mix"** as the breed, they must be able to enter a custom name for the mixed breed.
- If the **date of birth** is unknown, the user must be able to enter an **estimated age** instead.

## Prerequisistes

- Docker


## How To Run Me

Follow the steps below to set up and run the backend project.

### Step 1: Navigate to the `docker` Directory

Open a terminal and run the following command to navigate to the `docker` directory and Start the Docker Containers

```bash=
    cd docker
    docker-compose up --build -d
```

### Step 2: Access the pet_backend_app Container

```bash=
    docker exec -it pet_backend_app sh
```

### Step 3: Execute Laravel Commands

```bash=
    cp .env.example .env
    php artisan key:generate
    php artisan migrate:fresh --force --seed
```

### 


## Technical Features

This project adopts the **RESTful API** architecture to ensure scalability and maintainability while following industry best practices.  
Additionally, the system includes a robust API request validation mechanism that intercepts invalid requests in real time. For example:  

- If a user does not provide a valid date of birth, the system will automatically prompt them to enter an **estimated age**, ensuring data integrity and consistency.  
- With efficient request validation and error-handling mechanisms, the system enhances stability and security, delivering a seamless experience for users.  

## Schema

### pet_types 
| Column Name  | Data Type  | Length | Nullable | Description                  |
|--------------|------------|--------|----------|------------------------------|
| `id`         | `BIGINT`   |        | `NO`     | Primary key, unique identifier|
| `name`       | `VARCHAR`  | 255    | `NO`     | Pet type name, unique         |
| `created_at` | `TIMESTAMP`|        | `NO`     | Creation timestamp            |
| `updated_at` | `TIMESTAMP`|        | `YES`    | Last update timestamp         |


### breeds
| Column Name  | Data Type  | Length | Nullable | Description                         |
|--------------|------------|--------|----------|-------------------------------------|
| `id`         | `BIGINT`   |        | `NO`     | Primary key, unique identifier      |
| `pet_type_id`| `BIGINT`   |        | `NO`     | Foreign key referencing `pet_types` |
| `name`       | `VARCHAR`  | 255    | `NO`     | Breed name, unique                  |
| `created_at` | `TIMESTAMP`|        | `NO`     | Creation timestamp                  |
| `updated_at` | `TIMESTAMP`|        | `YES`    | Last update timestamp               |

### pets
| Column Name      | Data Type   | Length | Nullable | Default Value        | Description                         |
|------------------|-------------|--------|----------|----------------------|-------------------------------------|
| `id`             | `BIGINT`    |        | `NO`     | `AUTO_INCREMENT`     | Primary key, unique identifier      |
| `pet_type_id`    | `BIGINT`    |        | `NO`     |                      | Foreign key referencing `pet_types` |
| `breed_id`       | `BIGINT`    |        | `YES`    |                      | Foreign key referencing `breeds`    |
| `name`           | `VARCHAR`   | 255    | `NO`     |                      | Pet name                            |
| `date_of_birth`  | `DATE`      |        | `YES`    |                      | Pet date of birth                   |
| `approximate_age`| `INT`       |        | `YES`    |                      | Estimated age                       |
| `gender`         | `ENUM`      |        | `NO`     |                      | Pet gender (`male`, `female`)       |
| `is_age_estimated`| `BOOLEAN`  |        | `NO`     | `false`              | Whether the age is estimated        |
| `is_dangerous`   | `BOOLEAN`   |        | `NO`     | `false`              | Whether the pet is a dangerous breed|
| `is_mix`         | `BOOLEAN`   |        | `NO`     | `false`              | Whether the pet is a mix breed      |
| `custom_breed`   | `VARCHAR`   | 255    | `YES`    |                      | Custom breed name                   |
| `is_unknown`     | `BOOLEAN`   |        | `YES`    | `false`              | Whether the breed is unknown        |
| `created_at`     | `TIMESTAMP` |        | `NO`     | `CURRENT_TIMESTAMP`  | Creation timestamp                   |
| `updated_at`     | `TIMESTAMP` |        | `YES`    | `CURRENT_TIMESTAMP`  | Last update timestamp                |