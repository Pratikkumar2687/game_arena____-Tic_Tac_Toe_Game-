// resources/js/hooks/useUsers.js
import { useState, useEffect } from 'react';
import axios from 'axios';

export const useUsers = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchUsers = async () => {
            try {
                // You'll need to create this endpoint
                const response = await axios.get('/api/users');
                setUsers(response.data);
            } catch (error) {
                console.error('Failed to fetch users:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchUsers();
    }, []);

    return { users, loading };
};