import { useState, useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TicTacToeBoard from '@/Components/TicTacToeBoard';
import { matchApi } from '@/services/api';

export default function MatchShow({ auth, matchId }) {
    const [match, setMatch] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [making, setMaking] = useState(false);

    useEffect(() => {
        fetchMatch();

        const channel = window.Echo.channel(`match.${matchId}`)
            .listen('MoveMade', (e) => setMatch(e.match))
            .listen('MatchCompleted', (e) => setMatch(e.match));

        return () => {
            channel.stopListening('MoveMade');
            channel.stopListening('MatchCompleted');
            window.Echo.leave(`match.${matchId}`);
        };
    }, [matchId]);

    const fetchMatch = async () => {
        try {
            const response = await matchApi.getMatch(matchId);
            setMatch(response.data);
        } catch (err) {
            setError('Failed to load match');
        } finally {
            setLoading(false);
        }
    };

    const handleMove = async (position) => {
        setMaking(true);
        setError(null);

        try {
            const response = await matchApi.makeMove(matchId, position);
            setMatch(response.data.match);
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to make move');
        } finally {
            setMaking(false);
        }
    };

    const getOpponent = () => {
        if (!match) return null;
        return match.player_one_id === auth.user.id
            ? match.player_two
            : match.player_one;
    };

    if (loading) {
        return (
            <AuthenticatedLayout user={auth.user}>
                <Head title="Match" />
                <div className="p-6 text-center text-lg">Loading match...</div>
            </AuthenticatedLayout>
        );
    }

    if (!match) {
        return (
            <AuthenticatedLayout user={auth.user}>
                <Head title="Match Not Found" />
                <div className="p-6 text-center text-lg">Match not found</div>
            </AuthenticatedLayout>
        );
    }

    const opponent = getOpponent();

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={`Match - ${match.game.name}`} />

            <div className="max-w-3xl mx-auto p-6 space-y-6">

                {/* Header */}
                <div className="flex items-center justify-between">
                    <Link href="/lobby" className="text-blue-600 hover:underline">
                        ← Back to Lobby
                    </Link>

                    <div className="text-xl font-bold">{match.game.name}</div>

                    <div className="text-gray-600">
                        Playing against <span className="font-semibold">{opponent.name}</span>
                    </div>
                </div>

                {/* Error Message */}
                {error && (
                    <div className="p-3 bg-red-100 text-red-700 rounded">
                        {error}
                    </div>
                )}

                {/* Game Board */}
                <div className="flex justify-center">
                    <TicTacToeBoard
                        match={match}
                        currentUser={auth.user}
                        onMove={handleMove}
                        disabled={making || match.status !== 'active'}
                    />
                </div>

                {/* Move History */}
                {match.moves && match.moves.length > 0 && (
                    <div className="bg-white shadow rounded p-4">
                        <h2 className="text-lg font-semibold mb-3">Move History</h2>

                        <div className="space-y-2">
                            {match.moves.map((move, index) => (
                                <div
                                    key={index}
                                    className="flex justify-between border-b pb-2"
                                >
                                    <div>Move {index + 1}</div>
                                    <div>{move.user.name} – Position {move.move_data.position}</div>
                                    <div>{new Date(move.created_at).toLocaleTimeString()}</div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* New Game Button */}
                {match.status === 'completed' && (
                    <div className="text-center">
                        <Link
                            href="/lobby"
                            className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Start New Game
                        </Link>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}