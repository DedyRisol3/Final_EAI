import { useEffect, useState } from 'react';
import { Head, router } from '@inertiajs/react';
import api from '../../api';

const getPenjahitkuAppUrl = () => {
    if (import.meta.env.VITE_PENJAHITKU_APP_URL) {
        return import.meta.env.VITE_PENJAHITKU_APP_URL;
    }

    if (import.meta.env.VITE_PENJAHITKU_API_URL) {
        return import.meta.env.VITE_PENJAHITKU_API_URL.replace(/\/api\/?$/i, '');
    }

    return '';
};

const getSsoCallbackUrl = () => {
    const base = import.meta.env.VITE_LOYALTY_APP_URL || window.location.origin;
    return `${base.replace(/\/$/, '')}/loyalty/sso/callback`;
};

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    useEffect(() => {
        const token = localStorage.getItem('auth_token');
        const storedUser = localStorage.getItem('user');
        if (token && storedUser) {
            router.visit('/dashboard');
        }
    }, []);

    const submit = async (e) => {
        e.preventDefault();
        setError('');

        try {
            const response = await api.post(
                `${import.meta.env.VITE_PENJAHITKU_API_URL}/api/login`,
                { email, password }
            );

            console.log(response);

            if (response.data.status === 'success') {
                localStorage.setItem('auth_token', response.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.user));

                router.visit('/dashboard');
            } else {
                setError('Email atau password salah.');
            }
        } catch (err) {
            setError('Login gagal.');
        }
    };

    const openPenjahitkuLogin = (path) => {
        const base = getPenjahitkuAppUrl();

        if (!base) {
            setError('URL Penjahitku belum dikonfigurasi.');
            return;
        }

        const callback = encodeURIComponent(getSsoCallbackUrl());
        window.location.href = `${base}${path}?callback=${callback}`;
    };

    return (
        <div className="max-w-md mx-auto mt-20">
            <Head title="Login Loyalty App" />
            <h1 className="text-2xl font-bold mb-4">Loyalty Login</h1>

            {error && <div className="text-red-500 mb-3">{error}</div>}

            <form onSubmit={submit} className="space-y-4">
                <input
                    type="email"
                    className="w-full border border-gray-300 p-2 rounded-2xl py-3 px-4 focus:border-indigo-700 focus:shadow-sm outline-none"
                    placeholder="Email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                />

                <input
                    type="password"
                    className="w-full border border-gray-300 p-2 rounded-2xl py-3 px-4 focus:border-indigo-700 focus:shadow-sm outline-none"
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                />

                <button
                    type="submit"
                    className="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-2xl hover:bg-indigo-900"
                >
                    Login
                </button>
            </form>

            <div className="mt-6 space-y-3">
                <p className="text-sm text-gray-500">
                    Atau masuk melalui Penjahitku (termasuk Google / Socialite). Kami membuat token Sanctum dan
                    membawa kamu kembali ke aplikasi loyalty.
                </p>
                <button
                    type="button"
                    onClick={() => openPenjahitkuLogin('/loyalty/authorize')}
                    className="w-full bg-gray-100 border border-gray-300 text-gray-900 font-semibold py-3 px-4 rounded-2xl hover:bg-gray-200"
                >
                    Masuk lewat Penjahitku (tanpa password)
                </button>
                <button
                    type="button"
                    onClick={() => openPenjahitkuLogin('/auth/google/redirect')}
                    className="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-2xl hover:bg-blue-800"
                >
                    Masuk lewat Google
                </button>
            </div>
        </div>
    );
}
