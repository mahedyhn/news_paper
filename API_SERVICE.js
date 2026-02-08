import axios from 'axios';

// Set up API client with base URL
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost/api';

const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json'
    }
});

// Add token to request headers if it exists
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('api_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Handle response errors
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Token expired or invalid - logout user
            localStorage.removeItem('api_token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// Authentication Services
export const authService = {
    // Register new user
    register: async (name, email, password, password_confirmation) => {
        try {
            const response = await apiClient.post('/auth/register', {
                name,
                email,
                password,
                password_confirmation
            });

            if (response.data.success) {
                localStorage.setItem('api_token', response.data.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.data.user));
            }

            return response.data;
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Registration failed',
                errors: error.response?.data?.errors
            };
        }
    },

    // Login user
    login: async (email, password) => {
        try {
            const response = await apiClient.post('/auth/login', {
                email,
                password
            });

            if (response.data.success) {
                localStorage.setItem('api_token', response.data.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.data.user));
            }

            return response.data;
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Login failed'
            };
        }
    },

    // Logout user
    logout: async () => {
        try {
            await apiClient.post('/auth/logout');
            localStorage.removeItem('api_token');
            localStorage.removeItem('user');
            return { success: true };
        } catch (error) {
            return {
                success: false,
                message: 'Logout failed'
            };
        }
    },

    // Get current user info
    getCurrentUser: async () => {
        try {
            const response = await apiClient.get('/auth/me');
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to fetch user'
            };
        }
    },

    // Refresh token
    refreshToken: async () => {
        try {
            const response = await apiClient.post('/auth/refresh-token');

            if (response.data.success) {
                localStorage.setItem('api_token', response.data.data.token);
            }

            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to refresh token'
            };
        }
    },

    // Check if user is authenticated
    isAuthenticated: () => {
        return !!localStorage.getItem('api_token');
    },

    // Get stored user data
    getStoredUser: () => {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }
};

// News Services
export const newsService = {
    // Get all news
    getAllNews: async (page = 1) => {
        try {
            const response = await apiClient.get('/news', {
                params: { page }
            });
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to fetch news'
            };
        }
    },

    // Get single news
    getNews: async (id) => {
        try {
            const response = await apiClient.get(`/news/${id}`);
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'News not found'
            };
        }
    },

    // Get news by category
    getNewsByCategory: async (categoryId) => {
        try {
            const response = await apiClient.get(`/news/category/${categoryId}`);
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to fetch news'
            };
        }
    },

    // Create news (requires authentication)
    createNews: async (formData) => {
        try {
            const response = await apiClient.post('/news', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || 'Failed to create news',
                errors: error.response?.data?.errors
            };
        }
    },

    // Update news (requires authentication)
    updateNews: async (id, data) => {
        try {
            const response = await apiClient.put(`/news/${id}`, data);
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to update news',
                errors: error.response?.data?.errors
            };
        }
    },

    // Delete news (requires authentication)
    deleteNews: async (id) => {
        try {
            const response = await apiClient.delete(`/news/${id}`);
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to delete news'
            };
        }
    }
};

// Category Services
export const categoryService = {
    // Get all categories
    getAllCategories: async () => {
        try {
            const response = await apiClient.get('/categories');
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to fetch categories'
            };
        }
    },

    // Get single category
    getCategory: async (id) => {
        try {
            const response = await apiClient.get(`/categories/${id}`);
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Category not found'
            };
        }
    },

    // Create category (requires authentication)
    createCategory: async (name, desc) => {
        try {
            const response = await apiClient.post('/categories', {
                name,
                desc
            });
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to create category',
                errors: error.response?.data?.errors
            };
        }
    },

    // Update category (requires authentication)
    updateCategory: async (id, name, desc) => {
        try {
            const response = await apiClient.put(`/categories/${id}`, {
                name,
                desc
            });
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to update category'
            };
        }
    },

    // Delete category (requires authentication)
    deleteCategory: async (id) => {
        try {
            const response = await apiClient.delete(`/categories/${id}`);
            return response.data;
        } catch (error) {
            return {
                success: false,
                message: 'Failed to delete category'
            };
        }
    }
};

export default apiClient;
