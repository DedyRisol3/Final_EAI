import { useEffect, useMemo, useState } from 'react';
import { Head, router } from '@inertiajs/react';
import api from '../api';

export default function Dashboard() {
    const [points, setPoints] = useState(0);
    const [transactions, setTransactions] = useState([]);
    const [redemptions, setRedemptions] = useState([]);
    const user = useMemo(() => {
        const storedUser = localStorage.getItem('user');
        return storedUser ? JSON.parse(storedUser) : null;
    }, []);

    useEffect(() => {
        const token = localStorage.getItem('auth_token');
        if (!token || !user) {
            router.visit('/');
        }
    }, [user]);

    useEffect(() => {
        if (!user) return;

        async function fetchPoints() {
            try {
                const res = await api.get(
                    `${import.meta.env.VITE_LOYALTY_SERVICE_URL}/api/points/user/${user.id}`
                );
                setPoints(res.data.total_points);
            } catch (error) {
                console.error('Failed to fetch points', error);
            }
        }

        async function fetchHistory() {
            try {
                const res = await api.get(
                    `${import.meta.env.VITE_LOYALTY_SERVICE_URL}/api/history/${user.id}`
                );
                setTransactions(res.data.transactions || []);
                setRedemptions(res.data.redemptions || []);
            } catch (error) {
                console.error('Failed to fetch history', error);
            }
        }

        fetchPoints();
        fetchHistory();
    }, [user]);

    const logout = () => {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        router.visit('/');
    };

    if (!user) {
        return null;
    }

    return (
        <div className="max-w-xl mx-auto mt-10 space-y-6">
            <Head title="Dashboard Loyalty" />

            <div className="flex justify-between items-center">
                <h1 className="text-3xl font-bold">Loyalty Dashboard</h1>
                <button
                    onClick={logout}
                    className="bg-red-600 text-white px-3 py-1 rounded"
                >
                    Logout
                </button>
            </div>

            <div className="mt-4 bg-gray-100 p-4 rounded shadow">
                <p className="text-xl">Hello, {user?.name ?? 'Pengguna'}</p>
                <p className="text-2xl font-bold mt-2">
                    Poin Kamu: {points}
                </p>
            </div>

            <div className="bg-white p-4 rounded shadow space-y-3">
                <h2 className="text-xl font-semibold mb-2">Transaksi Poin</h2>
                {transactions.length === 0 && (
                    <p className="text-gray-500">Belum ada transaksi poin.</p>
                )}
                {transactions.map((t, index) => (
                    <div key={index} className="border-b border-gray-200 py-2 last:border-b-0">
                        <div className="flex justify-between">
                            <span className="font-medium">
                                {t.type === 'earn' ? 'Earn Poin' : 'Redeem Poin'}
                            </span>
                            <span className={t.amount > 0 ? 'text-green-600' : 'text-red-600'}>
                                {t.amount}
                            </span>
                        </div>
                        <p className="text-sm text-gray-600">{t.description}</p>
                        <p className="text-xs text-gray-400">{t.created_at}</p>
                    </div>
                ))}
            </div>

            <div className="bg-white p-4 rounded shadow space-y-3">
                <h2 className="text-xl font-semibold mb-2">Redeem Diskon</h2>
                {redemptions.length === 0 && (
                    <p className="text-gray-500">Belum ada redeem untuk diskon.</p>
                )}
                {redemptions.map((r, index) => (
                    <div key={index} className="border-b border-gray-200 py-2 last:border-b-0">
                        <div className="flex justify-between">
                            <span className="font-medium">
                                Redeem Diskon (Order #{r.order_id ?? '-'})
                            </span>
                            <span className="text-red-600">
                                -{r.points_used} poin
                            </span>
                        </div>
                        <p className="text-sm text-gray-600">
                            Diskon: Rp {Number(r.discount_value || 0).toLocaleString('id-ID')}
                        </p>
                        <p className="text-xs text-gray-400">{r.created_at}</p>
                    </div>
                ))}
            </div>
        </div>
    );
}
