import { useState, useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { gameApi, matchApi } from '@/services/api';
import { useUsers } from '@/hooks/useUsers';

export default function Lobby({ auth }) {
    const [games, setGames] = useState([]);
    const [matches, setMatches] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedGame, setSelectedGame] = useState(null);
    const [selectedOpponent, setSelectedOpponent] = useState('');
    const [creating, setCreating] = useState(false);
    const [error, setError] = useState(null);
    const { users } = useUsers();

    useEffect(() => {
        fetchData();

        window.Echo.channel(`user.${auth.user.id}`)
            .listen('MatchCreated', (e) => {
                setMatches(prev => [e.match, ...prev]);
            });

        return () => {
            window.Echo.leave(`user.${auth.user.id}`);
        };
    }, [auth.user.id]);

    const fetchData = async () => {
        try {
            const [gamesRes, matchesRes] = await Promise.all([
                gameApi.getGames(),
                matchApi.getMatches(),
            ]);
            setGames(gamesRes.data);
            setMatches(matchesRes.data);
        } catch (err) {
            setError('Failed to load data');
        } finally {
            setLoading(false);
        }
    };

    const handleCreateMatch = async (e) => {
        e.preventDefault();
        if (!selectedGame || !selectedOpponent) return;

        setCreating(true);
        setError(null);

        try {
            const response = await matchApi.createMatch(selectedGame, selectedOpponent);
            setMatches(prev => [response.data, ...prev]);
            setSelectedGame(null);
            setSelectedOpponent('');
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to create match');
        } finally {
            setCreating(false);
        }
    };

    const getOpponent = (match) => {
        return match.player_one_id === auth.user.id
            ? match.player_two
            : match.player_one;
    };

    const getMatchStatus = (match) => {
        if (match.status === 'completed') {
            if (!match.winner_user_id) return 'Draw';
            return match.winner_user_id === auth.user.id ? 'Won' : 'Lost';
        }
        if (match.status === 'abandoned') return 'Abandoned';
        if (match.current_turn_user_id === auth.user.id) return 'Your Turn';
        return 'Waiting';
    };

    const getStatusColor = (match) => {
        const status = getMatchStatus(match);
        if (status === 'Your Turn') return 'text-green-600 font-semibold';
        if (status === 'Won') return 'text-blue-600';
        if (status === 'Lost') return 'text-red-600';
        if (status === 'Draw') return 'text-gray-600';
        return 'text-yellow-600';
    };

    if (loading) {
        return (
            <AuthenticatedLayout user={auth.user}>
                <div className="p-6">Loading...</div>
            </AuthenticatedLayout>
        );
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Lobby" />

            <div className="max-w-4xl mx-auto p-6 space-y-8">

                {/* Header */}
                <div>
                    <h1 className="text-3xl font-bold">Game Arena</h1>
                    <p className="text-gray-600">Challenge your friends to a game!</p>
                </div>

                {/* Error Message */}
                {error && (
                    <div className="p-3 bg-red-100 text-red-700 rounded">
                        {error}
                    </div>
                )}

                {/* Available Games */}
                <div>
                    <h2 className="text-xl font-semibold mb-3">Available Games</h2>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {games.map(game => (
                            <div
                                key={game.id}
                                className={`p-4 border-2 rounded-lg cursor-pointer transition ${selectedGame === game.id
                                    ? 'border-blue-500 bg-blue-50'
                                    : 'border-gray-200 hover:border-gray-300'
                                    }`}
                                onClick={() => setSelectedGame(game.id)}
                            >
                                {game.name}
                            </div>
                        ))}
                    </div>
                </div>

                {/* Create Match Form */}
                {selectedGame && (
                    <form onSubmit={handleCreateMatch} className="space-y-4 p-4 border rounded-lg bg-white shadow">
                        <h3 className="font-semibold">Select Opponent</h3>

                        <select
                            value={selectedOpponent}
                            onChange={(e) => setSelectedOpponent(e.target.value)}
                            className="w-full border-gray-300 rounded-md shadow-sm"
                            required
                        >
                            <option value="">Choose a player...</option>
                            {users
                                .filter(user => user.id !== auth.user.id)
                                .map(user => (
                                    <option key={user.id} value={user.id}>
                                        {user.name}
                                    </option>
                                ))}
                        </select>

                        <button
                            type="submit"
                            disabled={creating}
                            className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            {creating ? 'Creating...' : 'Start Match'}
                        </button>
                    </form>
                )}

                {/* Your Matches */}
                <div>
                    <h2 className="text-xl font-semibold mb-3">Your Matches</h2>

                    {matches.length === 0 ? (
                        <p className="text-gray-600">No matches yet. Start a new game!</p>
                    ) : (
                        <div className="space-y-3">
                            {matches.map(match => (
                                <Link
                                    key={match.id}
                                    href={`/matches/${match.id}`}
                                    className="block p-4 border rounded-lg hover:bg-gray-50"
                                >
                                    <div className="flex justify-between">
                                        <div>
                                            <div className="font-semibold">{match.game.name}</div>
                                            <div className="text-gray-600">
                                                vs {getOpponent(match).name}
                                            </div>
                                        </div>

                                        <div className="text-right">
                                            <div className={getStatusColor(match)}>
                                                {getMatchStatus(match)}
                                            </div>
                                            <div className="text-sm text-gray-500">
                                                {new Date(match.created_at).toLocaleDateString()}
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}