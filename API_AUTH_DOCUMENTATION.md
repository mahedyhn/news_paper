# News Portal API Documentation

## Authentication Endpoints

### 1. Register User
**Endpoint:** `POST /api/auth/register`  
**Authentication:** Not required  
**Description:** Create a new user account and receive an API token

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (Success - 201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2026-02-08T10:30:00Z",
      "updated_at": "2026-02-08T10:30:00Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
    "token_type": "Bearer"
  }
}
```

**Response (Error - 422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

### 2. Login User
**Endpoint:** `POST /api/auth/login`  
**Authentication:** Not required  
**Description:** Login with email and password to receive an API token

**Request:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2026-02-08T10:30:00Z",
      "updated_at": "2026-02-08T10:30:00Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
    "token_type": "Bearer"
  }
}
```

**Response (Error - 401):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

### 3. Get Current User
**Endpoint:** `GET /api/auth/me`  
**Authentication:** Required (Bearer Token)  
**Description:** Get the currently authenticated user's information

**Request Header:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "User retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2026-02-08T10:30:00Z",
    "updated_at": "2026-02-08T10:30:00Z"
  }
}
```

---

### 4. Logout User
**Endpoint:** `POST /api/auth/logout`  
**Authentication:** Required (Bearer Token)  
**Description:** Logout and invalidate the current token

**Request Header:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

---

### 5. Refresh Token
**Endpoint:** `POST /api/auth/refresh-token`  
**Authentication:** Required (Bearer Token)  
**Description:** Generate a new API token while keeping the user session active

**Request Header:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "token": "2|newabcdefghijklmnopqrstuvwxyz1234567890",
    "token_type": "Bearer"
  }
}
```

---

## Public News Endpoints

### 1. Get All News
**Endpoint:** `GET /api/news`  
**Authentication:** Not required  
**Description:** Retrieve paginated list of all news articles

**Query Parameters:**
- `page` (optional): Page number (default: 1)

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Newspapers retrieved successfully",
  "data": {
    "data": [
      {
        "id": 1,
        "title": "Breaking News Title",
        "description": "Article content...",
        "author": "Author Name",
        "image": "news-images/filename.jpg",
        "category_id": 1,
        "created_at": "2026-02-08T10:30:00Z",
        "updated_at": "2026-02-08T10:30:00Z",
        "category": {
          "id": 1,
          "name": "Technology",
          "desc": "Tech news"
        },
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        }
      }
    ],
    "current_page": 1,
    "total": 50,
    "per_page": 15
  }
}
```

---

### 2. Get Single News Article
**Endpoint:** `GET /api/news/{id}`  
**Authentication:** Not required  
**Parameters:**
- `id` (required): News article ID

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Newspaper retrieved successfully",
  "data": {
    "id": 1,
    "title": "Breaking News Title",
    "description": "Full article content...",
    "author": "Author Name",
    "image": "news-images/filename.jpg",
    "category_id": 1,
    "created_at": "2026-02-08T10:30:00Z",
    "updated_at": "2026-02-08T10:30:00Z",
    "category": { ... },
    "user": { ... }
  }
}
```

---

### 3. Get News by Category
**Endpoint:** `GET /api/news/category/{categoryId}`  
**Authentication:** Not required  
**Parameters:**
- `categoryId` (required): Category ID

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Newspapers retrieved successfully",
  "data": [...]
}
```

---

## Public Category Endpoints

### 1. Get All Categories
**Endpoint:** `GET /api/categories`  
**Authentication:** Not required

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Technology",
      "desc": "Technology news",
      "created_at": "2026-02-08T10:30:00Z",
      "updated_at": "2026-02-08T10:30:00Z"
    }
  ]
}
```

---

### 2. Get Single Category with News
**Endpoint:** `GET /api/categories/{id}`  
**Authentication:** Not required

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Category retrieved successfully",
  "data": {
    "id": 1,
    "name": "Technology",
    "desc": "Technology news",
    "newspapers": [...]
  }
}
```

---

## Protected News Management Endpoints

### 1. Create News Article
**Endpoint:** `POST /api/news`  
**Authentication:** Required (Bearer Token)  
**Description:** Create a new news article

**Request:**
```
Form Data:
- title (required): string
- description (required): string
- category_id (required): integer (must exist in categories)
- image (optional): file (jpeg, png, jpg, gif - max 2MB)
```

**Response (Success - 201):**
```json
{
  "success": true,
  "message": "Newspaper created successfully",
  "data": {
    "id": 50,
    "title": "New Article",
    "description": "Content...",
    "author": "John Doe",
    "image": "news-images/filename.jpg",
    "category_id": 1,
    "user_id": 1
  }
}
```

---

### 2. Update News Article
**Endpoint:** `PUT /api/news/{id}`  
**Authentication:** Required (Bearer Token)  
**Parameters:**
- `id` (required): News article ID

**Request:**
```
JSON:
{
  "title": "Updated Title",
  "description": "Updated content",
  "category_id": 2
}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Newspaper updated successfully",
  "data": { ... }
}
```

---

### 3. Delete News Article
**Endpoint:** `DELETE /api/news/{id}`  
**Authentication:** Required (Bearer Token)

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Newspaper deleted successfully"
}
```

---

## Protected Category Management Endpoints

### 1. Create Category
**Endpoint:** `POST /api/categories`  
**Authentication:** Required (Bearer Token)

**Request:**
```json
{
  "name": "Business",
  "desc": "Business news"
}
```

**Response (Success - 201):**
```json
{
  "success": true,
  "message": "Category created successfully",
  "data": { ... }
}
```

---

## JavaScript/Fetch Examples

### Register User
```javascript
fetch('http://localhost/api/auth/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: 'John Doe',
    email: 'john@example.com',
    password: 'password123',
    password_confirmation: 'password123'
  })
})
.then(res => res.json())
.then(data => {
  console.log('Token:', data.data.token);
  localStorage.setItem('api_token', data.data.token);
});
```

### Login User
```javascript
fetch('http://localhost/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    email: 'john@example.com',
    password: 'password123'
  })
})
.then(res => res.json())
.then(data => {
  if (data.success) {
    localStorage.setItem('api_token', data.data.token);
    console.log('Logged in as:', data.data.user.name);
  }
});
```

### Get All News
```javascript
fetch('http://localhost/api/news')
  .then(res => res.json())
  .then(data => console.log(data.data));
```

### Create News (Authenticated)
```javascript
const token = localStorage.getItem('api_token');
const formData = new FormData();
formData.append('title', 'New Article');
formData.append('description', 'Article content...');
formData.append('category_id', 1);
formData.append('image', imageFile);

fetch('http://localhost/api/news', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`
  },
  body: formData
})
.then(res => res.json())
.then(data => console.log(data));
```

### Logout
```javascript
const token = localStorage.getItem('api_token');

fetch('http://localhost/api/auth/logout', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`
  }
})
.then(res => res.json())
.then(data => {
  localStorage.removeItem('api_token');
  console.log('Logged out');
});
```

---

## cURL Examples

### Register
```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Get All News
```bash
curl http://localhost/api/news
```

### Create News (with token)
```bash
curl -X POST http://localhost/api/news \
  -H "Authorization: Bearer TOKEN_HERE" \
  -F "title=New Article" \
  -F "description=Content" \
  -F "category_id=1" \
  -F "image=@/path/to/image.jpg"
```

### Logout
```bash
curl -X POST http://localhost/api/auth/logout \
  -H "Authorization: Bearer TOKEN_HERE"
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Newspaper not found"
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### 500 Server Error
```json
{
  "success": false,
  "message": "Failed to retrieve newspapers",
  "error": "Error message details"
}
```

---

## Token Usage

All protected endpoints require the `Authorization` header with the Bearer token:

```
Authorization: Bearer {token}
```

The token is returned in the login and register responses and should be stored securely (e.g., in localStorage for web apps, secure storage for mobile apps).

---

## CORS Configuration

If consuming this API from a different domain, ensure CORS is properly configured in `config/cors.php`.
