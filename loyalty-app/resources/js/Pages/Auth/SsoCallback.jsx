import { useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import api from '../../api';

export default function SsoCallback() {
    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const token = params.get('token');

        if (!token) {
            router.visit('/');
            return;
        }

        localStorage.setItem('auth_token', token);

        (async () => {
            try {
                const response = await api.get(`${import.meta.env.VITE_PENJAHITKU_API_URL}/api/user`);
                localStorage.setItem('user', JSON.stringify(response.data.user));
                router.visit('/dashboard');
            } catch (error) {
                console.error('Gagal mengambil profil user loyalty', error);
                localStorage.removeItem('auth_token');
                router.visit('/');
            }
        })();
    }, []);

    return (
        <div className="min-h-screen flex flex-col items-center justify-center space-y-2">
            <Head title="Menyiapkan sesi" />
            <p className="text-lg font-medium">Menghubungkan ke Penjahitku...</p>
            <p className="text-sm text-gray-500">Tunggu sebentar, kamu akan diarahkan ke dashboard loyalty.</p>
        </div>
    );
}
