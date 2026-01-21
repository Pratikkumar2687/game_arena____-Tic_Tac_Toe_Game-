import { useState } from 'react';

export default function TicTacToeBoard({ match, currentUser, onMove, disabled }) {
    const [selectedCell, setSelectedCell] = useState(null);

    const board = match.state?.board || Array(9).fill(null);
    const isMyTurn = match.current_turn_user_id === currentUser.id;
    const mySymbol = match.player_one_id === currentUser.id ? 'X' : 'O';

    const handleCellClick = async (index) => {
        if (disabled || !isMyTurn || board[index] !== null) return;

        setSelectedCell(index);

        try {
            await onMove(index);
        } catch (error) {
            console.error('Move failed:', error);
        } finally {
            setSelectedCell(null);
        }
    };

    const getCellClass = (index) => {
        const baseClass =
            "aspect-square flex items-center justify-center text-6xl font-bold border-2 border-gray-300 transition cursor-pointer";

        if (board[index] !== null) {
            return `${baseClass} ${board[index] === 'X' ? 'text-blue-600' : 'text-red-600'
                } cursor-not-allowed`;
        }

        if (!isMyTurn || disabled) {
            return `${baseClass} cursor-not-allowed opacity-50`;
        }

        if (selectedCell === index) {
            return `${baseClass} bg-gray-200`;
        }

        return `${baseClass} hover:bg-gray-100`;
    };

    return (
        <div className="space-y-4">
            {/* Board */}
            <div className="grid grid-cols-3 gap-2 w-64 mx-auto">
                {board.map((cell, index) => (
                    <div
                        key={index}
                        className={getCellClass(index)}
                        onClick={() => handleCellClick(index)}
                    >
                        {cell}
                    </div>
                ))}
            </div>

            {/* Status Message */}
            <div className="text-center text-xl font-semibold mt-4">
                {match.status === 'completed' ? (
                    match.winner_user_id ? (
                        match.winner_user_id === currentUser.id ? (
                            <span>You Won! 🎉</span>
                        ) : (
                            <span>You Lost</span>
                        )
                    ) : (
                        <span>Draw</span>
                    )
                ) : isMyTurn ? (
                    <span>Your Turn ({mySymbol})</span>
                ) : (
                    <span>Opponent's Turn</span>
                )}
            </div>
        </div>
    );
}