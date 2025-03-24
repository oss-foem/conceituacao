const authService = {
    apiUrl: 'http://localhost:8000/api',

    tokenKey: 'jwt_token',

    async login(email, password) {
        try {
            const response = await axios.post(`${this.apiUrl}/login`, {
                email: email,
                password: password
            }, {
                withCredentials: true
            }
        );

            if (response.data && response.data.token) {
                this.setToken(response.data.token);

                document.cookie = `jwt_token=${response.data.token}; path=/; max-age=86400`;

                return true;
            }

            return false;
        } catch (error) {
            console.error('Login error:', error.response?.data?.error || error.message);
            throw error;
        }
    },

    async logout() {
        try {
            await axios.post(`${this.apiUrl}/logout`, {}, {
                headers: this.getAuthHeader()
            });
        } catch (error) {
            console.error('Logout error:', error.response?.data?.error || error.message);
        } finally {
            this.removeToken();

            document.cookie = "jwt_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
    },

    isAuthenticated() {
        return !!this.getToken();
    },

    async getCurrentUser() {
        try {
            const response = await axios.get(`${this.apiUrl}/user`, {
                headers: this.getAuthHeader()
            });
            return response.data;
        } catch (error) {
            console.error('Get user error:', error.response?.data?.error || error.message);
            throw error;
        }
    },

    setToken(token) {
        localStorage.setItem(this.tokenKey, token);
    },

    getToken() {
        return localStorage.getItem(this.tokenKey);
    },

    removeToken() {
        localStorage.removeItem(this.tokenKey);
    },

    getAuthHeader() {
        const token = this.getToken();
        if (token) {
            return { Authorization: `Bearer ${token}` };
        }
        return {};
    },

    setupAxiosInterceptors() {
        axios.interceptors.request.use(config => {
            const token = this.getToken();
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        }, error => {
            return Promise.reject(error);
        });

        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response && error.response.status === 401) {
                    this.removeToken();
                    document.cookie = "jwt_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    window.location.href = '/login?expired=true';
                }
                return Promise.reject(error);
            }
        );
    }
};

document.addEventListener('DOMContentLoaded', () => {
    authService.setupAxiosInterceptors();
});
