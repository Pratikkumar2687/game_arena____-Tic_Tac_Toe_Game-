// resources/js/services/api.js
import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

export const gameApi = {
    getGames: () => api.get('/games'),
};

export const matchApi = {
    getMatches: () => api.get('/matches'),
    createMatch: (gameId, opponentId) => api.post('/matches', {
        game_id: gameId,
        opponent_id: opponentId,
    }),
    getMatch: (matchId) => api.get(`/matches/${matchId}`),
    makeMove: (matchId, position) => api.post(`/matches/${matchId}/moves`, {
        position,
    }),
};

export default api;