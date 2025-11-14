# 📌 Laravel 12 Task Management System
A fully secure and optimized **Task Management API + Web Application** built with **Laravel 12**, **Sanctum authentication**, **Bootstrap UI**, **caching**, **pagination**, **clean architecture**, and **RESTful API best practices**.

---

## 🚀 Features

### 🔐 Authentication (API)
- Register User  
- Login & Token Generation (Sanctum Personal Access Tokens)  
- Logout  
- Profile (Authenticated User)  
- Fully validated and secure  

### 🗂 Task Management (API + Web)
- Create, Read, Update, Delete Tasks  
- Task Ownership (User can access only their tasks)  
- Pagination + Query Optimization  
- API Resource transformers  
- Cache-based listing (auto invalidates on create/update/delete)  

### 🎨 Web UI (Bootstrap 5)
- Dashboard listing tasks  
- Create Task form  
- Edit Task form  
- Delete with confirmation  
- Beautiful Bootstrap UI  
- Pagination (Bootstrap styled)  

### 🛡 Security
- Sanctum Token Auth  
- Input validation everywhere  
- Error handling with JSON structure  
- Throttling for brute force  
- Fetch-only-required columns  
- Clean architecture (Controller → Request → Service → Model)  

---

## 📁 Project Structure

app/
├── Http/
│ ├── Controllers/Api
│ ├── Controllers/Web
│ ├── Requests
│ ├── Resources
├── Models/
├── Services/
routes/
├── api.php
├── web.php
resources/
├── views/tasks (Bootstrap Blade UI)

yaml
Copy code

---

## 🧰 Requirements

- PHP 8.2+
- Composer 2+
- MySQL 8+
- Laravel 12
- Node.js (optional for UI assets)
- Postman (for API testing)

---

## ⚙️ Installation & Setup

# 1. Clone Repository
    git clone https://github.com/your-username/your-repo.git
    cd your-repo

# 2. Install Dependencies
    composer install

# 3. Create .env
    cp .env.example .env

# 4. Generate Application Key
    php artisan key:generate

# 5️. Configure Database

# 6️. Run Migrations

    php artisan migrate

# 7️. Start Server

    php artisan serve
    
## Sanctum Authentication (API)

# 1. Register
   
    POST /api/auth/register
    Body:

    json

    {
        "name": "John Doe",
        "email": "john@gmail.com",
        "password": "password"
    }

# 2. Login

    POST /api/auth/login

    Response:
    {
        "token": "xxxxx",
        "user": { }
    }

    Add header:
        Authorization: Bearer <token>


##    Task API Endpoints

    Method	Endpoint	Description
    
1)    GET	/api/tasks	List Tasks (cached + paginated)
2)    POST	/api/tasks	Create Task
3)    GET	/api/tasks/{id}	Show Task
4)    PUT	/api/tasks/{id}	Update Task
5)    DELETE	/api/tasks/{id}	Delete Task

## Web Routes (Blade UI)
    Route	Description
    
1)    GET /tasks	Task Dashboard
2)    GET /tasks/create	Create Form
3)    GET /tasks/{id}/edit	Edit Form
4)    POST /tasks	Store Task
5)    PUT /tasks/{id}	Update Task
6)    DELETE /tasks/{id}	Delete Task

## Testing with Postman

    Authorization
    Add in Postman:

    Header:
    Authorization: Bearer <token>
    Accept: application/json
    
# Postman collection recommended:

    Register

    Login

    Logout

    Create Task

    Update Task

    Delete Task

    Get All Tasks

# Caching Strategy
Action	Cache Behavior
GET /tasks	Cache tasks for 60 seconds
POST /tasks	Clear cache
PUT /tasks	Clear cache
DELETE /tasks	Clear cache

# Error Handling Format (Standardized)
Example error response:

# json

    {
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
    }

#  Pagination Example

    GET /api/tasks?page=1
    
    Response:
        json
        {
            "data": [],
            "meta": {
                "current_page": 1,
                "total": 20
            }
        }

## Summary
    This README is ready for GitHub and explains your Laravel 12
    API + Web Application with:

    Sanctum authentication

    Task CRUD

    Web Blade UI (Bootstrap)

    API endpoints

    Pagination

    Caching

    Secure validation

    Error formatting

