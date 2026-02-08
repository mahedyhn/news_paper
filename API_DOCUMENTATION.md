# News Paper API Documentation

This is a comprehensive REST API for managing newspapers and categories.

## Base URL
```
http://localhost:8000/api
```

## Authentication
The API uses Sanctum token-based authentication for protected routes. Include the token in the Authorization header as a Bearer token:

```
Authorization: Bearer your_token_here
```

---

## Public Endpoints

### 1. Get All News
**GET** `/api/news`
- Returns paginated list of all newspapers (15 per page)
- No authentication required

**Response Example:**
```json
{
  "success": true,
  "message": "Newspapers retrieved successfully",
  "data": {
    "data": [
      {
        "id": 1,
        "title": "Breaking News",
        "description": "Article content...",
        "author": "John Doe",
        "image": "news-images/news-image1234567890.jpg",
        "category_id": 1,
        "user_id": 1,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z",
        "category": {
          "id": 1,
          "name": "Sports",
          "desc": "Sports news"
        },
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        }
      }
    ],
    "links": {...},
    "meta": {...}
  }
}
```

### 2. Get Single Newspaper
**GET** `/api/news/{id}`
- Returns a specific newspaper by ID
- No authentication required

**Response Example:**
```json
{
  "success": true,
  "message": "Newspaper retrieved successfully",
  "data": {
    "id": 1,
    "title": "Breaking News",
    "description": "Article content...",
    "author": "John Doe",
    "image": "news-images/news-image1234567890.jpg",
    "category": {...},
    "user": {...}
  }
}
```

### 3. Get News by Category
**GET** `/api/news/category/{categoryId}`
- Returns all newspapers for a specific category
- No authentication required

**Response:**
- Same format as "Get All News"

### 4. Get All Categories
**GET** `/api/categories`
- Returns paginated list of all categories
- No authentication required

**Response Example:**
```json
{
  "success": true,
  "message": "Categories retrieved successfully",
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Sports",
        "desc": "Sports news",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
      }
    ],
    "links": {...},
    "meta": {...}
  }
}
```

### 5. Get Single Category
**GET** `/api/categories/{id}`
- Returns a specific category with all its newspapers
- No authentication required

**Response Example:**
```json
{
  "success": true,
  "message": "Category retrieved successfully",
  "data": {
    "id": 1,
    "name": "Sports",
    "desc": "Sports news",
    "newspapers": [...]
  }
}
```

---

## Protected Endpoints (Requires Authentication)

### 6. Create News Article
**POST** `/api/news`
- Requires authentication (Bearer token)
- Creates a new newspaper article

**Request Body:**
```json
{
  "title": "Article Title",
  "description": "Article description...",
  "category_id": 1,
  "image": "[file]"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| title | string | Yes | Maximum 255 characters |
| description | string | Yes | Article content |
| category_id | integer | Yes | Must exist in categories |
| image | file | No | Image file (jpeg, png, jpg, gif, max 2MB) |

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Newspaper created successfully",
  "data": {
    "id": 2,
    "title": "New Article",
    "description": "Content...",
    "author": "Current User",
    "category_id": 1,
    "image": "news-images/news-image1234567890.jpg",
    "created_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### 7. Update News Article
**PUT** `/api/news/{id}`
- Requires authentication
- Updates an existing newspaper article

**Request Body:**
```json
{
  "title": "Updated Title",
  "description": "Updated description",
  "category_id": 2,
  "image": "[file]"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| title | string | No | Maximum 255 characters |
| description | string | No | Article content |
| category_id | integer | No | Must exist in categories |
| image | file | No | Image file (replaces old image) |

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Newspaper updated successfully",
  "data": {...}
}
```

### 8. Delete News Article
**DELETE** `/api/news/{id}`
- Requires authentication
- Deletes a newspaper article and its image

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Newspaper deleted successfully"
}
```

### 9. Create Category
**POST** `/api/categories`
- Requires authentication
- Creates a new category

**Request Body:**
```json
{
  "name": "Technology",
  "desc": "Technology news and updates"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| name | string | Yes | Unique, max 255 characters |
| desc | string | No | Category description |

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Category created successfully",
  "data": {
    "id": 2,
    "name": "Technology",
    "desc": "Technology news and updates",
    "created_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### 10. Update Category
**PUT** `/api/categories/{id}`
- Requires authentication
- Updates an existing category

**Request Body:**
```json
{
  "name": "Tech News",
  "desc": "Updated description"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Category updated successfully",
  "data": {...}
}
```

### 11. Delete Category
**DELETE** `/api/categories/{id}`
- Requires authentication
- Deletes a category (only if it has no newspapers)

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Category deleted successfully"
}
```

**Error Response (409 Conflict):**
```json
{
  "success": false,
  "message": "Cannot delete category with associated newspapers"
}
```

### 12. Get Current User
**GET** `/api/user`
- Requires authentication
- Returns the authenticated user's information

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "email_verified_at": null,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Newspaper not found"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Failed to retrieve newspapers",
  "error": "Error message details"
}
```

---

## Usage Examples

### Using cURL

**Get all news:**
```bash
curl -X GET http://localhost:8000/api/news
```

**Create a news article (with authentication):**
```bash
curl -X POST http://localhost:8000/api/news \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token_here" \
  -d '{
    "title": "My Article",
    "description": "Article content",
    "category_id": 1
  }'
```

**Upload image with article:**
```bash
curl -X POST http://localhost:8000/api/news \
  -H "Authorization: Bearer your_token_here" \
  -F "title=My Article" \
  -F "description=Article content" \
  -F "category_id=1" \
  -F "image=@/path/to/image.jpg"
```

### Using JavaScript/Fetch API

**Get all news:**
```javascript
fetch('http://localhost:8000/api/news')
  .then(response => response.json())
  .then(data => console.log(data));
```

**Create article with authentication:**
```javascript
fetch('http://localhost:8000/api/news', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer your_token_here'
  },
  body: JSON.stringify({
    title: 'My Article',
    description: 'Article content',
    category_id: 1
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## Notes

- All timestamps are returned in UTC/ISO 8601 format
- Pagination uses the default 15 items per page
- Images are automatically managed (old images are deleted when replaced)
- The `author` field is automatically set to the current user's name
- Password field is hidden from all responses for security
